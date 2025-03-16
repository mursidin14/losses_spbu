<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Filament\Resources\ReportResource\RelationManagers;
use App\Models\Product;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('product_id')
                ->relationship('product', 'name')
                ->required(),

            Forms\Components\DatePicker::make('tanggal')
                ->default(now())
                ->required(),

            Forms\Components\TextInput::make('stok_awal')
                ->label('Stok Awal')
                ->required(),

            Forms\Components\TextInput::make('no_tangki')
                ->label('No.Tangki')
                ->required(),

            Forms\Components\TextInput::make('no_pnbp')
                ->label('No.PNBP')
                ->required(),

            Forms\Components\TextInput::make('vol_sebelum_penerimaan')
                ->label('Vol Sebelum Penerimaan')
                ->required()
                ->numeric(),

            Forms\Components\TextInput::make('vol_penerimaan_pnbp')
                ->label('Vol Penerimaan PNBP')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('vol_penerimaan_aktual')
                ->label('Vol Penerimaan Aktual')
                ->numeric()
                ->required(),

                Forms\Components\TextInput::make('pengeluaran')
                ->label('Pengeluaran')
                ->numeric()
                ->default(function ($record) {
                    if ($record) {
                        $despenserJumlah = $record->despenser()->sum('jumlah');
                        return $despenserJumlah > 0 ? $despenserJumlah : $record->pengeluaran;
                    }
                    return 0;
                })
                ->reactive()
                ->afterStateHydrated(function ($state, $set, $record) {
                    if ($record) {
                        $despenserJumlah = $record->despenser()->sum('jumlah');
                        if ($despenserJumlah > 0) {
                            $set('pengeluaran', $despenserJumlah);
                        }
                    }
                }),

            Forms\Components\TextInput::make('stok_aktual')
                ->label('Stok Aktual')
                ->numeric()
                ->required(),

          ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\TextColumn::make('tanggal')->sortable(),
                Tables\Columns\TextColumn::make('stok_awal')->label('Stok Awal'),
                Tables\Columns\TextColumn::make('no_tangki')->label('No.Tangki'),
                Tables\Columns\TextColumn::make('no_pnbp')->label('No.PNBP'),
                Tables\Columns\TextColumn::make('vol_sebelum_penerimaan')->label('Vol Sebelum Peneriamaan'),
                Tables\Columns\TextColumn::make('vol_penerimaan_pnbp')->label('Vol Penerimaan PNBP'),
                Tables\Columns\TextColumn::make('vol_penerimaan_aktual')->label('Vol Penerimaan Aktual'),
                Tables\Columns\TextColumn::make('susut_tangki')->label('Susut Tangki'),
                Tables\Columns\TextColumn::make('pengeluaran')->label('Pengeluaran'),
                Tables\Columns\TextColumn::make('stok_teoritis'),
                Tables\Columns\TextColumn::make('stok_aktual'),
                Tables\Columns\TextColumn::make('susut_pengeluaran')->label('Susut Pengeluaran'),
            ])
            ->filters([
                SelectFilter::make('product_id')
                ->label('Filter Produk')
                ->options(Product::all()->pluck('name', 'id'))
                ->searchable()
                ->placeholder('Semua Produk'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
