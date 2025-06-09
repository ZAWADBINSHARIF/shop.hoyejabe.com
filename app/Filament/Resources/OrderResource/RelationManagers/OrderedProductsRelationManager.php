<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Enums\TextLength;
use App\Models\OrderedProduct;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderedProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderedProducts';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (string $state, Set $set) {
                        $productID = $state;

                        if ($productID) {
                            $product = Product::find($productID);

                            $set('product_name', $product->name);
                            $set('base_price', $product->base_price);
                            $set('extra_shipping_cost', $product->extra_shipping_cost);
                        }
                    }),

                TextInput::make('product_name')
                    ->maxLength(TextLength::LONG->value)
                    ->required(),

                TextInput::make('quantity')
                    ->numeric()
                    ->required(),

                Select::make('selected_color_code')
                    ->reactive()
                    ->options(function (Get $get) {
                        $productId = $get('product_id');

                        if (!$productId) {
                            return [];
                        }

                        return ProductColor::where('product_id', $productId)
                            ->pluck('color_code', 'color_code');
                    })
                    ->native(false)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $productId = $get('product_id');
                        $selected_color_code = $get('selected_color_code');

                        if (!$productId || !$selected_color_code) {
                            return [];
                        }

                        $productColorDetail = ProductColor::where('product_id', $productId)->where('color_code', $selected_color_code)->first();

                        if ($productColorDetail) {
                            $set('color_extra_price', $productColorDetail->extra_price ?? 0);
                        }
                    })
                    ->nullable(),

                TextInput::make('color_extra_price')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Select::make('selected_size')
                    ->label('Size')
                    ->reactive()
                    ->native(false)
                    ->options(function (Get $get) {
                        $productId = $get('product_id');

                        if (!$productId) {
                            return [];
                        }

                        return ProductSize::with('size')
                            ->where('product_id', $productId)
                            ->get()
                            ->pluck('size.value', 'size.value')
                            ->toArray();
                    })
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $productId = $get('product_id');
                        $selectedSize = $get('selected_size');

                        if (!$productId || !$selectedSize) {
                            return;
                        }

                        $productSizeDetails = ProductSize::where('product_id', $productId)
                            ->whereHas('size', function ($query) use ($selectedSize) {
                                $query->where('value', $selectedSize);
                            })
                            ->with('size')
                            ->first();

                        if ($productSizeDetails) {
                            $set('size_extra_price', $productSizeDetails->extra_price ?? 0);
                        }
                    })
                    ->nullable(),

                TextInput::make('size_extra_price')
                    ->numeric()
                    ->default(0)
                    ->required(),

                TextInput::make('base_price')
                    ->numeric()
                    ->required(),

                TextInput::make('extra_shipping_cost')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->columns([

                TextColumn::make('product_name')
                    ->label('Product Name (Snapshot)')
                    ->sortable()
                    ->searchable(),

                ColorColumn::make('selected_color_code')
                    ->label('Color Code')
                    ->sortable(),

                TextColumn::make('color_extra_price')
                    ->prefix('৳')
                    ->sortable(),

                TextColumn::make('selected_size')->sortable(),

                TextColumn::make('size_extra_price')
                    ->prefix('৳')
                    ->sortable(),

                TextColumn::make('base_price')
                    ->prefix('৳')
                    ->sortable(),

                TextColumn::make('quantity')
                    ->sortable(),

                TextColumn::make('extra_shipping_cost')
                    ->prefix('৳')
                    ->sortable(),

                TextColumn::make('product_total_price')
                    ->prefix('৳')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime('d M Y, h:i A')
                    ->timezone('Asia/Dhaka')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
