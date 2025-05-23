<?php

namespace App\Http\Resources;

use App\Models\Favorite;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChildProductResource extends JsonResource
{

    protected $userId;

    public function __construct($resource, $userId = null)
    {
        parent::__construct($resource);
        $this->userId = $userId;
    }
    public function toArray($request)
    {
        $isFav = 0;
        if ($this->userId) {
            $isFav = Favorite::where('product_id', $this->id)
                             ->where('user_id', $this->userId)
                             ->exists() ? 1 : 0;
        }
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'type' => $this->type,
            'sku' => $this->sku,
            'name' => $this->name,
            'title_ar' => $this->title_ar,
            'published' => $this->published,
            'is_featured' => $this->is_featured,
            'is_fav' => $isFav,
            'visibility' => $this->visibility,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'date_sale_price_starts' => $this->date_sale_price_starts,
            'date_sale_price_ends' => $this->date_sale_price_ends,
            'tax_status' => $this->tax_status,
            'tax_class' => $this->tax_class,
            'in_stock' => $this->in_stock,
            'stock' => $this->stock,
            'low_stock_amount' => $this->low_stock_amount,
            'backorders_allowed' => $this->backorders_allowed,
            'sold_individually' => $this->sold_individually,
            'weight_kg' => $this->weight_kg,
            'length_cm' => $this->length_cm,
            'width_cm' => $this->width_cm,
            'height_cm' => $this->height_cm,
            'allow_customer_reviews' => $this->allow_customer_reviews,
            'purchase_note' => $this->purchase_note,
            'sale_price' => $this->sale_price,
            'regular_price' => $this->regular_price,
            'discount' => $this->discount,
            'delivery_time' => $this->delivery_time,
            'categories' => $this->categories,
            'tags' => $this->tags,
            'shipping_class' => $this->shipping_class,
            'images' => $this->images,
            'download_limit' => $this->download_limit,
            'download_expiry_days' => $this->download_expiry_days,
            'parent' => $this->parent,
            'grouped_products' => $this->grouped_products,
            'upsells' => $this->upsells,
            'cross_sells' => $this->cross_sells,
            'external_url' => $this->external_url,
            'button_text' => $this->button_text,
            'position' => $this->position,
            'woo_variation_gallery_images' => $this->woo_variation_gallery_images,
            'meta_wpml_word_count' => $this->meta_wpml_word_count,
            'meta_wpml_media_featured' => $this->meta_wpml_media_featured,
            'meta_wpml_media_duplicate' => $this->meta_wpml_media_duplicate,
            'meta_rs_page_bg_color' => $this->meta_rs_page_bg_color,
            'meta_wpml_media_has_media' => $this->meta_wpml_media_has_media,
            'meta_site_sidebar_layout' => $this->meta_site_sidebar_layout,
            'meta_site_content_layout' => $this->meta_site_content_layout,
            'meta_theme_transparent_header_meta' => $this->meta_theme_transparent_header_meta,
            'meta_stick_header_meta' => $this->meta_stick_header_meta,
            'meta_yoast_wpseo_primary_product_cat' => $this->meta_yoast_wpseo_primary_product_cat,
            'meta_yoast_wpseo_focuskw' => $this->meta_yoast_wpseo_focuskw,
            'meta_yoast_wpseo_metadesc' => $this->meta_yoast_wpseo_metadesc,
            'meta_yoast_wpseo_linkdex' => $this->meta_yoast_wpseo_linkdex,
            'meta_yoast_wpseo_content_score' => $this->meta_yoast_wpseo_content_score,
            'meta_yoast_wpseo_estimated_reading_time_minutes' => $this->meta_yoast_wpseo_estimated_reading_time_minutes,
            'meta_wpspro_recent_view_time' => $this->meta_wpspro_recent_view_time,
            'meta_sp_wpsp_product_view_count' => $this->meta_sp_wpsp_product_view_count,
            'meta_last_editor_used_jetpack' => $this->meta_last_editor_used_jetpack,
            'meta_nickx_video_text_url' => $this->meta_nickx_video_text_url,
            'meta_nickx_product_video_type' => $this->meta_nickx_product_video_type,
            'meta_custom_thumbnail' => $this->meta_custom_thumbnail,
            'meta_wcml_average_rating' => $this->meta_wcml_average_rating,
            'meta_wcml_review_count' => $this->meta_wcml_review_count,
            'meta_top_nav_excluded' => $this->meta_top_nav_excluded,
            'meta_cms_nav_minihome' => $this->meta_cms_nav_minihome,
            'meta_last_translation_edit_mode' => $this->meta_last_translation_edit_mode,
            'meta_wp_attachment_metadata' => $this->meta_wp_attachment_metadata,
            'attribute_1_name' => $this->attribute_1_name,
            'attribute_1_value' => $this->attribute_1_value,
            'attribute_1_visible' => $this->attribute_1_visible,
            'attribute_1_global' => $this->attribute_1_global,
            'meta_yoast_wpseo_wordproof_timestamp' => $this->meta_yoast_wpseo_wordproof_timestamp,
            'meta_wp_old_date' => $this->meta_wp_old_date,
            'meta_ast_site_content_layout' => $this->meta_ast_site_content_layout,
            'meta_site_content_style' => $this->meta_site_content_style,
            'meta_site_sidebar_style' => $this->meta_site_sidebar_style,
            'meta_astra_migrate_meta_layouts' => $this->meta_astra_migrate_meta_layouts,
            'meta_mstore_video_url' => $this->meta_mstore_video_url,
            'meta_mstore_video_title' => $this->meta_mstore_video_title,
            'meta_mstore_video_description' => $this->meta_mstore_video_description,
            'attribute_2_name' => $this->attribute_2_name,
            'attribute_2_value' => $this->attribute_2_value,
            'attribute_2_visible' => $this->attribute_2_visible,
            'attribute_2_global' => $this->attribute_2_global,
            'attribute_3_name' => $this->attribute_3_name,
            'attribute_3_value' => $this->attribute_3_value,
            'attribute_3_visible' => $this->attribute_3_visible,
            'attribute_3_global' => $this->attribute_3_global,
            'avg_rate' => $this->getAvgRateAttribute(),
            'rates' => $this->whenLoaded('rates', function () {
                return $this->rates->map(function ($rate) {
                    return [
                        'id' => $rate->id,
                        'rate' => $rate->rate,
                        'title' => $rate->title,
                        'description' => $rate->description,
                        'images' => $rate->images,
                        'user_id' => $rate->user_id,
                    ];
                });
            }),
        ];
    }
}