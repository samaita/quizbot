<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponsesSeeder extends Seeder
{
    public function run(): void
    {
        $responses = [
            ['key' => 'welcome_back', 'language' => 'en', 'content' => "Welcome back, :name! How can I assist you today?"],
            ['key' => 'welcome_back', 'language' => 'id', 'content' => "Selamat datang kembali, :name! Bagaimana saya bisa membantu Anda hari ini?"],
            ['key' => 'welcome_not_registered', 'language' => 'en', 'content' => "Welcome! You're not registered yet. Please use /register to sign up."],
            ['key' => 'welcome_not_registered', 'language' => 'id', 'content' => "Selamat datang! Anda belum terdaftar. Silakan gunakan /register untuk mendaftar."],
            ['key' => 'already_registered', 'language' => 'en', 'content' => "You're already registered, :name!"],
            ['key' => 'already_registered', 'language' => 'id', 'content' => "Anda sudah terdaftar, :name!"],
            ['key' => 'success_register', 'language' => 'en', 'content' => "Thank you for registering, :name! You're now all set."],
            ['key' => 'success_register', 'language' => 'id', 'content' => "Terima kasih telah mendaftar, :name! Semuanya sudah siap."],
            ['key' => 'echo', 'language' => 'en', 'content' => ":name, you said :text"],
            ['key' => 'echo', 'language' => 'id', 'content' => ":name, Anda mengatakan :text"],
            ['key' => 'please_register', 'language' => 'en', 'content' => "Please register using /register to get personalized responses."],
            ['key' => 'please_register', 'language' => 'id', 'content' => "Silakan daftar menggunakan /register untuk mendapatkan respons yang dipersonalisasi."],
        ];

        DB::table('responses')->insert($responses);
    }
}