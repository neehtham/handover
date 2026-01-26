<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProceduresRelationManager extends RelationManager
{
    protected static string $relationship = 'procedures';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('bed_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('procedure_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'done' => 'Done',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('procedure_name')
            ->columns([
                Tables\Columns\TextColumn::make('bed_number'),
                Tables\Columns\TextColumn::make('procedure_name'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                     ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'done' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('addedBy.name')
                    ->label('Added By'),
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
                Tables\Actions\Action::make('mark_done')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'status' => 'done',
                        'finished_by' => auth()->id(),
                        'finished_at' => now(),
                    ]))
                    ->visible(fn ($record) => $record->status === 'pending'),
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
