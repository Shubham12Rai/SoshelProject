<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('language')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('language')->insert([
            ['name' => 'Afrikaans', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Albanian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Amharic', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Arabic', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Armenian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bengali', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bulgarian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bosnian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chinese', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Czech', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Croatian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Danish', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dutch', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'English', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Farsi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finnish', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'French', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'German', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Greek', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hindi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hebrew', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hungarian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ibo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Icelandic', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Indonesian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Italian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Japanese', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Korean', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kazakh', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Latvian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lithuanian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Marathi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Malay', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nepali', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Norweigan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Polish', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Portugese', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Romanian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Russian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Serbian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Slovak', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Slovenian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Somali', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Spanish', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Swahili', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Swedish', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tagalog', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tamil', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Thai', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ukranian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Urdu', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Uzbek', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vietnamese', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
