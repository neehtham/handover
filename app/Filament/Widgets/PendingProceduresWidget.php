<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ProcedureResource;
use App\Models\Procedure;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingProceduresWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Procedure::where('status', 'pending')
            )
            ->headerActions([
                CreateAction::make('create')
                    ->label('Add Procedure Request')
                    ->schema([
                        TextInput::make('patient_id')
                            ->label('Patient ID'),
                        TextInput::make('patient_name')
                            ->label('Patient Name'),
                        TextInput::make('bed_number')
                            ->label('Bed Number')
                            ->required(),
                        TextInput::make('procedure_name')
                            ->label('Procedure Name')
                            ->required(),
                        Textarea::make('remarks')
                            ->label('Remarks')
                            ->rows(3),
                    ])
                    ->action(function (array $data) {
                        Procedure::create($data);
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('bed_number')->label('Bed No'),
                Tables\Columns\TextColumn::make('patient_id')->label('Patient ID'),
                Tables\Columns\TextColumn::make('patient_name')->label('Patient'),
                Tables\Columns\TextColumn::make('procedure_name')->label('Procedure'),
                Tables\Columns\TextColumn::make('addedBy.name')->label('Added By'),

                Tables\Columns\TextColumn::make('created_at')->label('Time')->dateTime(),
            ])
            ->recordActions(ProcedureResource::fulfill());
    }
}
