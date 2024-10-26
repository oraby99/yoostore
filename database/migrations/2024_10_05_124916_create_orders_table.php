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
            $table->foreignId('address_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('invoice_id')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->foreignId('order_status_id')->constrained()->onDelete('cascade'); // Refers to dynamic statuses
            $table->string('payment_method')->default('gateway');
            $table->foreignId('payment_status_id')->constrained()->onDelete('cascade'); // Refers to dynamic payment statuses
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
