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
    public $coachType = 'mental_health'; // Default coach type

    public function mount()
    {
        // Initialize with a welcome message from the assistant based on coach type
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $this->getWelcomeMessage(),
        ];
    }

    public function sendMessage()
    {
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
        $welcomeMessages = [
            'mental_health' => 'Hello! I\'m Run On Empathy, your Mental Health Coach. How can I assist you today?',
            'career' => 'Hello! I\'m Run On Empathy, your Career Coach. How can I help you with your career goals?',
            'wellness' => 'Hello! I\'m Run On Empathy, your Wellness Coach. How can I support your well-being today?',
            // Add more welcome messages for different coaches
        ];

        return $welcomeMessages[$this->coachType] ?? 'Hello! How can I assist you today?';
    }

    private function chatWithAI($input)
    {
        $openaiService = config('services.openai');
        $config = new OpenAIConfig();
        $config->apiKey = $openaiService['api_key'];
    
        // Define model based on coach type if needed
        $modelMap = [
            'mental_health' => 'gpt-4o-mini',
            'career' => 'gpt-4o-mini',
            'wellness' => 'gpt-4o-mini',
            // Map more coach types to specific models if desired
        ];
        $config->model = $modelMap[$this->coachType] ?? 'gpt-4o-mini';
    
        $config->modelOptions = [
            'presence_penalty' => 0.6, // Increased to encourage new topics
            'frequency_penalty' => 0.3, // Increased to reduce repetition
            'max_tokens' => 200, // Limit AI response to 200 tokens
        ];
        
    
        $chat = new OpenAIChat($config);
    
        $vectorStore = new FileSystemVectorStore();
        $embeddingGenerator = new OpenAIADA002EmbeddingGenerator();
    
        if ($vectorStore->getNumberOfDocuments() === 0) {
            // Load context from a file based on coach type
            $contextFileMap = [
                'mental_health' => base_path('app/Http/Controllers/mental_health_context.txt'),
                'career' => base_path('app/Http/Controllers/career_context.txt'),
                'wellness' => base_path('app/Http/Controllers/wellness_context.txt'),
                // Add more context files for different coaches
            ];
    
            $contextFile = $contextFileMap[$this->coachType] ?? base_path('app/Http/Controllers/context.txt');
    
            $dataReader = new FileDataReader($contextFile);
            $documents = $dataReader->getDocuments();
            $splittedDocs = DocumentSplitter::splitDocuments($documents, 2000);
            $formattedDocs = EmbeddingFormatter::formatEmbeddings($splittedDocs);
    
            $embeddedDocs = $embeddingGenerator->embedDocuments($formattedDocs);
            $vectorStore->addDocuments($embeddedDocs);
        }
    
        // Retrieve user-specific answers to build context
        $userContext = $this->getUserContext();
    
        // Customize system message based on coach type
        $customSystemMessages = [
            'mental_health' => 'You name is Run On Empathy, a compassionate Mental Health Coach dedicated to supporting the Black community. Engage in friendly, empathetic, and conversational dialogue to help users navigate their mental well-being. Use a warm and understanding tone, and ensure your responses are supportive and non-judgmental. When providing information or advice, structure your responses using bullet points or numbered lists for clarity.',
            'career' => 'You name is Run On Empathy, a knowledgeable Career Coach committed to helping individuals in the Black community achieve their professional goals. Interact in a conversational and encouraging manner, offering insightful advice and practical guidance tailored to each user\'s career aspirations. Use bullet points or numbered lists to organize your responses when appropriate.',
            'wellness' => 'You name is Run On Empathy, a dedicated Wellness Coach focused on promoting holistic well-being within the Black community. Communicate in a friendly and conversational tone, providing guidance on physical health, nutrition, and maintaining a balanced lifestyle in an approachable manner. Structure your responses with bullet points or numbered lists to enhance readability.',
            // Add more system messages for different coaches as needed
        ];
    
        $customSystemMessage = $customSystemMessages[$this->coachType] ?? 'You are an AI assistant. Engage in friendly and conversational dialogue to help users with their needs. Structure your responses using bullet points or numbered lists where appropriate.';
    
        $qa = new QuestionAnswering($vectorStore, $embeddingGenerator, $chat);
        $qa->systemMessageTemplate = $customSystemMessage;
    
        // Combine context and user input with clear separation
        $fullPrompt = $userContext . "\nUser: " . $input . "\nAssistant (please respond in a structured and organized manner):";
    
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
