<?php
namespace App\Imports;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection->skip(1) as $index => $row) {
            $keys = explode(",", trim($row[10]));
            $values = explode(",", trim($row[11]));
            $attributes = array_combine($keys, $values);
            $discount = is_numeric($row[5]) ? (int)$row[5] : null;
            $product = Product::create([
                'name' => $row[0] ?? null,
                'description' => $row[1] ?? null,
                'longdescription' => $row[2] ?? null,
                'tag' => $row[3] ?? null,
                'deliverytime' => $row[4] ?? null,
                'discount' => $discount,
                'category_id' => $row[6] ?? null,
                'sub_category_id' => $row[7] ?? null,
                'is_published' => $row[8] ?? null,
                'in_stock' => $row[9] ?? null,
                'attributes' => $attributes,
            ]);
            $this->saveProductDetails($product, $row, $index);
            $this->saveProductImages($product->id, $row[17] ?? null);
        }
    }
    private function saveProductDetails($product, $row, $index)
    {
        $size = $row[14];
        $sizeDecoded = is_string($size) ? json_decode($size, true) : $size;
        ProductDetail::create([
            'product_id' => $product->id,
            'price'      => $row[12],
            'color'      => $row[13],
            'size'       => $sizeDecoded,
            'typeprice'  => $row[15],
            'typename'   => $row[16],
        ]);
    }
    private function saveProductImages($productId, $imagePaths)
    {
        if ($imagePaths) {
            $imageUrls = explode(",", $imagePaths);
            foreach ($imageUrls as $url) {
                $trimmedUrl = trim($url);
                if (!empty($trimmedUrl)) {
                    ProductImage::create([
                        'product_id' => $productId,
                        'image_path' => $trimmedUrl,
                    ]);
                }
            }
        }
    }
}
