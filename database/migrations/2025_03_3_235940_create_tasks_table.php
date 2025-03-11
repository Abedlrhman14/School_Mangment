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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // for teacher
            $table->foreignId('subject_id')->constrained('subject_id')->onDelete('cascade'); // for subject
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade'); // for class
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();  //for files
            $table->string('subject_id')->nullable();
            // $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
