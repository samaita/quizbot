<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('language', 5);
            $table->text('content');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            $table->softDeletesTz('deleted_at')->nullable();

            $table->unique(['key', 'language']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};