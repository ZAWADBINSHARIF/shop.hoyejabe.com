<?php

namespace App\Filament\Resources;

use App\Enums\TextLength;
use App\Filament\Resources\SizeResource\Pages;
use App\Filament\Resources\SizeResource\RelationManagers;
use App\Models\Size;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class SizeResource extends Resource
{
    protected static ?string $model = Size::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-minus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('value')
                    ->unique(Size::class, 'value', ignoreRecord: true)
                    ->required()
                    ->maxLength(TextLength::MEDIUM->value)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("value")
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
            'index' => Pages\ListSizes::route('/'),
            'create' => Pages\CreateSize::route('/create'),
            'edit' => Pages\EditSize::route('/{record}/edit'),
        ];
    }
}
