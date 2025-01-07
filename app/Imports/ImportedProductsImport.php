<?php

namespace App\Imports;

use App\Models\ImportedProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportedProductsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function model(array $row)
    {
        if (empty($row['id']) || empty($row['name'])) {
            \Log::warning('Skipping row due to missing required fields:', $row);
            return null;
        }

        return new ImportedProduct([
            'product_id' => $row['id'] ?? null,
            'type' => $row['type'] ?? null,
            'sku' => $row['sku'] ?? null,
            'name' => $row['name'] ?? null,
            'published' => ($row['published'] ?? '0') == '1',
            'is_featured' => ($row['is_featured'] ?? '0') == '1',
            'visibility' => $row['visibility_in_catalog'] ?? null,
            'short_description' => $row['short_description'] ?? null,
            'description' => $row['description'] ?? null,
            'date_sale_price_starts' => $row['date_sale_price_starts'] ?? null,
            'date_sale_price_ends' => $row['date_sale_price_ends'] ?? null,
            'tax_status' => $row['tax_status'] ?? null,
            'tax_class' => $row['tax_class'] ?? null,
            'in_stock' => ($row['in_stock'] ?? '0') == '1',
            'stock' => $row['stock'] ?? null,
            'low_stock_amount' => $row['low_stock_amount'] ?? null,
            'backorders_allowed' => ($row['backorders_allowed'] ?? '0') == '1',
            'sold_individually' => ($row['sold_individually'] ?? '0') == '1',
            'weight_kg' => $row['weight_kg'] ?? null,
            'length_cm' => $row['length_cm'] ?? null,
            'width_cm' => $row['width_cm'] ?? null,
            'height_cm' => $row['height_cm'] ?? null,
            'allow_customer_reviews' => ($row['allow_customer_reviews'] ?? '0') == '1',
            'purchase_note' => $row['purchase_note'] ?? null,
            'sale_price' => $row['sale_price'] ?? null,
            'regular_price' => $row['regular_price'] ?? null,
            'categories' => $row['categories'] ?? null,
            'tags' => $row['tags'] ?? null,
            'shipping_class' => $row['shipping_class'] ?? null,
            'images' => $row['images'] ?? null,
            'download_limit' => $row['download_limit'] ?? null,
            'download_expiry_days' => $row['download_expiry_days'] ?? null,
            'parent' => $row['parent'] ?? null,
            'grouped_products' => $row['grouped_products'] ?? null,
            'upsells' => $row['upsells'] ?? null,
            'cross_sells' => $row['cross_sells'] ?? null,
            'external_url' => $row['external_url'] ?? null,
            'button_text' => $row['button_text'] ?? null,
            'position' => $row['position'] ?? null,
            'woo_variation_gallery_images' => $row['woo_variation_gallery_images'] ?? null,
            'meta_wpml_word_count' => $row['meta_wpml_word_count'] ?? null,
            'meta_wpml_media_featured' => $row['meta_wpml_media_featured'] ?? null,
            'meta_wpml_media_duplicate' => $row['meta_wpml_media_duplicate'] ?? null,
            'meta_rs_page_bg_color' => $row['meta_rs_page_bg_color'] ?? null,
            'meta_wpml_media_has_media' => $row['meta_wpml_media_has_media'] ?? null,
            'meta_site_sidebar_layout' => $row['meta_site_sidebar_layout'] ?? null,
            'meta_site_content_layout' => $row['meta_site_content_layout'] ?? null,
            'meta_theme_transparent_header_meta' => $row['meta_theme_transparent_header_meta'] ?? null,
            'meta_stick_header_meta' => $row['meta_stick_header_meta'] ?? null,
            'meta_yoast_wpseo_primary_product_cat' => $row['meta_yoast_wpseo_primary_product_cat'] ?? null,
            'meta_yoast_wpseo_focuskw' => $row['meta_yoast_wpseo_focuskw'] ?? null,
            'meta_yoast_wpseo_metadesc' => $row['meta_yoast_wpseo_metadesc'] ?? null,
            'meta_yoast_wpseo_linkdex' => $row['meta_yoast_wpseo_linkdex'] ?? null,
            'meta_yoast_wpseo_content_score' => $row['meta_yoast_wpseo_content_score'] ?? null,
            'meta_yoast_wpseo_estimated_reading_time_minutes' => $row['meta_yoast_wpseo_estimated_reading_time_minutes'] ?? null,
            'meta_wpspro_recent_view_time' => $row['meta_wpspro_recent_view_time'] ?? null,
            'meta_sp_wpsp_product_view_count' => $row['meta_sp_wpsp_product_view_count'] ?? null,
            'meta_last_editor_used_jetpack' => $row['meta_last_editor_used_jetpack'] ?? null,
            'meta_nickx_video_text_url' => $row['meta_nickx_video_text_url'] ?? null,
            'meta_nickx_product_video_type' => $row['meta_nickx_product_video_type'] ?? null,
            'meta_custom_thumbnail' => $row['meta_custom_thumbnail'] ?? null,
            'meta_wcml_average_rating' => $row['meta_wcml_average_rating'] ?? null,
            'meta_wcml_review_count' => $row['meta_wcml_review_count'] ?? null,
            'meta_top_nav_excluded' => $row['meta_top_nav_excluded'] ?? null,
            'meta_cms_nav_minihome' => $row['meta_cms_nav_minihome'] ?? null,
            'meta_last_translation_edit_mode' => $row['meta_last_translation_edit_mode'] ?? null,
            'meta_wp_attachment_metadata' => $row['meta_wp_attachment_metadata'] ?? null,
            'attribute_1_name' => $row['attribute_1_name'] ?? null,
            'attribute_1_value' => $row['attribute_1_value'] ?? null,
            'attribute_1_visible' => ($row['attribute_1_visible'] ?? '0') == '1',
            'attribute_1_global' => ($row['attribute_1_global'] ?? '0') == '1',
            'meta_yoast_wpseo_wordproof_timestamp' => $row['meta_yoast_wpseo_wordproof_timestamp'] ?? null,
            'meta_wp_old_date' => $row['meta_wp_old_date'] ?? null,
            'meta_ast_site_content_layout' => $row['meta_ast_site_content_layout'] ?? null,
            'meta_site_content_style' => $row['meta_site_content_style'] ?? null,
            'meta_site_sidebar_style' => $row['meta_site_sidebar_style'] ?? null,
            'meta_astra_migrate_meta_layouts' => $row['meta_astra_migrate_meta_layouts'] ?? null,
            'meta_mstore_video_url' => $row['meta_mstore_video_url'] ?? null,
            'meta_mstore_video_title' => $row['meta_mstore_video_title'] ?? null,
            'meta_mstore_video_description' => $row['meta_mstore_video_description'] ?? null,
            'attribute_2_name' => $row['attribute_2_name'] ?? null,
            'attribute_2_value' => $row['attribute_2_value'] ?? null,
            'attribute_2_visible' => ($row['attribute_2_visible'] ?? '0') == '1',
            'attribute_2_global' => ($row['attribute_2_global'] ?? '0') == '1',
            'attribute_3_name' => $row['attribute_3_name'] ?? null,
            'attribute_3_value' => $row['attribute_3_value'] ?? null,
            'attribute_3_visible' => ($row['attribute_3_visible'] ?? '0') == '1',
            'attribute_3_global' => ($row['attribute_3_global'] ?? '0') == '1',
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}