<?php

namespace App\Filament\Resources\PacResource\Pages;

use App\Filament\Resources\PacResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPac extends EditRecord
{
    protected static string $resource = PacResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
