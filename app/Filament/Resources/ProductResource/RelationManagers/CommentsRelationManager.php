<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Product Comments & Reviews';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Forms\Components\TextInput::make('customer_name')
                    ->label('Display Name')
                    ->helperText('Override customer name for display')
                    ->maxLength(255),
                
                Forms\Components\Textarea::make('comment')
                    ->required()
                    ->columnSpanFull()
                    ->rows(4),
                
                Forms\Components\Select::make('rating')
                    ->options([
                        1 => '⭐ 1 Star - Poor',
                        2 => '⭐⭐ 2 Stars - Fair',
                        3 => '⭐⭐⭐ 3 Stars - Good',
                        4 => '⭐⭐⭐⭐ 4 Stars - Very Good',
                        5 => '⭐⭐⭐⭐⭐ 5 Stars - Excellent',
                    ])
                    ->default(5),
                
                Forms\Components\Toggle::make('is_verified_purchase')
                    ->label('Verified Purchase')
                    ->helperText('Customer purchased this product'),
                
                Forms\Components\Toggle::make('is_approved')
                    ->label('Approved')
                    ->helperText('Show this comment publicly')
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                        $state ? $set('approved_at', now()) : $set('approved_at', null)
                    ),
                
                Forms\Components\Toggle::make('is_visible')
                    ->label('Visible')
                    ->default(true),
                
                Forms\Components\DateTimePicker::make('approved_at')
                    ->label('Approved At')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('comment')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->comment;
                    })
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('rating')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? "⭐ {$state}/5" : '-')
                    ->color(fn ($state) => match (true) {
                        $state >= 4 => 'success',
                        $state == 3 => 'warning',
                        $state <= 2 => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\IconColumn::make('is_verified_purchase')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle'),
                
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean(),
                
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visible')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        1 => '⭐ 1 Star',
                        2 => '⭐⭐ 2 Stars',
                        3 => '⭐⭐⭐ 3 Stars',
                        4 => '⭐⭐⭐⭐ 4 Stars',
                        5 => '⭐⭐⭐⭐⭐ 5 Stars',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Approval Status')
                    ->placeholder('All')
                    ->trueLabel('Approved')
                    ->falseLabel('Pending'),
                
                Tables\Filters\TernaryFilter::make('is_verified_purchase')
                    ->label('Verified Purchase'),
                
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Visibility'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => !$record->is_approved)
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'is_approved' => true,
                        'approved_at' => now(),
                    ])),
                
                Tables\Actions\Action::make('hide')
                    ->label('Hide')
                    ->icon('heroicon-o-eye-slash')
                    ->color('warning')
                    ->visible(fn ($record) => $record->is_visible)
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['is_visible' => false])),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update([
                            'is_approved' => true,
                            'approved_at' => now(),
                        ])),
                    
                    Tables\Actions\BulkAction::make('hide')
                        ->label('Hide Selected')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_visible' => false])),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
