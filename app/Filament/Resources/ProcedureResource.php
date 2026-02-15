<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcedureResource\Pages;
use App\Models\Pac;
use App\Models\Procedure;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProcedureResource extends Resource
{
    protected static ?string $model = Procedure::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bed_number')
                    ->required()
                    ->maxLength(255),
                TextInput::make('procedure_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('patient_id')
                    ->label('Patient ID'),
                TextInput::make('patient_name')
                    ->label('Patient Name'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'done' => 'Done',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Hidden::make('added_by'),
                Forms\Components\Hidden::make('finished_by'),
                Forms\Components\Hidden::make('finished_at'),
                Forms\Components\Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Request #')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bed_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('procedure_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('addedBy.name')
                    ->label('Added By'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'done' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('finishedBy.name')
                    ->label('Finished By'),
                Tables\Columns\TextColumn::make('finished_at')
                    ->label('Finished Time')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\Action::make('mark_done')
                    ->requiresConfirmation()
                    ->action(fn(Procedure $record) => $record->update([
                        'status' => 'done',
                        'finished_by' => auth()->id(),
                        'finished_at' => now(),
                    ]))
                    ->visible(fn(Procedure $record) => $record->status === 'pending'),
                Actions\EditAction::make(),
            ])
            ->recordActions(self::fulfill())
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function fulfill(): array
    {
        return [
            Actions\Action::make('fulfill')
                ->requiresConfirmation()
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'done' => 'Done',
                            'pending' => 'Pending',
                        ])
                ])
                ->action(fn(Procedure $record, array $data) => $record->update([
                    'status' => $data['status'],
                    'finished_by' => auth()->id(),
                    'finished_at' => now(),
                ]))
                ->visible(fn(Procedure $record) => $record->status === 'pending'),
            Actions\EditAction::make(),
        ];
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
            'index' => Pages\ListProcedures::route('/'),
            'create' => Pages\CreateProcedure::route('/create'),
            'edit' => Pages\EditProcedure::route('/{record}/edit'),
        ];
    }
}
