<?php

namespace App\Filament\Resources\Product;

use App\Filament\Resources\Product\ProductsResource\Pages;
use App\Models\Category;
use App\Models\ProductCategory;
use App\Models\Products;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Actions\ReplicateAction;
use Illuminate\Database\Eloquent\Model;

class ProductsResource extends Resource
{
    protected static ?string $model = Products::class;
    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationGroup = 'Products';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Product')
                ->description('Prevent abuse by limiting the number of requests per period')
                ->schema([
                    TextInput::make('name')->required(),
                    Select::make('category_id')->options(Category::all()->pluck('name', 'id'))
                        ->preload()
                        ->multiple()
                        ->required()
                        ->label('Category'),
                    TextInput::make('description')->required(),
                    TextInput::make('price')->required()->prefix('INR')->numeric(),
                    TextInput::make('discounted_price')->required()->prefix('INR')->numeric(),
                    FileUpload::make('image')->required(),
                ])
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('name'),
                TextColumn::make('price'),
                ToggleColumn::make('is_active')->label('Status')->tooltip('Toggle to mark the status active/inactive.'),
            ])
            ->filters([
                //
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                ReplicateAction::make()
                    ->label("Duplicate")
                    ->beforeReplicaSaved(function (Products $replica): void {
                        $duplicateProductClone = $replica;
                        $originalName = $duplicateProductClone->name;
                        $suffix = 1;

                        while (Products::where('name', $duplicateProductClone->name)->exists()) {
                            $duplicateProductClone->name = $originalName . ' - copy' . $suffix++;
                        }
                    })

                    ->after(function (Model $replica, $record): void {
                        $cloneRecordProuctCategory = $replica;
                        $cloneProductCategorys =  ProductCategory::where('product_id', '=', $record->id)->pluck('category_id');;

                        foreach ($cloneProductCategorys as $cloneProductCategory) {
                            ProductCategory::create([
                                'category_id' => $cloneProductCategory,
                                'product_id' =>  $cloneRecordProuctCategory->id,
                            ]);
                        }
                    })
            ])

            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProducts::route('/create'),
            'edit' => Pages\EditProducts::route('/{record}/edit'),
        ];
    }
}
