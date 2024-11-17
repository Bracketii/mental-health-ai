<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Option;

class QuestionsTableSeeder extends Seeder
{
   /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'text' => 'How often do you feel stressed?',
                'order' => 1,
                'options' => ['Never', 'Sometimes', 'Often', 'Always'],
            ],
            [
                'text' => 'How do you rate your sleep quality?',
                'order' => 2,
                'options' => ['Poor', 'Fair', 'Good', 'Excellent'],
            ],
            [
                'text' => 'Are you currently in a relationship?',
                'order' => 3,
                'options' => ['Yes', 'No'],
            ],
            [
                'text' => 'What is your relationship status?',
                'order' => 4,
                'options' => [
                    '❤️ In a long-term relationship',
                    '💞 In a new relationship',
                    '💍 Married',
                    '😊 Other',
                    '🤔 Prefer not to say',
                ],
            ],
            [
                'text' => 'Do you feel satisfied in your current relationship?',
                'order' => 5,
                'options' => [
                    '😣 Not satisfied at all',
                    '😕 Not very satisfied',
                    '😐 Somewhat satisfied',
                    '😍 Very satisfied',
                    '🤔 Not sure',
                ],
            ],
            [
                'text' => 'How would you describe your relationship?',
                'order' => 6,
                'options' => [
                    '🎢 Experiencing ups and downs',
                    '😔 Emotionally disconnected',
                    '🥺 Resentful and filled with distrust',
                    '💔 Unbalanced or manipulative',
                    '😊 Happy and fulfilling',
                ],
            ],
            [
                'text' => 'What is your primary goal for exploring your attachment style?',
                'order' => 7,
                'options' => [
                    '💪 Strengthen my current relationship',
                    '❤️ Feel more emotionally connected to my partner',
                    '🔒 Improve trust and security',
                    '🚶 Leave or recover from a toxic relationship',
                    '💎 Transform my relationship into a high-value one',
                    '✅ All of the above',
                ],
            ],
            [
                'text' => 'Do you agree with the statement: "Sometimes I crave solitude, then suddenly need company?"',
                'order' => 8,
                'options' => [
                    '👍 Agree',
                    '👎 Disagree',
                ],
            ],
            [
                'text' => 'Do you agree with the statement: "I find it challenging to open up to others about my thoughts and feelings?"',
                'order' => 9,
                'options' => [
                    '👍 Agree',
                    '👎 Disagree',
                ],
            ],
            [
                'text' => 'Do you agree with the statement: "I tend to be out of touch with my emotions quite frequently?"',
                'order' => 10,
                'options' => [
                    '👍 Agree',
                    '👎 Disagree',
                ],
            ],
            [
                'text' => 'Do you agree with the statement: "I find it difficult to set boundaries?"',
                'order' => 11,
                'options' => [
                    '✅ True',
                    '❌ False',
                ],
            ],
            [
                'text' => 'Do you agree with the statement: "I avoid arguments with my partner, friends or family to avoid the chance of losing them?"',
                'order' => 12,
                'options' => [
                    '✅ True',
                    '❌ False',
                ],
            ],
            [
                'text' => 'Do you agree with the statement: "I often feel conflicted about wanting closeness in relationships but also fearing it?"',
                'order' => 13,
                'options' => [
                    '✅ True',
                    '❌ False',
                ],
            ],
            [
                'text' => 'Do you agree with the statement: "I worry that others won\'t care about me as much as I care about them?"',
                'order' => 14,
                'options' => [
                    '✅ Yes',
                    '❌ No',
                ],
            ],
            [
                'text' => 'Do you agree with the statement: "I tend to overthink and analyze every aspect of my relationships?"',
                'order' => 15,
                'options' => [
                    '✅ Yes',
                    '❌ No',
                ],
            ],
            [
                'text' => 'Do you agree with the statement: "I feel bad when my loved ones or friends do things without inviting me?"',
                'order' => 16,
                'options' => [
                    '✅ Yes',
                    '❌ No',
                ],
            ],
            [
                'text' => 'What emotions come up when you think about your childhood?',
                'order' => 17,
                'options' => [
                    '😊 Happiness and comfort',
                    '😟 Worry and insecurity',
                    '🧍 Emotional distance',
                    '😕 Confusion and mixed emotions',
                    '🧐 Other',
                ],
            ],
            [
                'text' => 'How do you typically manage stress?',
                'order' => 18,
                'options' => [
                    '🧘 Meditation or relaxation techniques',
                    '🏃 Physical activity or exercise',
                    '🎮 Engaging in hobbies or distractions',
                    '💬 Talking to friends or family',
                    '🤷 I don’t have a specific strategy',
                ],
            ],
            [
                'text' => 'Do you agree with the statement: "I feel overwhelmed by my responsibilities more often than not?"',
                'order' => 19,
                'options' => [
                    '✅ True',
                    '❌ False',
                ],
            ],
            [
                'text' => 'How often do you find yourself overthinking past mistakes?',
                'order' => 20,
                'options' => [
                    'Never',
                    'Rarely',
                    'Often',
                    'Always',
                ],
            ],
            [
                'text' => 'How comfortable are you sharing your feelings with close friends or family?',
                'order' => 21,
                'options' => [
                    '😟 Very uncomfortable',
                    '😕 Slightly uncomfortable',
                    '🙂 Neutral',
                    '😊 Comfortable',
                    '😍 Very comfortable',
                ],
            ],
            [
                'text' => 'Do you feel supported by those around you when facing challenges?',
                'order' => 22,
                'options' => [
                    '😣 Not at all',
                    '😕 Sometimes',
                    '😊 Most of the time',
                    '😍 Always',
                ],
            ],
            [
                'text' => 'Do you agree with the statement: "I tend to avoid asking for help even when I need it?"',
                'order' => 23,
                'options' => [
                    '👍 Agree',
                    '👎 Disagree',
                ],
            ],
            [
                'text' => 'What is your usual reaction when facing conflicts in relationships?',
                'order' => 24,
                'options' => [
                    '😶 Avoid addressing the issue',
                    '😡 React emotionally and impulsively',
                    '🗣️ Communicate openly and calmly',
                    '🤔 Overthink the situation before acting',
                    '🤷 Not sure',
                ],
            ],
            [
                'text' => 'How often do you feel disconnected from the people around you?',
                'order' => 25,
                'options' => [
                    'Never',
                    'Rarely',
                    'Sometimes',
                    'Often',
                    'Always',
                ],
            ],
            [
                'text' => 'Do you feel like you are able to balance your personal needs with the needs of others?',
                'order' => 26,
                'options' => [
                    '😣 Not at all',
                    '😕 Rarely',
                    '😊 Sometimes',
                    '😍 Most of the time',
                    '🤔 Not sure',
                ],
            ],
            [
                'text' => 'What is your primary source of emotional support?',
                'order' => 27,
                'options' => [
                    '👨‍👩‍👧 Family',
                    '💬 Friends',
                    '🧑‍⚕️ Therapy or professional help',
                    '🧘 Self-care practices',
                    '🤷 I don’t have a primary source of support',
                ],
            ],
            [
                'text' => 'How do you usually respond to criticism?',
                'order' => 28,
                'options' => [
                    '😔 I take it personally and feel hurt',
                    '🤔 I analyze it to see if it’s valid',
                    '😶 I ignore it and move on',
                    '💬 I discuss it to understand more',
                    '😊 I appreciate it as constructive feedback',
                ],
            ],
            [
                'text' => 'How often do you prioritize your mental health over other responsibilities?',
                'order' => 29,
                'options' => [
                    'Never',
                    'Rarely',
                    'Sometimes',
                    'Often',
                    'Always',
                ],
            ],
            [
                'text' => 'What would you describe as your biggest emotional challenge right now?',
                'order' => 30,
                'options' => [
                    '😟 Managing stress',
                    '💔 Navigating relationship difficulties',
                    '😔 Dealing with self-doubt',
                    '🤷 Identifying my emotions',
                    '😊 Other',
                ],
            ],
        ];

        foreach ($questions as $q) {
            $question = Question::create([
                'text' => $q['text'],
                'order' => $q['order'],
            ]);

            foreach ($q['options'] as $optionText) {
                Option::create([
                    'question_id' => $question->id,
                    'text' => $optionText,
                ]);
            }
        }
    }
}
