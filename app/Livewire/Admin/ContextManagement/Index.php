<?php

namespace App\Livewire\Admin\ContextManagement;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use LLPhant\Embeddings\DataReader\FileDataReader;
use LLPhant\Embeddings\DocumentSplitter\DocumentSplitter;
use LLPhant\Embeddings\EmbeddingFormatter\EmbeddingFormatter;
use LLPhant\Embeddings\EmbeddingGenerator\OpenAI\OpenAIADA002EmbeddingGenerator;
use LLPhant\Embeddings\VectorStores\FileSystem\FileSystemVectorStore;
use LLPhant\Chat\OpenAIChat;
use LLPhant\OpenAIConfig;
use LLPhant\Query\SemanticSearch\QuestionAnswering;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    public $contextContent = '';
    public $editing = false;
    public $showModal = false;

    public $confirmingReset = false;

    protected $rules = [
        'contextContent' => 'required|string',
    ];

    public function mount()
    {
        $this->loadContext();
    }

    public function loadContext()
    {
        $contextFilePath = base_path('app/Http/mental_health_context.txt');

        if (File::exists($contextFilePath)) {
            $this->contextContent = File::get($contextFilePath);
        } else {
            $this->contextContent = '';
        }
    }

    public function render()
    {
        return view('livewire.admin.context-management.index')
            ->layout('layouts.admin');
    }

    public function openEditModal()
    {
        $this->editing = true;
        $this->showModal = true;
    }

    public function saveContext()
    {
        $this->validate();

        $contextFilePath = base_path('app/Http/mental_health_context.txt');
        $vectorStorePath = public_path('documents-vectorStore.json');

        try {
            // Write the new content to the context file
            File::put($contextFilePath, $this->contextContent);
            Log::info('Context file updated successfully.');

            // Delete the existing vector store if it exists
            if (File::exists($vectorStorePath)) {
                // Optional: Backup the existing vector store
                $backupPath = public_path('documents-vectorStore_backup_' . now()->timestamp . '.json');
                File::copy($vectorStorePath, $backupPath);
                Log::info('Vector store backed up successfully to ' . $backupPath);

                // Delete the existing vector store
                File::delete($vectorStorePath);
                Log::info('Vector store deleted successfully.');
            }

            // Regenerate the vector store
            $this->regenerateVectorStore($contextFilePath, $vectorStorePath);

            // Reset modal states
            $this->showModal = false;
            $this->editing = false;

            session()->flash('message', 'Context updated and vector store regenerated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating context or regenerating vector store', ['error' => $e->getMessage()]);
            session()->flash('error', 'An error occurred while updating the context. Please try again.');
        }
    }

    private function regenerateVectorStore($contextFilePath, $vectorStorePath)
    {
        try {
            // Initialize the embedding generator and vector store
            $embeddingGenerator = new OpenAIADA002EmbeddingGenerator();
            $vectorStore = new FileSystemVectorStore();

            // Load and process the context file
            $dataReader = new FileDataReader($contextFilePath);
            $documents = $dataReader->getDocuments();
            $splittedDocs = DocumentSplitter::splitDocuments($documents, 2000);
            $formattedDocs = EmbeddingFormatter::formatEmbeddings($splittedDocs);

            // Generate embeddings
            $embeddedDocs = $embeddingGenerator->embedDocuments($formattedDocs);
            Log::info('Embeddings generated successfully.');

            // Add documents to the vector store
            $vectorStore->addDocuments($embeddedDocs);
            Log::info('Documents added to vector store successfully.');

            // **Directly serialize the embedded documents to JSON**
            File::put($vectorStorePath, json_encode($embeddedDocs, JSON_PRETTY_PRINT));
            Log::info('Vector store regenerated and saved successfully.');
        } catch (\Exception $e) {
            Log::error('Error during vector store regeneration', ['error' => $e->getMessage()]);
            throw $e; // Rethrow to be caught in the parent method
        }
    }

    public function confirmReset()
    {
        $this->confirmingReset = true;
    }

    public function resetContext()
    {
        $defaultContent = "1. **Mental Health Basics**:
   - **Definitions and Concepts**: Mental health refers to the emotional, psychological, and social well-being of an individual. It influences how people think, feel, and act. Mental illness encompasses a range of mental health disorders that affect mood, thinking, and behavior, such as depression, anxiety, schizophrenia, and bipolar disorder. Basic understanding of these terms and their impact on overall life satisfaction and functioning is foundational.
   - **Symptoms and Diagnosis**: Mental health symptoms can vary greatly in terms of frequency, intensity, and duration. Diagnostic criteria for conditions like anxiety, depression, and PTSD help in distinguishing typical behavior from disorder-based patterns. Each diagnosis involves symptom clusters that professionals use to create structured treatment plans.
   - **Treatment Modalities**: Various treatment options, such as therapy and medication, are employed to manage mental health disorders. Common therapeutic approaches include Cognitive Behavioral Therapy (CBT), Dialectical Behavior Therapy (DBT), and Mindfulness-Based Stress Reduction (MBSR). Each therapy focuses on altering specific behaviors, thoughts, or emotions for overall improvement.

2. **Psychological Theories and Approaches**:
   - **Theories of Mind and Personality**: Core theories like psychoanalytic, cognitive-behavioral, and humanistic theories offer insights into personality and mental health. Cognitive theories, for example, focus on how thought patterns affect emotions and behaviors, while humanistic approaches prioritize individual potential and self-growth.
   - **Attachment Theory**: Attachment theory explores how early relationships influence emotional development. Attachment styles—secure, avoidant, anxious, and disorganized—affect how individuals relate to others, forming a basis for understanding behavior in relationships.
   - **Developmental Psychology**: This branch examines psychological changes across a lifespan, helping to understand age-related mental health challenges, like adolescent self-esteem issues or elderly depression. Developmental psychology underscores the need for age-appropriate interventions.

3. **Therapeutic Techniques and Coping Mechanisms**:
   - **CBT Techniques**: Cognitive Behavioral Therapy uses methods like thought restructuring and behavioral activation to combat negative thinking patterns. Exposure therapy, commonly used in anxiety treatment, gradually exposes individuals to fear triggers to reduce avoidance behaviors.
   - **Mindfulness and Relaxation Techniques**: Mindfulness practices, such as guided meditations, grounding exercises, and deep breathing, improve focus on the present moment and reduce stress. These techniques support emotional regulation and resilience against negative thoughts.
   - **Emotional Regulation Skills**: Derived from DBT, these skills include distress tolerance, which helps individuals cope with intense emotions, and radical acceptance, which involves accepting situations without judgment, fostering healthier reactions to stress.

4. **Interventions for Common Mental Health Issues**:
   - **Anxiety Management**: Techniques like cognitive restructuring, lifestyle changes, and exposure therapy assist individuals in managing various types of anxiety. Cognitive restructuring helps in challenging irrational fears, while lifestyle interventions, such as exercise and sleep hygiene, provide holistic benefits.
   - **Depression Support**: Addressing depression involves engaging in structured daily routines, increasing positive social interactions, and fostering behavioral activation by re-engaging in enjoyable activities. Supportive measures focus on breaking cycles of isolation and inactivity.
   - **Stress and Burnout Management**: Burnout prevention techniques emphasize setting boundaries, prioritizing self-care, and building a supportive network. Stress management includes relaxation practices, healthy lifestyle choices, and regular mental health check-ins.

5. **Psychiatric Research and Evidence-Based Studies**:
   - **Mental Health Outcomes Research**: Research into mental health outcomes sheds light on the effectiveness of therapies, such as CBT or DBT, across various populations. Studies highlight benefits of therapy in improving quality of life and reducing symptoms in disorders like depression and PTSD.
   - **Medication and Biological Factors**: Understanding biological factors, including neurochemistry and the role of genetics in mental health, assists in tailoring treatments. Medications, such as antidepressants and antipsychotics, influence brain function and are often combined with therapy for effective outcomes.

6. **Self-Help and Supportive Content**:
   - **Goal Setting and Personal Growth**: Effective goal-setting is central to mental health recovery, fostering personal growth and resilience. Techniques like SMART goals help individuals achieve measurable progress, improving self-efficacy and mental wellness.
   - **Grief and Trauma Support**: Trauma and grief therapies involve helping individuals process their experiences, accept their emotions, and develop healthy coping strategies. Techniques include trauma-focused CBT, which addresses trauma memories, and expressive writing for grief processing.
   - **Relationship and Communication Skills**: Training individuals in conflict resolution, empathy, and assertive communication helps improve relationships. Positive communication builds understanding and decreases relational stress.

7. **Cultural and Societal Perspectives**:
   - **Mental Health Stigma**: Recognizing the impact of stigma, particularly cultural stigma, on mental health treatment is essential. Cultural awareness helps mental health coaches foster acceptance and understanding, especially in communities where seeking help is discouraged.
   - **Social Determinants of Health**: Socioeconomic factors, including income, education, and access to resources, significantly affect mental health. Understanding these determinants allows mental health professionals to offer tailored support.

8. **Wellness and Preventative Mental Health Practices**:
   - **Exercise and Mental Health**: Physical activity is linked to improved mental health through the release of endorphins and reduced stress hormones. Regular exercise contributes to overall well-being and can alleviate symptoms of anxiety and depression.
   - **Sleep Hygiene and Nutrition**: Healthy sleep and balanced nutrition are vital for mental health. Sleep hygiene techniques, such as maintaining a consistent sleep schedule, and nutrition that supports brain health (like omega-3s) can prevent and alleviate mental health issues.
   - **Substance Use and Addiction Recovery**: Coping strategies for addiction, such as motivational interviewing, relapse prevention, and peer support, play an important role in mental health recovery. Recovery focuses on rebuilding habits and providing support structures for long-term resilience.

This guide provides a foundational understanding of key areas in mental health coaching, equipping a coach with the knowledge needed to support clients effectively.";

        $this->contextContent = $defaultContent;

        $this->saveContext();

        $this->confirmingReset = false;
    }

    public function cancelReset()
    {
        $this->confirmingReset = false;
    }
}
