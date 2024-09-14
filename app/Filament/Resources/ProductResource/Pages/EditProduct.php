<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductDetail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = ProductResource::class;
    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $product = $this->record->load('productDetails', 'images');
        $data['images'] = $product->images->pluck('image_path')->toArray();
    
        if ($product->productDetails->isNotEmpty() && !$product->productDetails->first()->typename) {
            $data['is_product_details'] = true;
            $data['product_details'] = $product->productDetails->map(function ($detail) {
                return [
                    'price' => $detail->price,
                    'stock' => $detail->stock,
                    'color' => $detail->color,
                    'size' => $detail->size,
                    'image' => $detail->image ? [$detail->image] : [],
                ];
            })->toArray();
            $data['type_details'] = [];
        } else {
            $data['is_product_details'] = false;
            $data['type_details'] = $product->productDetails->map(function ($detail) {
                return [
                    'typename' => $detail->typename,
                    'typeprice' => $detail->typeprice,
                    'typestock' => $detail->typestock,
                    'typeimage' => $detail->typeimage ? [$detail->typeimage] : [],
                ];
            })->toArray();
            $data['product_details'] = [];
        }
    
        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->productDetails = $data['product_details'] ?? [];
        $this->typeDetails = $data['type_details'] ?? [];
        if (isset($data['product_details'])) {
            unset($data['product_details']);
        }
        if (isset($data['type_details'])) {
            unset($data['type_details']);
        }
        $this->images = isset($data['images']) ? (is_array($data['images']) ? $data['images'] : [$data['images']]) : [];
        if (isset($data['images'])) {
            unset($data['images']);
        }
        return $data;
    }
    protected function afterSave(): void
    {
        // Update product details only if they are present
        if (!empty($this->productDetails)) {
            $this->updateProductDetails($this->record, $this->productDetails);
        }
    
        // Update type details only if they are present
        if (!empty($this->typeDetails)) {
            $this->updateTypeDetails($this->record, $this->typeDetails);
        }
    
        // Save images if any are present
        if (!empty($this->images)) {
            $this->saveImages($this->record, $this->images);
        }
    }
    private function updateProductDetails(Product $product, array $productDetails)
    {
        $product->productDetails()->whereNull('typename')->delete(); // Ensure only product details are updated
        foreach ($productDetails as $item) {
            ProductDetail::create([
                'product_id' => $product->id,
                'price'      => $item['price'],
                'stock'      => $item['stock'] ?? 0,
                'color'      => $item['color'] ?? null,
                'size'       => $item['size'] ?? null,
                'image'      => $item['image'] ?? null,
            ]);
        }
    }
    private function updateTypeDetails(Product $product, array $typeDetails)
    {
        $product->productDetails()->whereNotNull('typename')->delete(); // Ensure only type details are updated
        foreach ($typeDetails as $item) {
            ProductDetail::create([
                'product_id' => $product->id,
                'typename'   => $item['typename'] ?? null,
                'typeprice'  => $item['typeprice'],
                'typestock'  => $item['typestock'] ?? 0,
                'typeimage'  => $item['typeimage'] ?? null,
            ]);
        }
    }
    private function saveImages(Product $product, array $images)
    {
        $product->images()->delete();
        foreach ($images as $image) {
            if ($image) {
                $product->images()->create(['image_path' => $image]);
            }
        }
    }
}
