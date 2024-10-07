<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_detail_id')->nullable()->after('product_id'); // Add the new column
            $table->foreign('product_detail_id')->references('id')->on('product_details')->onDelete('set null'); // Add the foreign key constraint
        });
    }
    
    public function down()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropForeign(['product_detail_id']); // Drop the foreign key first
            $table->dropColumn('product_detail_id');    // Then drop the column
        });
    }
    
};
