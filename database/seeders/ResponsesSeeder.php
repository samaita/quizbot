<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponsesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $responses = [
            ['key' => 'welcome_back', 'language' => 'en', 'content' => "Welcome back, :name! How can I assist you today?", 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'welcome_back', 'language' => 'id', 'content' => "Selamat datang kembali, :name! Bagaimana saya bisa membantu Anda hari ini?", 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'welcome_not_registered', 'language' => 'en', 'content' => "Welcome! You're not registered yet. Please use /register to sign up.", 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'welcome_not_registered', 'language' => 'id', 'content' => "Selamat datang! Anda belum terdaftar. Silakan gunakan /register untuk mendaftar.", 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'already_registered', 'language' => 'en', 'content' => "You're already registered, :name!", 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'already_registered', 'language' => 'id', 'content' => "Anda sudah terdaftar, :name!", 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'success_register', 'language' => 'en', 'content' => "Thank you for registering, :name! You're now all set.", 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'success_register', 'language' => 'id', 'content' => "Terima kasih telah mendaftar, :name! Semuanya sudah siap.", 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'echo', 'language' => 'en', 'content' => ":name, you said :text", 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'echo', 'language' => 'id', 'content' => ":name, Anda mengatakan :text", 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'please_register', 'language' => 'en', 'content' => "Please register using /register to get personalized responses.", 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'please_register', 'language' => 'id', 'content' => "Silakan daftar menggunakan /register untuk mendapatkan respons yang dipersonalisasi.", 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('responses')->insert($responses);
    }
}