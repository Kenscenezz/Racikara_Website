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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->longText('ingredients')->nullable();
            $table->longText('steps')->nullable();
            $table->integer('cooking_time')->default(30)->comment('in minutes');
            $table->enum('difficulty', ['Mudah', 'Sedang', 'Sulit'])->default('Mudah');
            $table->integer('portion')->default(2);
            $table->integer('calories')->default(0);
            $table->string('image')->default('placeholder-food.jpg');
            $table->decimal('rating', 3, 1)->default(4.8);
            $table->integer('review_count')->default(0);
            $table->string('tags', 500)->nullable();
            $table->integer('views')->default(0);
            $table->enum('status', ['published', 'draft'])->default('published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
