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
        $data['details'] = $product->productDetails->map(function ($detail) {
            return [
                'price'    => $detail->price,
                'discount' => $detail->discount,
                'stock'    => $detail->stock,
                'color'    => $detail->color,
                'size'     => $detail->size,
                'image'    => $detail->image ? [$detail->image] : [],  // Wrap in array
                'attributes' => $item['attributes'] ?? null,

            ];
        })->toArray();
        $data['images'] = $product->images->pluck('image_path')->toArray(); 
        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->sizesWithPrices = $data['details'];
        unset($data['details']);
        $this->images = isset($data['images']) ? (is_array($data['images']) ? $data['images'] : [$data['images']]) : [];
        unset($data['images']);
        return $data;
    }
    protected function afterSave(): void
    {
        $this->updateSizesWithPrices($this->record, $this->sizesWithPrices);
        $this->saveImages($this->record, $this->images);
    }    
    private function updateSizesWithPrices(Product $product, array $sizesWithPrices)
    {
        $product->productDetails()->delete();
        foreach ($sizesWithPrices as $item) {
            $image = null;
            if (isset($item['image'])) {
                if (is_array($item['image']) && count($item['image']) > 0) {
                    $image = $item['image'][0];
                } elseif (is_string($item['image'])) {
                    $image = $item['image'];
                }
            }
            ProductDetail::create([
                'product_id' => $product->id,
                'price'      => $item['price'],
                'discount'   => $item['discount'] ?? $item['price'],
                'stock'      => $item['stock'] ?? 0,
                'color'      => $item['color'] ?? null,
                'size'       => $item['size'] ?? null,
                'image'      => $image,
                'attributes' => $item['attributes'] ?? null,

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


