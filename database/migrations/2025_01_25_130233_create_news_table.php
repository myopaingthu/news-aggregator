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
            $table->id();
            $table->foreignId('source_id')->constrained('sources')->onDelete('cascade')->nullable();
            $table->foreignId('data_source_id')->constrained('data_sources')->onDelete('cascade');
            $table->string('author')->nullable()->index();
            $table->string('title')->index();
            $table->string('category', 50)->nullable()->index();
            $table->string('slug')->index();
            $table->text('description')->nullable()->index();
            $table->longText('content')->nullable()->index();
            $table->string('url')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
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
