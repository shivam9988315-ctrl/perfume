<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                TextInput::make('guest_email')
                    ->email(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('payment_status')
                    ->required()
                    ->default('pending'),
                TextInput::make('payment_method'),
                Select::make('coupon_id')
                    ->relationship('coupon', 'id'),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric(),
                TextInput::make('discount_total')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('tax_total')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('shipping_total')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('grand_total')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                Textarea::make('shipping_address')
                    ->columnSpanFull(),
                Textarea::make('billing_address')
                    ->columnSpanFull(),
                Textarea::make('customer_note')
                    ->columnSpanFull(),
                DateTimePicker::make('placed_at'),
            ]);
    }
}
