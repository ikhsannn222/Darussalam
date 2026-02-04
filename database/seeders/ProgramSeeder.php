<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('programs')->insert([
            [
                'name' => 'TK',
                'description' => 'Program Taman Kanak-Kanak yang berfokus pada pendidikan dasar anak usia dini dengan pendekatan islami, pembentukan karakter, serta pengenalan nilai-nilai moral dan sosial.',
                'logo' => 'program-logos/tk.png',
                'is_open_registration' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'TPA',
                'description' => 'Program Taman Pendidikan Al-Qur’an yang bertujuan membimbing anak-anak dalam membaca Al-Qur’an, memahami dasar-dasar ibadah, dan menanamkan kecintaan terhadap Al-Qur’an.',
                'logo' => 'program-logos/tpa.png',
                'is_open_registration' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'TQA',
                'description' => 'Program lanjutan pendidikan Al-Qur’an yang menekankan pada kelancaran bacaan, tajwid, serta pemahaman dasar terhadap isi Al-Qur’an.',
                'logo' => 'program-logos/tqa.png',
                'is_open_registration' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Tahfidz',
                'description' => 'Program Tahfidz Al-Qur’an yang dirancang untuk membantu santri menghafal Al-Qur’an secara bertahap dengan bimbingan yang terstruktur dan berkelanjutan.',
                'logo' => 'program-logos/tahfidz.png',
                'is_open_registration' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
