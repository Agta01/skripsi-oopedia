<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Question;
use App\Models\User;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Starting Question Seeding...');

        // Ensure we have an admin user for 'created_by'
        $admin = User::first() ?? User::factory()->create(['role_id' => 1]);

        // 1. Create Materials
        $materials = [
            ['title' => 'Pengenalan Java', 'content' => 'Dasar-dasar bahasa pemrograman Java'],
            ['title' => 'Konsep OOP', 'content' => 'Object Oriented Programming'],
            ['title' => 'Struktur Data', 'content' => 'Array, List, dan Map'],
        ];

        foreach ($materials as $matData) {
            $material = Material::firstOrCreate(
                ['title' => $matData['title']], 
                ['content' => $matData['content'], 'created_by' => $admin->id]
            );
            
            $this->command->info("Seeded Material: {$material->title}");

            // 2. Create Questions for each Material
            $difficulties = ['beginner', 'medium', 'hard'];
            
            for ($i = 1; $i <= 10; $i++) {
                Question::create([
                    'material_id' => $material->id,
                    'question_text' => "Contoh Soal {$i} untuk materi {$material->title}?",
                    'difficulty' => $difficulties[array_rand($difficulties)],
                    'question_type' => 'fill_in_the_blank', // Updated to match ENUM definition
                    'created_by' => $admin->id
                ]);
            }
        }

        $this->command->info('Questions Seeded Successfully!');
    }
}
