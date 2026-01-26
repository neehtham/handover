<?php

namespace App\Filament\Resources\PostOpRequestResource\Pages;

use App\Filament\Resources\PostOpRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPostOpRequest extends EditRecord
{
    protected static string $resource = PostOpRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
