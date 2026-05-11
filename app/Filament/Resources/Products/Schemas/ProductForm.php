<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('brand_id')
                    ->relationship('brand', 'name'),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                Textarea::make('short_description')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('top_notes')
                    ->label('Top notes')
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('middle_notes')
                    ->label('Middle notes')
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('base_notes')
                    ->label('Base notes')
                    ->rows(2)
                    ->columnSpanFull(),
                TextInput::make('fragrance_type'),
                TextInput::make('gender'),
                Select::make('product_type')
                    ->options([
                        'perfume' => 'Perfume',
                        'attar' => 'Attar',
                        'oud' => 'Oud',
                        'arabic_collection' => 'Arabic collection',
                        'luxury_collection' => 'Luxury collection',
                    ])
                    ->default('perfume')
                    ->required(),
                TextInput::make('base_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('compare_at_price')
                    ->numeric()
                    ->prefix('$'),
                Toggle::make('is_featured')
                    ->required(),
                Toggle::make('is_new')
                    ->required(),
                Toggle::make('is_bestseller')
                    ->required(),
                Toggle::make('is_on_sale')
                    ->required(),
                Toggle::make('is_limited_edition')
                    ->label('Limited edition'),
                TextInput::make('meta_title'),
                TextInput::make('meta_description'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
