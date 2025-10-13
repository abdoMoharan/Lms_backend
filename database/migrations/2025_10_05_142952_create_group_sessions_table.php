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
 Schema::create('group_sessions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');      // ربط الجلسة بالمجموعة
        // $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');        // ربط الجلسة بالغرفة
        $table->dateTime('start_time');                                                  // وقت بدء الجلسة
        $table->dateTime('end_time');                                                    // وقت انتهاء الجلسة
        $table->integer('capacity');                                                     // عدد المقاعد المتاحة في الجلسة
        $table->enum('session_status', ['scheduled', 'completed', 'cancelled']);         // حالة الجلسة
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_sessions');
    }
};
