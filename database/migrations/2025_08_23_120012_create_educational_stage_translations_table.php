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
        Schema::create('educational_stage_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stage_id');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->unique(['stage_id', 'locale']);
            $table->foreign('stage_id')->references('id')->on('educational_stages')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_stage_translations');
    }
};
