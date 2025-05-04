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
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('author_id')->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->boolean('active')->default(true);
            $table->text('content');
            $table->string('image')->nullable();
            $table->dateTimeTz('published_at');
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
