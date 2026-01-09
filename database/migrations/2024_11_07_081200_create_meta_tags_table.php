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
        Schema::create('meta_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->unique('route_id');
            $table->text('description');
            $table->string('keywords');
            $table->string('og_title');
            $table->text('og_description');
            $table->string('og_image');
            $table->string('og_url');
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_title');
            $table->text('twitter_description');
            $table->string('twitter_image');
            $table->string('twitter_site');
            $table->string('twitter_creator')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_tags');
    }
};
