<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->disabled()
                    ->placeholder('UUID auto-generated'),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_completed'),
                Forms\Components\TextInput::make('total_seconds')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_running'),
                Forms\Components\KeyValue::make('daily_tracked')
                    ->keyLabel('Date')
                    ->valueLabel('Seconds'),
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
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('project.name'),
                Tables\Columns\IconColumn::make('is_completed')->boolean(),
                Tables\Columns\TextColumn::make('total_seconds')
                    ->formatStateUsing(fn ($state) => floor($state / 3600) . 'h ' . floor(($state % 3600) / 60) . 'm'),
                Tables\Columns\IconColumn::make('is_running')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Filter by User')
                    ->relationship('user', 'name'),
                Tables\Filters\Filter::make('is_completed')
                    ->query(fn ($query) => $query->where('is_completed', true)),
                Tables\Filters\Filter::make('is_running')
                    ->query(fn ($query) => $query->where('is_running', true)),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
