<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NozzleResource\Pages;
use App\Filament\Resources\NozzleResource\RelationManagers;
use App\Models\Nozzle;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NozzleResource extends Resource
{
    protected static ?string $model = Nozzle::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-turn-up-right';

    public static ?string $navigationGroup = 'Operator';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'supervisor', 'operator']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('product_id')
                ->relationship('product', 'name')
                ->label('Produk')
                ->required(),

            Forms\Components\Select::make('shift_id')
                ->relationship('shift', 'name')
                ->label('Shift')
                ->required(),

            Forms\Components\DatePicker::make('tanggal')
                ->label('Tanggal')
                ->required(),

            Forms\Components\TextInput::make('name')
                ->label('Nama Operator')
                ->required(),

            Forms\Components\TextInput::make('nozzle')
                ->label('Nozzle')
                ->required(),

            Forms\Components\TextInput::make('meter_awal')
                ->numeric()
                ->required()
                ->label('Meter Awal'),

            Forms\Components\TextInput::make('meter_akhir')
                ->numeric()
                ->required()
                ->label('Meter Akhir'),

            Forms\Components\TimePicker::make('waktu_masuk')
                ->label('Waktu Masuk')
                ->required()
                ->seconds(false)
                ->format('H:i') 
                ->default(now()->format('H:i')),

            Forms\Components\TimePicker::make('waktu_selesai')
                ->label('Waktu Selesai')
                ->required()
                ->seconds(false)
                ->format('H:i') 
                ->default(now()->format('H:i')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\TextColumn::make('shift.name')->label('Shift')->searchable(),
                Tables\Columns\TextColumn::make('tanggal')->date()->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Nama Operator')->searchable(),
                Tables\Columns\TextColumn::make('nozzle'),
                Tables\Columns\TextColumn::make('meter_awal'),
                Tables\Columns\TextColumn::make('meter_akhir'),
                Tables\Columns\TextColumn::make('waktu_masuk'),
                Tables\Columns\TextColumn::make('waktu_selesai'),
                Tables\Columns\TextColumn::make('jumlah'),
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
            'index' => Pages\ListNozzles::route('/'),
            'create' => Pages\CreateNozzle::route('/create'),
            // 'edit' => Pages\EditNozzle::route('/{record}/edit'),
        ];
    }
}
