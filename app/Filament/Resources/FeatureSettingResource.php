<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeatureSettingResource\Pages;
use App\Models\FeatureSetting;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class FeatureSettingResource extends Resource
{
    protected static ?string $model = FeatureSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments';
    
    protected static ?string $navigationGroup = 'Master Control';

    protected static ?string $navigationLabel = 'Feature Control Centre';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->disabled(),
                Forms\Components\Toggle::make('is_enabled')
                    ->label('Feature Active')
                    ->onColor('success')
                    ->offColor('danger'),
                Forms\Components\TextInput::make('category')
                    ->disabled(),
                Forms\Components\Textarea::make('description')
                    ->disabled()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('category')
                    ->colors([
                        'primary' => 'Monetization',
                        'success' => 'AI',
                        'warning' => 'Tools',
                        'danger' => 'Security',
                        'secondary' => 'Experience',
                    ]),
                Tables\Columns\TextColumn::make('label')->weight('bold'),
                Tables\Columns\ToggleColumn::make('is_enabled')
                    ->label('Master Status'),
                Tables\Columns\TextColumn::make('description')->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Monetization' => 'Monetization',
                        'AI' => 'AI',
                        'Tools' => 'Tools',
                        'Security' => 'Security',
                        'Experience' => 'Experience',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeatureSettings::route('/'),
        ];
    }
}
