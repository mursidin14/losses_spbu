<?php

namespace App\Filament\Resources\NozzleResource\Pages;

use App\Filament\Resources\NozzleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNozzles extends ListRecords
{
    protected static string $resource = NozzleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
