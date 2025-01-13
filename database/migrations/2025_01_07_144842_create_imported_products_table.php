<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportedProductsTable extends Migration

{
    public function up()
    {
        Schema::create('imported_products', function (Blueprint $table) {
            
            $table->id();
            $table->string('product_id')->nullable();
            $table->string('type')->nullable();
            $table->string('sku')->nullable();
            $table->string('name')->nullable();
            $table->boolean('published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('visibility')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->date('date_sale_price_starts')->nullable();
            $table->date('date_sale_price_ends')->nullable();
            $table->string('tax_status')->nullable();
            $table->string('tax_class')->nullable();
            $table->boolean('in_stock')->default(false);
            $table->integer('stock')->nullable();
            $table->integer('low_stock_amount')->nullable();
            $table->boolean('backorders_allowed')->default(false);
            $table->boolean('sold_individually')->default(false);
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->decimal('length_cm', 8, 2)->nullable();
            $table->decimal('width_cm', 8, 2)->nullable();
            $table->decimal('height_cm', 8, 2)->nullable();
            $table->boolean('allow_customer_reviews')->default(false);
            $table->text('purchase_note')->nullable();
            $table->decimal('sale_price', 8, 2)->nullable();
            $table->decimal('regular_price', 8, 2)->nullable();
            $table->text('categories')->nullable();
            $table->text('tags')->nullable();
            $table->string('shipping_class')->nullable();
            $table->text('images')->nullable();
            $table->integer('download_limit')->nullable();
            $table->integer('download_expiry_days')->nullable();
            $table->string('parent')->nullable();
            $table->text('grouped_products')->nullable();
            $table->text('upsells')->nullable();
            $table->text('cross_sells')->nullable();
            $table->text('external_url')->nullable();
            $table->string('button_text')->nullable();
            $table->integer('position')->nullable();
            $table->text('woo_variation_gallery_images')->nullable();
            $table->text('meta_wpml_word_count')->nullable();
            $table->text('meta_wpml_media_featured')->nullable();
            $table->text('meta_wpml_media_duplicate')->nullable();
            $table->text('meta_rs_page_bg_color')->nullable();
            $table->text('meta_wpml_media_has_media')->nullable();
            $table->text('meta_site_sidebar_layout')->nullable();
            $table->text('meta_site_content_layout')->nullable();
            $table->text('meta_theme_transparent_header_meta')->nullable();
            $table->text('meta_stick_header_meta')->nullable();
            $table->text('meta_yoast_wpseo_primary_product_cat')->nullable();
            $table->text('meta_yoast_wpseo_focuskw')->nullable();
            $table->text('meta_yoast_wpseo_metadesc')->nullable();
            $table->text('meta_yoast_wpseo_linkdex')->nullable();
            $table->text('meta_yoast_wpseo_content_score')->nullable();
            $table->text('meta_yoast_wpseo_estimated_reading_time_minutes')->nullable();
            $table->text('meta_wpspro_recent_view_time')->nullable();
            $table->text('meta_sp_wpsp_product_view_count')->nullable();
            $table->text('meta_last_editor_used_jetpack')->nullable();
            $table->text('meta_nickx_video_text_url')->nullable();
            $table->text('meta_nickx_product_video_type')->nullable();
            $table->text('meta_custom_thumbnail')->nullable();
            $table->text('meta_wcml_average_rating')->nullable();
            $table->text('meta_wcml_review_count')->nullable();
            $table->text('meta_top_nav_excluded')->nullable();
            $table->text('meta_cms_nav_minihome')->nullable();
            $table->text('meta_last_translation_edit_mode')->nullable();
            $table->text('meta_wp_attachment_metadata')->nullable();
            $table->text('attribute_1_name')->nullable();
            $table->text('attribute_1_value')->nullable();
            $table->boolean('attribute_1_visible')->default(false);
            $table->boolean('attribute_1_global')->default(false);
            $table->text('meta_yoast_wpseo_wordproof_timestamp')->nullable();
            $table->text('meta_wp_old_date')->nullable();
            $table->text('meta_ast_site_content_layout')->nullable();
            $table->text('meta_site_content_style')->nullable();
            $table->text('meta_site_sidebar_style')->nullable();
            $table->text('meta_astra_migrate_meta_layouts')->nullable();
            $table->text('meta_mstore_video_url')->nullable();
            $table->text('meta_mstore_video_title')->nullable();
            $table->text('meta_mstore_video_description')->nullable();
            $table->text('attribute_2_name')->nullable();
            $table->text('attribute_2_value')->nullable();
            $table->boolean('attribute_2_visible')->default(false);
            $table->boolean('attribute_2_global')->default(false);
            $table->text('attribute_3_name')->nullable();
            $table->text('attribute_3_value')->nullable();
            $table->boolean('attribute_3_visible')->default(false);
            $table->boolean('attribute_3_global')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('imported_products');
    }
}