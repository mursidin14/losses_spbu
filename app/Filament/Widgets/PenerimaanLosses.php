<?php

namespace App\Filament\Widgets;

use App\Models\Penerimaan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PenerimaanLosses extends BaseWidget
{
    protected static ?string $heading = 'Losses Harian Penerimaan';
    protected static ?int $sort = 1; // Urutan tampil di dashboard

    // Query data yang ditampilkan
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Penerimaan::query()->latest('tanggal')
            )
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('susut_harian')
                    ->label('Susut Harian (L)')
                    ->numeric(2)
                    ->sortable(),
            ])
            ->defaultPaginationPageOption(10)
            ->filters([]);
    }
}
