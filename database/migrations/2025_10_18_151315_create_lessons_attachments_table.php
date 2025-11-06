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
        Schema::create('lessons_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('group_session_id')->nullable()->constrained('group_sessions')->cascadeOnDelete();
            $table->string('video_upload')->nullable();
            $table->string('file')->nullable();
            $table->enum('type', ['upload_video', 'youtube_link', 'vimeo_link'])->nullable();
            $table->string('link')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons_attachments');
    }
};
