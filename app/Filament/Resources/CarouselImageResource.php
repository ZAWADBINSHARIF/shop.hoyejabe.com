<?php

namespace App\Filament\Resources;

use App\Enums\StoragePath;
use App\Enums\TextLength;
use App\Filament\Resources\CarouselImageResource\Pages;
use App\Filament\Resources\CarouselImageResource\RelationManagers;
use App\Models\CarouselImage;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarouselImageResource extends Resource
{
    protected static ?string $model = CarouselImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->maxLength(TextLength::LONG->value),
                TextInput::make('product_url')
                    ->maxLength(TextLength::LONG->value)
                    ->placeholder(config('app.url') . '/product/HoneyBee'),
                FileUpload::make('image')
                    ->image()
                    ->required()
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                    ->directory(StoragePath::CAROUSEL_IMAGES->value)
                    ->disk('public')
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('title'),
                TextColumn::make('product_url')
                    ->label('Product URL')
                    ->url(fn($record) => $record->product_url)
                    ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListCarouselImages::route('/'),
            'create' => Pages\CreateCarouselImage::route('/create'),
            'edit' => Pages\EditCarouselImage::route('/{record}/edit'),
        ];
    }
}
