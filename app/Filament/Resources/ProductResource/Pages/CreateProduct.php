<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductDetail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = ProductResource::class;
    protected function handleRecordCreation(array $data): Product
    {
        $product = Product::create($data);
        $images = $data['images'];
        unset($data['images']);
        if ($data['is_product_details']) {
            $this->updateProductDetails($product, $data['product_details']);
        } else {
            $this->updateTypeDetails($product, $data['type_details']);
        }
        $this->saveImages($product, $images);
        return $product;
    }
    private function updateProductDetails(Product $product, array $productDetails)
    {
        foreach ($productDetails as $item) {
            ProductDetail::create([
                'product_id' => $product->id,
                'price'      => $item['price'],
                'color'      => $item['color'] ?? null,
                'size'       => $item['size'] ?? null,
                'image'      => 'https://amhere.net/storage/' . $item['image'] ?? null,
            ]);
        }
    }
    private function updateTypeDetails(Product $product, array $typeDetails)
    {
        foreach ($typeDetails as $item) {
            ProductDetail::create([
                'product_id' => $product->id,
                'typename'   => $item['typename'] ?? null,
                'typeprice'  => $item['typeprice'],
                'typeimage'  => 'https://amhere.net/storage/' . $item['typeimage'] ?? null,
            ]);
        }
    }
    private function saveImages(Product $product, array $images)
    {
        foreach ($images as $image) {
            $product->images()->create(['image_path' => 'https://amhere.net/storage/' . $image]);
        }
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
