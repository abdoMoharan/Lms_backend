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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('qualification')->nullable();       // المؤهل العلمي
            $table->string('certificate_name')->nullable();    // الشهادات المهنية
            $table->date('certificate_date')->nullable();      // تاريخ الشهادة المهنية
            $table->string('experience')->nullable();          // الخبرة العملية
            $table->string('id_card_number')->nullable();      // رقم الهوية الشخصية
            $table->string('id_card_image_front')->nullable(); // صورة من الهوية
            $table->string('id_card_image_back')->nullable();  // صورة من الهوية
            $table->string('birthdate')->nullable();           // تاريخ الميلاد
            $table->string('nationality')->nullable();         // الجنسية
            $table->string('address')->nullable();             // العنوان
            $table->string('degree')->nullable();
            $table->string('cv')->nullable();
            $table->string('intro_video')->nullable();
            $table->longText('bio')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
