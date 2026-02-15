<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PacResource;
use App\Filament\Resources\ProcedureResource;
use App\Models\Pac;
use App\Models\Procedure;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingPacsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pac::where('status', 'pending')
            )
            ->headerActions([
                CreateAction::make('create')
                    ->label('Add PAC Request')
                    ->schema([
                        TextInput::make('patient_id')
                            ->label('Patient ID'),
                        TextInput::make('patient_name')
                            ->label('Patient Name'),
                        TextInput::make('bed_number')
                            ->label('Bed Number')
                            ->required(),
                        Textarea::make('remarks')
                            ->label('Remarks')
                            ->rows(3),
                    ])
                    ->action(function (array $data) {
                        Pac::create($data);
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('bed_number')->label('Bed No'),
                Tables\Columns\TextColumn::make('patient_name')->label('Patient'),
                Tables\Columns\TextColumn::make('addedBy.name')->label('Added By'),
                Tables\Columns\TextColumn::make('created_at')->label('Time')->dateTime(),
            ])
            ->recordActions(PacResource::fulfill());
    }
}
