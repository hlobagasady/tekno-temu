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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); $table->string('name'); $table->string('brand')->default('');
            $table->unsignedBigInteger('price_idr'); $table->unsignedInteger('sold_count')->default(0);
            $table->string('category'); $table->string('collection_name')->default(''); $table->string('spec')->default('');
            $table->text('image_url')->nullable(); $table->text('affiliate_url'); $table->boolean('is_top')->default(false);
            $table->enum('status', ['draft','published','archived'])->default('published'); $table->index(['status','category']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
