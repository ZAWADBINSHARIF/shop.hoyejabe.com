<?php

namespace App\Filament\Resources;

use App\Enums\TextLength;
use App\Filament\Resources\ShippingCostResource\Pages;
use App\Filament\Resources\ShippingCostResource\RelationManagers;
use App\Models\ShippingCost;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingCostResource extends Resource
{
    protected static ?string $model = ShippingCost::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(TextLength::SHORT->value),
                TextInput::make('cost')
                    ->numeric()
                    ->default(0)
                    ->prefix('৳'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('cost')
                    ->prefix("৳")
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingCosts::route('/'),
            'create' => Pages\CreateShippingCost::route('/create'),
            'edit' => Pages\EditShippingCost::route('/{record}/edit'),
        ];
    }
}
