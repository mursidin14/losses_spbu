<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenerimaanResource\Pages;
use App\Filament\Resources\PenerimaanResource\RelationManagers;
use App\Models\Penerimaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenerimaanResource extends Resource
{
    protected static ?string $model = Penerimaan::class;

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\TextColumn::make('tanggal')->sortable(),
                Tables\Columns\TextColumn::make('no_tangki')->label('No.Tangki'),
                Tables\Columns\TextColumn::make('no_pnbp')->label('No.PNBP'),
                Tables\Columns\TextColumn::make('vol_sebelum_penerimaan')->label('Vol Sebelum Peneriamaan'),
                Tables\Columns\TextColumn::make('vol_penerimaan_pnbp')->label('Vol Penerimaan PNBP'),
                Tables\Columns\TextColumn::make('vol_penerimaan_aktual')->label('Vol Penerimaan Aktual'),
                Tables\Columns\TextColumn::make('susut_tangki')->label('Susut Tangki'),
                Tables\Columns\TextColumn::make('susut_harian')->label('Susut Harian'),
                Tables\Columns\TextColumn::make('susut_bulanan')->label('Susut Bulanan'),
                Tables\Columns\TextColumn::make('susut_tahunan')->label('Susut Tahunan'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPenerimaans::route('/'),
            'create' => Pages\CreatePenerimaan::route('/create'),
            'edit' => Pages\EditPenerimaan::route('/{record}/edit'),
        ];
    }
}
