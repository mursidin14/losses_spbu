<?php

namespace App\Filament\Resources\DespenserResource\Pages;

use App\Filament\Resources\DespenserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDespenser extends EditRecord
{
    protected static string $resource = DespenserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
