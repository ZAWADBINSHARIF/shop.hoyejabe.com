<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\TextLength;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\OrderedProductsRelationManager;
use App\Models\Order;
use App\Models\ShippingCost;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function getNavigationBadge(): ?string
    {
        return Order::count();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Customer Info')->schema([
                    TextInput::make('order_tracking_id')
                        ->readOnly()
                        ->visibleOn('edit')
                        ->maxLength(TextLength::LONG->value),

                    TextInput::make('customer_name')
                        ->required()
                        ->maxLength(TextLength::LONG->value),

                    TextInput::make('customer_mobile')
                        ->required()
                        ->tel()
                        ->maxLength(TextLength::PHONE->value),

                    TextInput::make('city')
                        ->maxLength(TextLength::MEDIUM->value)
                        ->required(),

                    TextInput::make('address')
                        ->label('Full Address')
                        ->maxLength(TextLength::LONG->value)
                        ->nullable(),

                    TextInput::make('upazila')
                        ->maxLength(TextLength::LONG->value)
                        ->nullable(),

                    TextInput::make('thana')
                        ->maxLength(TextLength::LONG->value)
                        ->nullable(),

                    TextInput::make('post_code')
                        ->nullable()
                        ->maxLength(TextLength::LONG->value)
                        ->maxLength(10),
                ])->columns(2),

                Section::make('Shipping')->schema([
                    Select::make('selected_shipping_area')
                        ->label('Shipping Method')
                        ->relationship('shipping', 'title') // assumes `title` field in ShippingCost
                        ->required()
                        ->native(false)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $state, $set) {

                            if ($state) {
                                $shipping_method = ShippingCost::find($state);

                                if ($shipping_method->cost) {
                                    $set('shipping_cost', $shipping_method->cost);
                                }
                            }
                        }),

                    TextInput::make('shipping_cost')
                        ->numeric()
                        ->required()
                        ->prefix('৳'),

                    TextInput::make('extra_shipping_cost')
                        ->numeric()
                        ->default(0)
                        ->prefix('৳'),
                ])->columns(3),

                Section::make('Order Summary')->schema([
                    TextInput::make('total_price')
                        ->numeric()
                        ->required()
                        ->prefix('৳'),

                    Select::make('order_status')
                        ->required()
                        ->options(OrderStatus::class)
                        ->native(false),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_tracking_id'),

                TextColumn::make('customer_name')->searchable()->sortable(),
                TextColumn::make('customer_mobile')->searchable(),
                TextColumn::make('city')->sortable(),

                // Optional location fields
                TextColumn::make('upazila')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('thana')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('post_code')->toggleable(isToggledHiddenByDefault: true),

                // Shipping cost relationship
                TextColumn::make('shipping.title')
                    ->label('Shipping Method')
                    ->sortable(),


                TextColumn::make('shipping_cost')->prefix('৳')->sortable(),
                TextColumn::make('extra_shipping_cost')->prefix('৳')->sortable(),
                TextColumn::make('total_price')->prefix('৳')->sortable(),

                SelectColumn::make('order_status')
                    ->options(OrderStatus::class),

                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime('d M Y, h:i A')
                    ->timezone('Asia/Dhaka')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('order_status')
                    ->native(false)
                    ->options(OrderStatus::class)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrderedProductsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            // 'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            // 'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
