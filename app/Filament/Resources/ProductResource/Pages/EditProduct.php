<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductDetail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\Features\SupportAttributes\AttributeCollection;

class EditProduct extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = ProductResource::class;

    public AttributeCollection $attributes;

    public function mount(string|int $record): void
    {
        parent::mount($record);

        // Initialize the attributes collection
        $this->attributes = new AttributeCollection();

        // Load the product attributes into the collection
        if ($this->record) {
            $attributes = $this->record->attributes;

            // Check if attributes is a JSON string
            if (is_string($attributes)) {
                $decodedAttributes = json_decode($attributes, true);
                if (is_array($decodedAttributes)) {
                    foreach ($decodedAttributes as $key => $value) {
                        $this->attributes[$key] = $value; // Safe assignment
                    }
                }
            }
        }
    }

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
        $attributes = $product->attributes;

        // Ensure attributes are processed correctly
        if (is_string($attributes)) {
            $decodedAttributes = json_decode($attributes, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decodedAttributes)) {
                $attributes = []; // Handle JSON decoding error or if not an array
            } else {
                $attributes = $decodedAttributes;
            }
        } elseif (!is_array($attributes)) {
            $attributes = [];
        }

        // Initialize attributes in $data safely
        $data['attributes'] = isset($data['attributes']) && is_array($data['attributes']) ? $data['attributes'] : [];
        foreach ($attributes as $key => $value) {
            if (!is_string($value) && !is_array($value)) {
                \Log::error("Unexpected attribute value type for key '{$key}': ", ['value' => $value]);
                continue; // Skip this value
            }
            $data['attributes'][$key] = $value;
        }

        $data['images'] = $product->images->pluck('image_path')->toArray();

        // Handling product details or type details
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

        // Safely store the attributes from the data
        $this->attributes = new AttributeCollection();

        if (isset($data['attributes']) && is_array($data['attributes'])) {
            foreach ($data['attributes'] as $key => $value) {
                $this->attributes[$key] = $value;
            }
        }

        unset($data['product_details'], $data['type_details'], $data['attributes']);

        // Handle images
        $this->images = isset($data['images']) ? (is_array($data['images']) ? $data['images'] : [$data['images']]) : [];
        unset($data['images']);

        return $data;
    }

    protected function handleRecordUpdate($record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Call the parent method to handle the update
        $product = parent::handleRecordUpdate($record, $data);

        \Log::debug('Product returned from handleRecordUpdate:', ['product' => $product]);

        // Call afterSave with the updated product
        $this->afterSave($product);

        return $product;
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $product = parent::save($shouldRedirect, $shouldSendSavedNotification);
    
        if ($product instanceof Product) {
            $this->afterSave($product); // Call with the product
        } else {
            \Log::error('No product returned from save method.');
        }
    }
    
    
    protected function afterSave(?Product $product = null): void
    {
        if ($product === null) {
            \Log::error('afterSave called with null product');
            return; // Handle this case appropriately
        }
    
        // Handle saving attributes if they are stored as JSON
        if ($this->attributes->isNotEmpty()) {
            $product->attributes = json_encode($this->attributes->toArray());
            $product->save(); // Save the product after modifying the attributes
        }
    
        // Save images after attributes
        $this->saveImages($product, $this->images);
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
        $product->images()->delete(); // Delete existing images
        foreach ($images as $image) {
            if ($image) {
                $product->images()->create(['image_path' => $image]); // Create new image records
            }
        }
    }
}
