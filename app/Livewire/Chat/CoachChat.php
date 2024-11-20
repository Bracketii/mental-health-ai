<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use LLPhant\Chat\Message;
use LLPhant\OpenAIConfig;
use App\Models\UserAnswer;
use App\Models\SystemMessage; // Import SystemMessage model
use LLPhant\Chat\OpenAIChat;
use LLPhant\Chat\Enums\ChatRole;
use App\Models\ConversationHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use LLPhant\Embeddings\DataReader\FileDataReader;
use LLPhant\Query\SemanticSearch\QuestionAnswering;
use LLPhant\Embeddings\DocumentSplitter\DocumentSplitter;
use LLPhant\Embeddings\EmbeddingFormatter\EmbeddingFormatter;
use LLPhant\Embeddings\VectorStores\FileSystem\FileSystemVectorStore;
use LLPhant\Embeddings\EmbeddingGenerator\OpenAI\OpenAIADA002EmbeddingGenerator;

class CoachChat extends Component
{
    public $messages = [];
    public $inputMessage = '';
    // Removed public $isTyping;

    private $coachType = 'mental_health'; // Fixed coach type

    public function mount()
    {
        $user = Auth::user();

        // Fetch coach information
        $coach = $user->coach;

        // Fetch welcome message from system messages
        if ($coach) {
            $welcomeMessageRecord = SystemMessage::where('type', 'welcome_coach')->first();
            if ($welcomeMessageRecord) {
                $welcomeMessage = str_replace(
                    ['{name}', '{designation}'],
                    [$coach->name, $coach->designation],
                    $welcomeMessageRecord->content
                );
            } else {
                // Fallback if system message not found
                $welcomeMessage = "Hello! I'm {$coach->name}, your {$coach->designation}. How can I assist you today?";
            }
        } else {
            $welcomeMessageRecord = SystemMessage::where('type', 'welcome_default')->first();
            if ($welcomeMessageRecord) {
                $welcomeMessage = $welcomeMessageRecord->content;
            } else {
                // Fallback if system message not found
                $welcomeMessage = "Hello! I'm your Mental Health Coach. How can I assist you today?";
            }
        }

        // Load existing conversations
        $conversations = $this->getRecentConversations();

        if ($conversations->isEmpty()) {
            // Initialize with a welcome message from the Coach
            $this->messages[] = [
                'role' => 'assistant',
                'content' => $welcomeMessage,
                'type' => 'ai',
            ];
        } else {
            // Load existing messages
            foreach ($conversations as $conversation) {
                $this->messages[] = [
                    'role' => 'user',
                    'content' => $conversation->user_message,
                    'type' => 'user',
                ];
                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => $conversation->ai_response,
                    'type' => 'ai',
                ];
            }
        }
    }

    public function sendMessage()
    {
        $user = Auth::user();

        // Check if the user is paid
        if (!$user->hasActiveSubscription()) {
            $this->dispatchBrowserEvent('not-paid');
            return;
        }

        $userMessage = trim($this->inputMessage);

        if ($userMessage === '') {
            return;
        }

        // Add user message to chat
        $this->messages[] = [
            'role' => 'user',
            'content' => $userMessage,
            'type' => 'user',
        ];

        // Clear input
        $this->inputMessage = '';

        // Save user message to conversation history
        $conversation = ConversationHistory::create([
            'user_id' => Auth::id(),
            'coach_type' => $this->coachType,
            'user_message' => $userMessage,
            'ai_response' => '',
            'created_at' => now(),
        ]);

        // Process AI response
        $aiResponse = $this->chatWithAI($userMessage);

        // Update the conversation history with AI response
        $conversation->ai_response = $aiResponse;
        $conversation->save();

        // Add AI response to chat
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $aiResponse,
            'type' => 'ai',
        ];
    }

    private function getWelcomeMessage()
    {
        // Not used anymore since welcome message is fetched from system messages
        return '';
    }

    private function buildContext($conversations)
    {
        return $conversations->reduce(function ($carry, $conversation) {
            // Append the user message
            $carry .= "User: " . $conversation->user_message . "\n";

            // Truncate the AI response if it's too long
            $truncatedResponse = mb_substr($conversation->ai_response, 0, 200); // Adjust character limit as needed
            $carry .= "Assistant: " . $truncatedResponse . (mb_strlen($conversation->ai_response) > 200 ? '...' : '') . "\n";

            return $carry;
        }, "");
    }

    private function getRecentConversations()
    {
        return ConversationHistory::where('user_id', Auth::id())
            ->latest() // Orders by created_at in descending order by default
            ->take(6) // Limits to the 6 most recent conversations
            ->get()
            ->reverse(); // Reverses the order to show oldest first among the 6
    }

    private function chatWithAI($input)
    {
        $openaiService = config('services.openai');
        $config = new OpenAIConfig();
        $config->apiKey = $openaiService['api_key'];

        // Fetch coach information
        $user = Auth::user();
        $coach = $user->coach;

        // Fetch system message from database
        if ($coach) {
            $systemMessageRecord = SystemMessage::where('type', 'system_coach')->first();
            if ($systemMessageRecord) {
                $systemMessage = str_replace(
                    ['{name}', '{designation}'],
                    [$coach->name, $coach->designation],
                    $systemMessageRecord->content
                );
            } else {
                // Fallback if system message not found
                $systemMessage = "Your name is {$coach->name}, a {$coach->designation}. Always give short answers. Your goal is to provide empathetic, and conversational dialogue to help users navigate their mental well-being. Use a warm and understanding tone, short answers and suggestions and ensure your responses are supportive and non-judgmental. When providing information or advice, structure your responses using bullet points or numbered lists for clarity.";
            }
        } else {
            $systemMessageRecord = SystemMessage::where('type', 'system_default')->first();
            if ($systemMessageRecord) {
                $systemMessage = $systemMessageRecord->content;
            } else {
                // Fallback if system message not found
                $systemMessage = 'Your name is Run On Empathy, a compassionate Mental Health Coach. Always give short answers. Your goal is to provide empathetic, and conversational dialogue to help users navigate their mental well-being. Use a warm and understanding tone, short answers and suggestions and ensure your responses are supportive and non-judgmental. When providing information or advice, structure your responses using bullet points or numbered lists for clarity.';
            }
        }

        $config->model = 'gpt-4o-mini';

        $config->modelOptions = [
            'presence_penalty' => 0.6, // Encourage new topics
            'frequency_penalty' => 0.3, // Reduce repetition
        ];

        $chat = new OpenAIChat($config);

        $vectorStore = new FileSystemVectorStore();
        $embeddingGenerator = new OpenAIADA002EmbeddingGenerator();

        if ($vectorStore->getNumberOfDocuments() === 0) {
            // Load context from the Mental Health context file
            $contextFile = base_path('app/Http/mental_health_context.txt');

            $dataReader = new FileDataReader($contextFile);
            $documents = $dataReader->getDocuments();
            $splittedDocs = DocumentSplitter::splitDocuments($documents, 2000);
            $formattedDocs = EmbeddingFormatter::formatEmbeddings($splittedDocs);

            $embeddedDocs = $embeddingGenerator->embedDocuments($formattedDocs);
            $vectorStore->addDocuments($embeddedDocs);
        }

        // Retrieve user-specific answers to build context
        $userContext = $this->getUserContext();

        $recentConversations = $this->getRecentConversations();
        $history = $this->buildContext($recentConversations);

        // Combine system message, history, and user context
        $fullPrompt = $history . "\n" . $userContext . "\nUser: " . $input . "\nAssistant (please respond in a structured and organized manner):";

        $qa = new QuestionAnswering($vectorStore, $embeddingGenerator, $chat);
        $qa->systemMessageTemplate = $systemMessage;

        $message = new Message();
        $message->content = $fullPrompt;
        $message->role = ChatRole::User;

        try {
            $stream = $qa->answerQuestionFromChat([$message]);
            if ($stream instanceof \Psr\Http\Message\StreamInterface) {
                return $stream->getContents();
            }
        } catch (\Exception $e) {
            Log::error('Error processing chat completion', ['error' => $e->getMessage()]);
            return 'Sorry, something went wrong while processing your request.';
        }

        return 'Sorry, I could not process your request.';
    }

    private function getUserContext()
    {
        $user = Auth::user();
        $answers = UserAnswer::with(['question', 'option'])
                    ->where('user_id', $user->id)
                    ->orderBy('question_id')
                    ->get();

        if ($answers->isEmpty()) {
            return "The user hasn't provided additional context yet.";
        }

        $context = "Here is some background information about the user:\n";

        foreach ($answers as $answer) {
            $context .= "• **" . $answer->question->text . "** " . $answer->option->text . "\n";
        }

        return $context;
    }

    public function startNewChat()
    {
        // **Delete previous conversations**
        ConversationHistory::where('user_id', Auth::id())->delete();

        // **Clear current messages and add welcome message**
        $this->messages = [];

        // Fetch welcome message again after deleting conversations
        $this->mount();
    }

    public function render()
    {
        // Fetch coach info for avatar display
        $coach = Auth::user()->coach;

        // Get user's gender
        $userGender = ucfirst(Auth::user()->gender ?? 'User');

        return view('livewire.chat.coach-chat', [
            'coach' => $coach,
            'userGender' => $userGender,
        ]);
    }
}
