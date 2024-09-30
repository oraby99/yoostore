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
        // Initialize the product with basic data
        $product = Product::create($data);
    
        // Handle attributes with language support
        if (isset($data['attributes'])) {
            $attributes = $this->prepareAttributes($data['attributes']);
            // Store attributes directly in the model's attributes field
            $product->attributes = $attributes;
            $product->save();
        }
    
        // Handle images separately
        $images = $data['images'];
        unset($data['images']);
    
        // Decide if you are working with product details or type details
        if ($data['is_product_details']) {
            $this->updateProductDetails($product, $data['product_details']);
        } else {
            $this->updateTypeDetails($product, $data['type_details']);
        }
    
        // Save images for the product
        $this->saveImages($product, $images);
    
        return $product;
    }

    private function updateProductDetails(Product $product, array $productDetails)
    {
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

    private function afterSave(): void
    {
        if (!empty($this->attributes)) {
            $this->record->attributes()->delete();
            $this->record->attributes()->createMany($this->attributes);
        }
    }

    private function prepareAttributes(array $attributes): array
    {
        $structuredAttributes = [];

        // Ensure the input is structured correctly
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                // Handle cases where $value is an array
                foreach ($value as $lang => $attrValue) {
                    $structuredAttributes[$lang][$key] = $attrValue; // Structure as per your requirements
                }
            } else {
                // Handle cases where $value is a string (no language structure)
                $structuredAttributes['en'][$key] = $value; // Assuming 'en' as default
            }
        }

        return $structuredAttributes;
    }
}
