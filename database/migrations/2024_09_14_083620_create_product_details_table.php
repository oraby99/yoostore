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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('price')->nullable();
            $table->string('image')->nullable();
            $table->string('color')->nullable();
            $table->json('size')->nullable();
            $table->integer('stock')->nullable();

            $table->integer('typeprice')->nullable();
            $table->string('typeimage')->nullable();
            $table->string('typename')->nullable();
            $table->integer('typestock')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
