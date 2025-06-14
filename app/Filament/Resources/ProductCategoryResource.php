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
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\QueryException;

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
                    BulkAction::make('delete')
                        ->label('Delete selected')
                        ->color('danger')
                        ->icon('heroicon-m-trash')
                        ->action(function (Collection $records) {
                            try {
                                foreach ($records as $record) {
                                    $record->delete();
                                }

                                Notification::make()
                                    ->title('Deleted successfully')
                                    ->success()
                                    ->send();
                            } catch (QueryException $e) {
                                if ($e->getCode() == '23000' && str_contains($e->getMessage(), '1451')) {
                                    Notification::make()
                                        ->title('Cannot delete records')
                                        ->body('One or more selected items are linked to other records (Products) and cannot be deleted.')
                                        ->warning()
                                        ->send();
                                } else {
                                    throw $e;
                                }
                            }
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
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
