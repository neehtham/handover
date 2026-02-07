<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('id_no')
                    ->required()
                    ->maxLength(255)
                    ->label('ID No.'),
                Forms\Components\Textarea::make('diagnosis')
                    ->required(),
                Forms\Components\TextInput::make('bed_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'chronic' => 'Chronic',
                        'post_op' => 'Post Op',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bed_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'chronic' => 'info',
                        'post_op' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_discharged')
                    ->boolean(),
                Tables\Columns\TextColumn::make('discharged_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn(Builder $query) => $query->where('is_discharged', false))
                    ->default(),
            ])
            ->recordActions(self::discharge())
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function discharge(): array
    {
        return [
            Actions\Action::make('discharge')
                ->requiresConfirmation()
                ->action(function (Patient $record) {
                    $record->update([
                        'is_discharged' => true,
                        'discharged_at' => now(),
                        'discharged_by' => auth()->id(),
                    ]);
                })
            ->visible(fn (Patient $record) => !$record->is_discharged),
            Actions\EditAction::make(),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChronicRoundsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
