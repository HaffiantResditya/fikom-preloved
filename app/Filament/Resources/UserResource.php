<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Manajemen User';
    protected static ?string $modelLabel = 'Pengguna';
    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-users';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(100)->label('Nama'),
            Forms\Components\TextInput::make('email')->email()->required()->maxLength(100),
            Forms\Components\TextInput::make('whatsapp_number')->label('Nomor WhatsApp')->maxLength(20),
            Forms\Components\Select::make('role')
                ->label('Role')
                ->options(['admin' => 'Admin', 'student' => 'Mahasiswa'])
                ->required(),
            Forms\Components\Select::make('account_status')
                ->label('Status Akun')
                ->options([
                    'pending'  => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('Nama'),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('whatsapp_number')->label('WhatsApp'),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'admin' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('account_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'pending'  => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default    => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y')->label('Terdaftar'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('account_status')
                    ->label('Status Akun')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->action(fn(User $record) => $record->update(['account_status' => 'approved']))
                    ->visible(fn(User $record) => $record->account_status !== 'approved'),
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->action(fn(User $record) => $record->update(['account_status' => 'rejected']))
                    ->visible(fn(User $record) => $record->account_status !== 'rejected'),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit'  => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
