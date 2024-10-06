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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained()->onDelete('cascade')->nullable();
            $table->string('invoice_id')->nullable(); // Nullable for COD orders
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('Pending'); // Order status: Pending, Delivered, Cancelled, etc.
            $table->string('payment_method')->default('gateway'); // Payment method: gateway or cod
            $table->string('payment_status')->default('Pending'); // Payment status: Pending, Paid, Cancelled
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
