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
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            $table->softDeletesTz('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_telegram');
    }
}