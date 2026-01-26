<?php

namespace App\Filament\Widgets;

use App\Models\PostOpRequest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingPostOpWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PostOpRequest::where('status', 'requested')
            )
            ->columns([
                Tables\Columns\TextColumn::make('bed_number')->label('Bed No'),
                Tables\Columns\TextColumn::make('patient.name')->label('Patient'),
                Tables\Columns\TextColumn::make('advice')->limit(50),
                Tables\Columns\TextColumn::make('addedBy.name')->label('Added By'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ]);
    }
}
