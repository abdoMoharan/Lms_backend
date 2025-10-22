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
        Schema::create('group_days', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_time')->nullable(); // وقت بدء الجلسة
            $table->enum('session_time', ['pm', 'am'])->nullable();
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
            $table->foreignId('week_id')->nullable()->constrained('weeks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_days');
    }
};
