<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->disabled()
                    ->placeholder('UUID auto-generated'),
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'name')
                    ->searchable(),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable(),
                Forms\Components\TextInput::make('client_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->prefix('$'),
                Forms\Components\DateTimePicker::make('date'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Paid' => 'Paid',
                        'Pending' => 'Pending',
                        'Overdue' => 'Overdue',
                        'Draft' => 'Draft',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_external'),
                Forms\Components\TextInput::make('currency')
                    ->default('USD'),
                Forms\Components\Toggle::make('is_gst_enabled'),
                Forms\Components\TextInput::make('gst_percentage')
                    ->numeric()
                    ->default(18.0),
                Forms\Components\Textarea::make('description')
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner')
                    ->searchable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('client_name')->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn ($state, $record) => '$' . number_format($state, 0)),
                Tables\Columns\TextColumn::make('date')->date(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'Paid',
                        'warning' => 'Pending',
                        'danger' => 'Overdue',
                        'secondary' => 'Draft',
                    ]),
                Tables\Columns\IconColumn::make('is_external')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Filter by User')
                    ->relationship('user', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Paid' => 'Paid',
                        'Pending' => 'Pending',
                        'Overdue' => 'Overdue',
                        'Draft' => 'Draft',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes()->withoutTrashed();
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
