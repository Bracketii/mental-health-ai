<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use LLPhant\Chat\Message;
use LLPhant\OpenAIConfig;
use App\Models\UserAnswer;
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
    private $coachType = 'mental_health'; // Fixed coach type

    public function mount()
    {
        // Initialize with a welcome message from the Mental Health Coach
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $this->getWelcomeMessage(),
        ];
    }

    public function sendMessage()
    {
        // Check if the user is paid
        // if (!auth()->user()->isPaid()) {
        //     // Display an error message or redirect as appropriate
        //     session()->flash('error', 'You need to be a paid subscriber to send messages.');
        //     return;
        // }

        $userMessage = trim($this->inputMessage);

        if ($userMessage === '') {
            return;
        }

        // Add user message to chat
        $this->messages[] = [
            'role' => 'user',
            'content' => $userMessage,
        ];

        // Clear input
        $this->inputMessage = '';

        // Process AI response
        $aiResponse = $this->chatWithAI($userMessage);

        // Add AI response to chat
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $aiResponse,
        ];

        // Save conversation to database
        $this->saveConversation($userMessage, $aiResponse);
    }

    private function getWelcomeMessage()
    {
        return 'Hello! I\'m Run On Empathy, your Mental Health Coach. How can I assist you today?';
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
    
        // Define model for Mental Health Coach
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
    
        // Customize system message for Mental Health Coach
        $customSystemMessage = 'Your name is Run On Empathy, a compassionate Mental Health Coach. Always give short answers. Your goal is to provide empathetic, and conversational dialogue to help users navigate their mental well-being. Use a warm and understanding tone, short answers and suggestions and ensure your responses are supportive and non-judgmental. When providing information or advice, structure your responses using bullet points or numbered lists for clarity. \n\n{userContext}.';
    
        $qa = new QuestionAnswering($vectorStore, $embeddingGenerator, $chat);
        $qa->systemMessageTemplate = $customSystemMessage;
    
        // Combine context and user input with clear separation
        $fullPrompt = $history . "\n" . $userContext . "\nUser: " . $input . "\nAssistant (please respond in a structured and organized manner):";
    
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

    private function saveConversation($userMessage, $aiResponse)
    {
        // Save the conversation to the database
        ConversationHistory::create([
            'user_id' => Auth::id(),
            'coach_type' => $this->coachType,
            'user_message' => $userMessage,
            'ai_response' => $aiResponse,
            'created_at' => now(),
        ]);
    }

    public function render()
    {
        return view('livewire.chat.coach-chat');
    }
}
