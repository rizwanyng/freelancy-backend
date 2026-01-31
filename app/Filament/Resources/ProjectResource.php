<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

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
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('client_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('budget')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Not Started' => 'Not Started',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                        'On Hold' => 'On Hold',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('deadline'),
                Forms\Components\TextInput::make('estimated_hours')
                    ->numeric(),
                Forms\Components\TextInput::make('currency')
                    ->default('USD'),
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
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('client_name'),
                Tables\Columns\TextColumn::make('budget')->money(fn($record) => $record->currency ?? 'USD'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'Not Started',
                        'primary' => 'In Progress',
                        'success' => 'Completed',
                        'warning' => 'On Hold',
                    ]),
                Tables\Columns\TextColumn::make('deadline')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Filter by User')
                    ->relationship('user', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Not Started' => 'Not Started',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                        'On Hold' => 'On Hold',
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
