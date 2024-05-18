<?php

namespace App\Filament\Resources\Product\ProductsResource\Pages;

use App\Filament\Resources\Product\ProductsResource;
use App\Models\ProductCategory;
use App\Models\Products;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProducts extends CreateRecord
{
    protected static string $resource = ProductsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $productTable): Model
    {
        $product = Products::create($productTable);

        foreach ($productTable['category_id'] as $CreatecategoryIdProduct) {
            ProductCategory::create([
                'category_id' => $CreatecategoryIdProduct,
                'product_id' => $product->id,
            ]);
        }

        return $product;
    }
}
