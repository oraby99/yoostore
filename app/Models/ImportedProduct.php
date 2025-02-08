<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'sku',
        'name',
        'title_ar', 
        'published',
        'is_featured',
        'visibility',
        'short_description',
        'description',
        'description_ar', 
        'date_sale_price_starts',
        'date_sale_price_ends',
        'tax_status',
        'tax_class',
        'in_stock',
        'stock',
        'low_stock_amount',
        'backorders_allowed',
        'sold_individually',
        'weight_kg',
        'length_cm',
        'width_cm',
        'height_cm',
        'allow_customer_reviews',
        'purchase_note',
        'sale_price',
        'regular_price',
        'discount', 
        'delivery_time', 
        'categories',
        'tags',
        'shipping_class',
        'images',
        'download_limit',
        'download_expiry_days',
        'parent',
        'grouped_products',
        'upsells',
        'cross_sells',
        'external_url',
        'button_text',
        'position',
        'woo_variation_gallery_images',
        'meta_wpml_word_count',
        'meta_wpml_media_featured',
        'meta_wpml_media_duplicate',
        'meta_rs_page_bg_color',
        'meta_wpml_media_has_media',
        'meta_site_sidebar_layout',
        'meta_site_content_layout',
        'meta_theme_transparent_header_meta',
        'meta_stick_header_meta',
        'meta_yoast_wpseo_primary_product_cat',
        'meta_yoast_wpseo_focuskw',
        'meta_yoast_wpseo_metadesc',
        'meta_yoast_wpseo_linkdex',
        'meta_yoast_wpseo_content_score',
        'meta_yoast_wpseo_estimated_reading_time_minutes',
        'meta_wpspro_recent_view_time',
        'meta_sp_wpsp_product_view_count',
        'meta_last_editor_used_jetpack',
        'meta_nickx_video_text_url',
        'meta_nickx_product_video_type',
        'meta_custom_thumbnail',
        'meta_wcml_average_rating',
        'meta_wcml_review_count',
        'meta_top_nav_excluded',
        'meta_cms_nav_minihome',
        'meta_last_translation_edit_mode',
        'meta_wp_attachment_metadata',
        'attribute_1_name',
        'attribute_1_value',
        'attribute_1_visible',
        'attribute_1_global',
        'meta_yoast_wpseo_wordproof_timestamp',
        'meta_wp_old_date',
        'meta_ast_site_content_layout',
        'meta_site_content_style',
        'meta_site_sidebar_style',
        'meta_astra_migrate_meta_layouts',
        'meta_mstore_video_url',
        'meta_mstore_video_title',
        'meta_mstore_video_description',
        'attribute_2_name',
        'attribute_2_value',
        'attribute_2_visible',
        'attribute_2_global',
        'attribute_3_name',
        'attribute_3_value',
        'attribute_3_visible',
        'attribute_3_global',
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'product_id');
    }
    public function childproduct()
    {
        return $this->hasMany(ImportedProduct::class, 'parent', 'sku');
    }
    public function rates()
    {
        return $this->hasMany(Rate::class, 'product_id');
    }
    public function orders()
    {
        return $this->hasMany(OrderProduct::class);
    }
    protected $casts = [
        'images' => 'array',
    ];
    public function getImagesAttribute($value)
    {
        return $value ? array_map('trim', explode(',', $value)) : [];
    }
    public function setImagesAttribute($value)
    {
        if (is_array($value)) {
            $urls = array_column($value, 'url');
            $this->attributes['images'] = implode(',', $urls);
        } else {
            $this->attributes['images'] = $value;
        }
    }
    public function getAvgRateAttribute()
    {
        if ($this->rates->isEmpty()) {
            return 0; // Return 0 if there are no rates
        }

        return $this->rates->avg('rate');
    }
}