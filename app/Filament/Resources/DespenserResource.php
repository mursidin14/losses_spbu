<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DespenserResource\Pages;
use App\Filament\Resources\DespenserResource\RelationManagers;
use App\Models\Despenser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DespenserResource extends Resource
{
    protected static ?string $model = Despenser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Forms\Components\TextInput::make('nozzle')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('meter_awal')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('meter_akhir')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product'),
                Tables\Columns\TextColumn::make('nozzle')
                    ->label('Nozzle'),
                Tables\Columns\TextColumn::make('meter_awal')
                    ->label('Meter Awal'),
                Tables\Columns\TextColumn::make('meter_akhir')
                    ->label('Meter Akhir'),
                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListDespensers::route('/'),
            'create' => Pages\CreateDespenser::route('/create'),
            'edit' => Pages\EditDespenser::route('/{record}/edit'),
        ];
    }
}
