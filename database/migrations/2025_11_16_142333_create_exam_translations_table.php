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
        Schema::create('exam_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->string('slug')->nullable();
            $table->unique(['exam_id', 'locale']);
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_translations');
    }
};
