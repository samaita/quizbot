<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTelegramTable extends Migration
{
    public function up()
    {
        Schema::create('users_telegram', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('telegram_chat_id')->unique();
            $table->timestamps();
            $table->softDeletesTz();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_telegram');
    }
}