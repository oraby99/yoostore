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
        $sizesWithPrices = $data['details'];
        unset($data['details']);
        $images = $data['images'];
        unset($data['images']);
        $product = Product::create($data);
        $this->updateSizesWithPrices($product, $sizesWithPrices);
        $this->saveImages($product, $images);
        return $product;
    }
    private function updateSizesWithPrices(Product $product, array $sizesWithPrices)
    {
        foreach ($sizesWithPrices as $item) {
            ProductDetail::create([
                'product_id' => $product->id,
                'price'      => $item['price'],
                'discount'   => $item['discount'] ?? $item['price'],
                'stock'      => $item['stock'] ?? 0,
                'color'      => $item['color'] ?? null,
                'size'       => $item['size'] ?? null,
                'image'      => $item['image'] ?? null,
                'size'       => $item['size'] ?? null,
                'attributes' => $item['attributes'] ?? null,

            ]);
        }
    }
    private function saveImages(Product $product, array $images)
    {
        foreach ($images as $image) {
            $product->images()->create(['image_path' => $image]);
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
