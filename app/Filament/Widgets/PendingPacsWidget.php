<?php

namespace App\Filament\Widgets;

use App\Models\Pac;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingPacsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pac::where('status', 'pending')
            )
            ->columns([
                Tables\Columns\TextColumn::make('bed_number')->label('Bed No'),
                Tables\Columns\TextColumn::make('patient.name')->label('Patient'),
                Tables\Columns\TextColumn::make('addedBy.name')->label('Added By'),
                Tables\Columns\TextColumn::make('created_at')->label('Time')->dateTime(),
            ]);
    }
}
