<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DespenserResource\Pages;
use App\Filament\Resources\DespenserResource\RelationManagers;
use App\Models\Despenser;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class DespenserResource extends Resource
{
    protected static ?string $model = Despenser::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    public static ?string $navigationGroup = 'Operator';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasRole(['admin', 'supervisor', 'operator']);
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
                
                Forms\Components\Textarea::make('name')
                    ->label('Nama Operator')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                    ->disabled()
                    ->dehydrated(false),

                Forms\Components\Textarea::make('nozzle')
                    ->label('No Nozzle')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                    ->disabled()
                    ->dehydrated(false),

                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required(),

                Forms\Components\TextInput::make('stok_awal')
                    ->numeric()
                    ->required()
                    ->label('Stok Awal'),

                Forms\Components\TextInput::make('stok_akhir')
                    ->numeric()
                    ->required()
                    ->label('Stok Akhir'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('shift.name')->label('Shift')->searchable(),
                Tables\Columns\TextColumn::make('tanggal')->date()->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Nama Operator'),
                Tables\Columns\TextColumn::make('nozzle'),

                Tables\Columns\TextColumn::make('stok_awal'),
                Tables\Columns\TextColumn::make('jumlah'),
                Tables\Columns\TextColumn::make('stok_teoritis'),
                Tables\Columns\TextColumn::make('stok_akhir'),

                Tables\Columns\TextColumn::make('susut_despenser'),
                Tables\Columns\TextColumn::make('susut_harian')
                ->label('Susut Harian')
                    ->formatStateUsing(fn ($state) => number_format($state, 2))
                    ->color(fn ($state) => $state < 0.50 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('susut_bulanan'),
                Tables\Columns\TextColumn::make('susut_tahunan'),
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
                Tables\Actions\Action::make('checkLoss')
    ->label('Periksa Losses')
    ->action(function ($record) {
            if ($record->susut_harian > 0.50) {
                Notification::make()
                    ->title('Susut Despenser Tinggi!')
                    ->body('Nilai susut despenser melebihi 0.5%')
                    ->danger()
                    ->send();
            } else {
                Notification::make()
                    ->title('Susut Aman')
                    ->body('Nilai susut despenser di bawah batas aman.')
                    ->success()
                    ->send();
            }
    })
    ->icon('heroicon-m-exclamation-triangle')
    ->color('danger'),
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
