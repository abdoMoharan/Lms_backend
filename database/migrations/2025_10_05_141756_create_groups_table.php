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
            $table->string('group_name');                                                  // اسم المجموعة
            $table->foreignId('course_id')->constrained()->onDelete('cascade');            // الكورس الذي تنتمي إليه المجموعة
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade'); // المدرب المسؤول
            $table->integer('max_seats')->nullable()->default(25);                         // الحد الأقصى لعدد المقاعد
            $table->tinyInteger('status')->default(1);
            $table->dateTime('start_time');                                          // وقت بدء الجلسة
            $table->dateTime('end_time');                                            // وقت انتهاء الجلسة
            $table->integer('capacity');                                             // عدد المقاعد المتاحة في الجلسة
            $table->enum('session_status', ['scheduled', 'completed', 'cancelled']); // حالة الجلسة         // حالة المجموعة
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
