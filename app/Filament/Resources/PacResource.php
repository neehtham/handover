<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PacResource\Pages;
use App\Filament\Resources\PacResource\RelationManagers;
use App\Models\Pac;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PacResource extends Resource
{
    protected static ?string $model = Pac::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('patient_name')
                    ->label('Patient Name'),
                Forms\Components\TextInput::make('patient_id')
                    ->label('Patient ID'),
                Forms\Components\TextInput::make('bed_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'cleared' => 'Cleared',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Hidden::make('added_by'),
                Forms\Components\Hidden::make('fulfilled_by'),
                Forms\Components\Textarea::make('remarks')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('fulfilled_at')
                     ->hidden(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bed_number')
                    ->label('Bed No')
                    ->searchable(),
                Tables\Columns\TextColumn::make('patient_name')
                    ->label('Patient Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('patient_id')
                    ->label('Patient ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('addedBy.name')
                    ->label('Added By'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'cleared' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fulfilledBy.name')
                    ->label('Fulfilled By'),
                Tables\Columns\TextColumn::make('fulfilled_at')
                    ->label('Fulfilled Time')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions(self::fulfill())
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function fulfill() : array
    {
        return[
            Actions\Action::make('fulfill')
                ->requiresConfirmation()
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'cleared' => 'Cleared',
                            'rejected' => 'Rejected',
                        ])
                ])
                ->action(function (Pac $record, array $data) {
                    return $record->update([
                        'status' => $data['status'],
                        'fulfilled_by' => auth()->id(),
                        'fulfilled_at' => now(),
                    ]);
                })
                ->visible(fn (Pac $record) => $record->status === 'pending'),
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
            'index' => Pages\ListPacs::route('/'),
            'create' => Pages\CreatePac::route('/create'),
            'edit' => Pages\EditPac::route('/{record}/edit'),
        ];
    }
}
