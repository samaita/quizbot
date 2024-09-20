<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_telegram_id');
            $table->unsignedBigInteger('question_id');
            $table->string('correct_answer');
            $table->string('user_answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->boolean('is_answered')->default(false);
            $table->timestamps();

            $table->foreign('user_telegram_id')->references('id')->on('users_telegram')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_logs');
    }
};