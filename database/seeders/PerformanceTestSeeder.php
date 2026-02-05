<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Question;
use App\Models\Progress;
use Carbon\Carbon;

class PerformanceTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Starting Performance Test Seeding...');

        // 1. Create 50 Dummy Students
        $this->command->info('Creating 50 Dummy Students...');
        $students = User::factory()->count(50)->create([
            'role_id' => 3, // Peran Mahasiswa
            'password' => Hash::make('password'), // Password default
        ]);

        // 2. Get All Questions
        $questions = Question::all();
        
        if ($questions->isEmpty()) {
            $this->command->error('No questions found! Please seed questions first.');
            return;
        }

        $progressData = [];
        $now = Carbon::now();

        // 3. Generate Progress Data for Each Student
        $this->command->info('Generating Progress Data...');
        
        foreach ($students as $student) {
            // Randomly answer 20% to 90% of available questions
            $questionsToAnswer = $questions->random((int)($questions->count() * rand(20, 90) / 100));

            foreach ($questionsToAnswer as $question) {
                $isCorrect = rand(0, 100) < 80; // 80% chance being correct eventually
                $attempts = rand(1, 5); // 1-5 attempts

                if ($isCorrect) {
                     // If correct, insert the success record
                     $progressData[] = [
                        'user_id' => $student->id,
                        'question_id' => $question->id,
                        'material_id' => $question->material_id, // Assuming question has material_id
                        'is_correct' => 1,
                        'is_answered' => 1,
                        'attempt_number' => $attempts,
                        // 'answer_id' => null, // Removed: Column does not exist
                        'created_at' => Carbon::now()->subDays(rand(0, 30))->toDateTimeString(),
                        'updated_at' => Carbon::now()->subDays(rand(0, 30))->toDateTimeString(),
                    ];
                } else {
                    // Only incorrect attempts (abandoned)
                     $progressData[] = [
                        'user_id' => $student->id,
                        'question_id' => $question->id,
                        'material_id' => $question->material_id,
                        'is_correct' => 0,
                        'is_answered' => 1,
                        'attempt_number' => $attempts,
                        // 'answer_id' => null,
                        'created_at' => Carbon::now()->subDays(rand(0, 30))->toDateTimeString(),
                        'updated_at' => Carbon::now()->subDays(rand(0, 30))->toDateTimeString(),
                    ];
                }
            }
        }

        // 4. Batch Insert for Speed
        $this->command->info('Inserting ' . count($progressData) . ' progress records...');
        
        // Chunk inserts to avoid memory limits
        foreach (array_chunk($progressData, 500) as $chunk) {
            Progress::insert($chunk);
        }

        $this->command->info('Performance Test Data Seeded Successfully!');
    }
}
