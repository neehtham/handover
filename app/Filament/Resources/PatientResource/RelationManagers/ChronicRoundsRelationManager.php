<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ChronicRoundsRelationManager extends RelationManager
{
    protected static string $relationship = 'chronicRounds';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Hidden::make('doctor_id')
                    ->default(fn () => auth()->id()),
                Forms\Components\Textarea::make('advice')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('advice')
            ->columns([
                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor'),
                Tables\Columns\TextColumn::make('diagnosis_update')
                    ->limit(50),
                Tables\Columns\TextColumn::make('advice')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
