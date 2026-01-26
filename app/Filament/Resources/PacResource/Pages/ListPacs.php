<?php

namespace App\Filament\Resources\PacResource\Pages;

use App\Filament\Resources\PacResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPacs extends ListRecords
{
    protected static string $resource = PacResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
