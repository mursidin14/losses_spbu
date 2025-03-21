<?php

namespace App\Filament\Resources\NozzleResource\Pages;

use App\Filament\Resources\NozzleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNozzle extends EditRecord
{
    protected static string $resource = NozzleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
