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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');                                               // اسم المجموعة
            $table->foreignId('course_id')->constrained()->onDelete('cascade');         // الكورس الذي تنتمي إليه المجموعة
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // المدرب المسؤول
            $table->integer('max_seats')->nullable()->default(25);                      // الحد الأقصى لعدد المقاعد
            $table->integer('available_seats')->nullable()->default(25);                // الحد الحالي لعدد المقاعد
            $table->tinyInteger('status')->default(1)->nullable();
            $table->enum('session_status', ['scheduled', 'completed', 'cancelled']); // حالة الجلسة
            $table->enum('group_type', ['individual', 'group']);                     // حالة المجموعة (فردي/جماعي)
            $table->integer('duration')->nullable();                              // عدد الساعات
            $table->integer('hours_count')->nullable();                              // عدد الساعات
            $table->integer('number_lessons')->nullable();                           //get in course Id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
