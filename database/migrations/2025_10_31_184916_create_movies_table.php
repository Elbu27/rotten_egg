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
    Schema::create('movies', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('poster')->nullable();
        $table->string('trailer_url')->nullable();
        $table->boolean('is_restricted')->default(false);
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('type')->default('General');
        $table->integer('release_year')->nullable();
        $table->integer('age_rating')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
