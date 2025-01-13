<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountDeliverytimeDescriptionArTitleArToImportedProductsTable extends Migration
{
    public function up()
    {
        Schema::table('imported_products', function (Blueprint $table) {
            $table->decimal('discount', 8, 2)->nullable()->after('regular_price');
            $table->string('delivery_time')->nullable()->after('discount');
            $table->text('description_ar')->nullable()->after('description');
            $table->string('title_ar')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('imported_products', function (Blueprint $table) {
            $table->dropColumn(['discount', 'delivery_time', 'description_ar', 'title_ar']);
        });
    }
}
