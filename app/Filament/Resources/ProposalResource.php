<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProposalResource\Pages;
use App\Models\Proposal;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ProposalResource extends Resource
{
    protected static ?string $model = Proposal::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->disabled()
                    ->placeholder('UUID auto-generated'),
                Forms\Components\TextInput::make('client_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('project_title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('estimated_budget')
                    ->numeric()
                    ->required()
                    ->prefix('$'),
                Forms\Components\DateTimePicker::make('date_sent')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Sent' => 'Sent',
                        'Accepted' => 'Accepted',
                        'Rejected' => 'Rejected',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('timeline')
                    ->maxLength(255),
                Forms\Components\TextInput::make('style')
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('project_title')->searchable(),
                Tables\Columns\TextColumn::make('estimated_budget')->money('USD'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'Draft',
                        'primary' => 'Sent',
                        'success' => 'Accepted',
                        'danger' => 'Rejected',
                    ]),
                Tables\Columns\TextColumn::make('date_sent')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Filter by User')
                    ->relationship('user', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Sent' => 'Sent',
                        'Accepted' => 'Accepted',
                        'Rejected' => 'Rejected',
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProposals::route('/'),
            'create' => Pages\CreateProposal::route('/create'),
            'edit' => Pages\EditProposal::route('/{record}/edit'),
        ];
    }
}
