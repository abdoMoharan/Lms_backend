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
        Schema::create('meeting_zooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_session_id')->nullable()->constrained('group_sessions')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('zoom_id')->nullable();    // لتخزين Zoom Meeting ID
            $table->string('host_id')->nullable();    // لتخزين ID المضيف
            $table->string('host_email')->nullable(); // لتخزين البريد الإلكتروني للمضيف
            $table->string('topic')->nullable();      // لتخزين عنوان الاجتماع
            $table->string('start_time')->nullable(); // لتخزين وقت بداية الاجتماع
            $table->integer('duration')->nullable();  // لتخزين مدة الاجتماع (بالدقائق)
            $table->string('timezone')->nullable();   // لتخزين المنطقة الزمنية
            $table->longText('start_url')->nullable();  // لتخزين رابط بدء الاجتماع
            $table->longText('join_url')->nullable();   // لتخزين رابط الانضمام للاجتماع
            $table->string('password')->nullable();   // لتخزين كلمة المرور الخاصة بالاجتماع
  $table->tinyInteger('is_meeting_created')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_zooms');
    }
};
