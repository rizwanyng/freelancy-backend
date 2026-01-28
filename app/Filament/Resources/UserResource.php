<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\Select::make('plan')
                    ->options([
                        'free' => 'Free',
                        'pro' => 'Pro',
                        'elite' => 'Elite',
                    ])
                    ->default('free')
                    ->required(),
                Forms\Components\DateTimePicker::make('plan_expires_at')
                    ->label('Subscription Expiry'),
                Forms\Components\Section::make('Payment Gateways')
                    ->schema([
                        Forms\Components\TextInput::make('stripe_link')
                            ->url()
                            ->placeholder('https://buy.stripe.com/...'),
                        Forms\Components\TextInput::make('paypal_email')
                            ->email()
                            ->placeholder('yourname@paypal.com'),
                        Forms\Components\TextInput::make('upi_id')
                            ->placeholder('username@bank'),
                    ])->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\BadgeColumn::make('plan')
                    ->colors([
                        'secondary' => 'free',
                        'primary' => 'pro',
                        'success' => 'elite',
                    ]),
                Tables\Columns\TextColumn::make('plan_expires_at')
                    ->dateTime()
                    ->label('Expiry'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
