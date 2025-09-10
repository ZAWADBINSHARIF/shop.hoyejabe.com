<?php

namespace App\Filament\Resources;

use Illuminate\Support\Str;
use App\Enums\StoragePath;
use App\Enums\TextLength;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Forms\Components\BasicEditor;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\size;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{
    ColorPicker,
    TextInput,
    Toggle,
    Select,
    FileUpload,
    Repeater,
    Section
};
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\{
    TextColumn,
    BooleanColumn,
    BadgeColumn,
    ImageColumn,
    SelectColumn,
    ToggleColumn
};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function getNavigationBadge(): ?string
    {
        return Product::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(TextLength::LONG->value)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, Set $set, string|null $state) {

                            if ($operation) {
                                $set('slug', Str::slug($state, separator: "-"));
                            }
                        }),

                    TextInput::make('slug')
                        ->required()
                        ->unique(Product::class, 'slug', ignoreRecord: true)
                        ->readOnly()
                        ->maxLength(TextLength::LONG->value),

                    Select::make('product_category')
                        ->label('Category')
                        ->required()
                        ->relationship('category', 'name')
                        ->native(false)
                        ->createOptionForm([
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
                        ]),

                    Section::make()->schema([
                        Toggle::make('published')
                            ->label('Published'),

                        Toggle::make('out_of_stock')
                            ->label('Out of Stock'),
                    ])->columns(2)->columnSpan(1),

                    Section::make()
                        ->schema([
                            BasicEditor::make('highlighted_description')
                                ->required()
                                ->disableToolbarButtons([
                                    'blockquote',
                                    'strike',
                                    'h1',
                                    'h2',
                                    'h3',
                                    'link'
                                ]),

                            BasicEditor::make('details_description')
                                ->label('Detailed Description')
                                ->nullable(),
                        ])->columns(2),

                ])->columns(2),

                Section::make()
                    ->schema([
                        TextInput::make('base_price')
                            ->numeric()
                            ->required()
                            ->afterStateUpdated(function (?string $state, ?string $old, Get $get, Set $set) {

                                if ($get('discount_percentage')) {
                                    $set('without_discount_price', $state + $state * ($get('discount_percentage') / 100));
                                }
                            })
                            ->live(true)
                            ->prefix('৳'),


                        TextInput::make('discount_percentage')
                            ->numeric()
                            ->afterStateUpdated(function (?string $state, ?string $old, Get $get, Set $set) {
                                $set('without_discount_price', $get('base_price') + $get('base_price') * ($state / 100));
                            })
                            ->live(true)
                            ->suffix('%'),

                        TextInput::make('without_discount_price')
                            ->numeric()
                            ->prefix('৳')
                            ->readOnly(),

                        TextInput::make('extra_shipping_cost')
                            ->numeric()
                            ->default(0)
                            ->prefix('৳'),

                        Toggle::make('toggle_discount_price')
                            ->hint('Turn on and off for calculating and showing the discount'),
                    ])->columns(2),


                Section::make("Add Sizes and colors")
                    ->schema([
                        Repeater::make('sizes')
                            ->relationship()
                            ->schema([
                                Select::make('size_id')
                                    ->relationship('size', 'value')
                                    ->required()
                                    ->native(false)
                                    ->createOptionForm([
                                        TextInput::make('value')
                                            ->unique(Size::class, 'value', ignoreRecord: true)
                                            ->required()
                                            ->maxLength(TextLength::MEDIUM->value)
                                    ]),

                                TextInput::make('extra_price')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('৳'),
                            ])
                            ->default([])
                            ->reorderable(false)
                            ->collapsible(),

                        Repeater::make('colors')
                            ->relationship()
                            ->schema([
                                ColorPicker::make('color_code')
                                    ->required(),

                                TextInput::make('extra_price')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('৳'),
                            ])
                            ->default([])
                            ->reorderable(false)
                            ->collapsible()
                    ])->columns(2)->columnSpan(1),

                FileUpload::make('images')
                    ->label('Product Images')
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '1:1',
                    ])
                    ->imageCropAspectRatio('1:1')
                    ->rules(['dimensions:ratio=1/1'])
                    ->helperText('Upload a aspect ratio 1:1 image (PNG/JPG/jpeg).')
                    ->maxFiles(10)
                    ->required()
                    ->disk('public')
                    ->directory(StoragePath::PRODUCT_IMAGES->value),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Category')->sortable(),
                TextColumn::make('base_price')->money('bdt', true)->sortable(),
                ToggleColumn::make('published'),
                ToggleColumn::make('out_of_stock'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->timezone("Asia/Dhaka")
                    ->date('d-M-y, h:i A'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
