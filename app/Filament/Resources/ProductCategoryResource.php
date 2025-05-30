<?php

namespace App\Filament\Resources;

use Illuminate\Support\Str;
use App\Enums\TextLength;
use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Filament\Resources\ProductCategoryResource\RelationManagers;
use App\Models\ProductCategory;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ProductCategory::class, 'name', ignoreRecord: true)
                    ->maxLength(TextLength::MEDIUM->value)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, Set $set, string|null $state) {

                        if ($operation) {
                            $set('slug', Str::slug($state, separator: "-"));
                        }
                    }),

                TextInput::make('slug')
                    ->required()
                    ->readOnly()
                    ->unique(ProductCategory::class, 'slug', ignoreRecord: true)
                    ->maxLength(TextLength::MEDIUM->value),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name"),
                TextColumn::make("slug"),
                TextColumn::make("created_at")
                    ->timezone("Asia/Dhaka")
                    ->date('d-M-y, h:i A')
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
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }
}
