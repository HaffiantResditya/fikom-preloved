<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationLabel = 'Manajemen Iklan';
    protected static ?string $modelLabel = 'Iklan Barang';
    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-tag';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pending'  => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'sold'     => 'Terjual',
                ])
                ->required(),
            Forms\Components\TextInput::make('title')
                ->disabled()
                ->dehydrated(false)
                ->label('Judul'),
            Forms\Components\Select::make('category_id')
                ->label('Kategori')
                ->options(Category::pluck('name', 'id'))
                ->disabled()
                ->dehydrated(false),
            Forms\Components\TextInput::make('price')
                ->disabled()
                ->dehydrated(false)
                ->prefix('Rp')
                ->label('Harga'),
            Forms\Components\Select::make('condition')
                ->label('Kondisi')
                ->options([
                    'Baru' => 'Baru',
                    'Bekas Seperti Baru' => 'Bekas Seperti Baru',
                    'Bekas Layak Pakai' => 'Bekas Layak Pakai',
                ])
                ->disabled()
                ->dehydrated(false),
            Forms\Components\Textarea::make('description')
                ->disabled()
                ->dehydrated(false)
                ->label('Deskripsi'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('primaryImage.image_path')
                    ->label('Foto')
                    ->disk('public')
                    ->square(),
                Tables\Columns\TextColumn::make('title')->searchable()->label('Judul')->limit(40),
                Tables\Columns\TextColumn::make('seller.name')->label('Penjual')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('condition')->label('Kondisi')->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'pending'  => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'sold'     => 'gray',
                        default    => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y')->label('Diposting'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'sold'     => 'Terjual',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->action(fn(Product $record) => $record->update(['status' => 'approved']))
                    ->visible(fn(Product $record) => $record->status === 'pending'),
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->action(fn(Product $record) => $record->update(['status' => 'rejected']))
                    ->visible(fn(Product $record) => $record->status === 'pending'),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'edit'  => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
