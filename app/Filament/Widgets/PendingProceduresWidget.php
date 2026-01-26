<?php

namespace App\Filament\Widgets;

use App\Models\Procedure;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingProceduresWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Procedure::where('status', 'pending')
            )
            ->columns([
                Tables\Columns\TextColumn::make('bed_number')->label('Bed No'),
                Tables\Columns\TextColumn::make('procedure_name')->label('Procedure'),
                Tables\Columns\TextColumn::make('addedBy.name')->label('Added By'),
                Tables\Columns\TextColumn::make('created_at')->label('Time')->dateTime(),
            ]);
    }
}
