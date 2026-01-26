<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChronicRoundsRelationManager extends RelationManager
{
    protected static string $relationship = 'chronicRounds';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('doctor_id')
                    ->default(fn () => auth()->id()),
                Forms\Components\Textarea::make('diagnosis_update'),
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
