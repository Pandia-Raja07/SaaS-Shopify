<?php

namespace App\Filament\Resources\Product\ProductsResource\Pages;

use App\Filament\Resources\Product\ProductsResource;
use App\Models\ProductCategory;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProducts extends EditRecord
{
    protected static string $resource = ProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $categoryShow): array
    {
        $categoryShow['category_id'] = [];
        $categoryShow['category_id'] = ProductCategory::where('product_id', $categoryShow['id'])->pluck('category_id');
        return $categoryShow;
    }

    protected function handleRecordUpdate(Model $storedProducts, array $updateStoredProducts): Model
    {
        $updateCategorys = $updateStoredProducts['category_id'];
        $productName = $storedProducts->id;
        ProductCategory::where('product_id', $storedProducts->id)->delete();

        foreach ($updateCategorys as $updateCategory) {
            ProductCategory::create([
                'category_id' => $updateCategory,
                'product_id' => $productName,
            ]);
        }
        
        $storedProducts->update($updateStoredProducts);
        return $storedProducts;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
