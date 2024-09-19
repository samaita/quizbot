<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed languages
        $englishId = DB::table('languages')->firstOrCreate([
            'code' => 'en',
            'name' => 'English',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed question types
        $multipleChoiceId = DB::table('question_types')->firstOrCreate([
            'name' => 'Basic Multiple Choice',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed questions
        $questions = [
            [
                'question' => 'What is the most populated island in Indonesia?',
                'answer' => 'D',
                'additional_data' => json_encode([
                    'choices' => [
                        'A' => 'Sumatra',
                        'B' => 'Papua',
                        'C' => 'Kalimantan',
                        'D' => 'Jawa',
                    ],
                    'explanation' => 'Jawa is the most populated island in Indonesia, with approximately 55% of the country\'s population living there.'
                ]),
            ],
            [
                'question' => 'What is the name of the first Indonesian President?',
                'answer' => 'C',
                'additional_data' => json_encode([
                    'choices' => [
                        'A' => 'BJ Habibie',
                        'B' => 'Megawati',
                        'C' => 'Soekarno',
                        'D' => 'Joko Widodo',
                    ],
                    'explanation' => 'Soekarno was the first president and one of the founding fathers of Indonesia.'
                ]),
            ],
            [
                'question' => 'Which of these is Indonesia\'s national animal?',
                'answer' => 'B',
                'additional_data' => json_encode([
                    'choices' => [
                        'A' => 'Orangutan',
                        'B' => 'Komodo Dragon',
                        'C' => 'Sumatran Tiger',
                        'D' => 'Javan Rhinoceros',
                    ],
                    'explanation' => 'The Komodo Dragon, native to Indonesia, is the country\'s national animal.'
                ]),
            ],
            [
                'question' => 'What is the capital city of Indonesia?',
                'answer' => 'A',
                'additional_data' => json_encode([
                    'choices' => [
                        'A' => 'Jakarta',
                        'B' => 'Surabaya',
                        'C' => 'Bandung',
                        'D' => 'Yogyakarta',
                    ],
                    'explanation' => 'Jakarta is the current capital of Indonesia, although there are plans to move the capital to East Kalimantan in the future.'
                ]),
            ],
            [
                'question' => 'Which famous temple complex is located in Central Java, Indonesia?',
                'answer' => 'C',
                'additional_data' => json_encode([
                    'choices' => [
                        'A' => 'Angkor Wat',
                        'B' => 'Pura Besakih',
                        'C' => 'Borobudur',
                        'D' => 'Prambanan',
                    ],
                    'explanation' => 'Borobudur is a 9th-century Mahayana Buddhist temple located in Central Java, Indonesia. It\'s the world\'s largest Buddhist temple.'
                ]),
            ],
        ];

        foreach ($questions as $question) {
            DB::table('questions')->insert([
                'question_type_id' => $multipleChoiceId,
                'language_id' => $englishId,
                'question' => $question['question'],
                'answer' => $question['answer'],
                'additional_data' => $question['additional_data'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}