<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ChronicRoundsAlertWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Chronic Patients Pending Rounds Today';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Patient::query()
                    ->where('type', 'chronic')
                    ->where('is_discharged', false)
                    ->whereDoesntHave('chronicRounds', function (Builder $query) {
                        $query->whereDate('created_at', today());
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('bed_number')->label('Bed No'),
                Tables\Columns\TextColumn::make('name')->label('Patient'),
                Tables\Columns\TextColumn::make('updated_at')->label('Last Update')->dateTime(),
            ]);
    }
}
