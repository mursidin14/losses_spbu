<?php

namespace App\Filament\Resources\DespenserResource\Pages;

use App\Filament\Resources\DespenserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDespensers extends ListRecords
{
    protected static string $resource = DespenserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
