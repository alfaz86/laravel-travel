<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_number')
                    ->label('No Booking')
                    ->searchable('booking_number'),
                TextColumn::make('booking_date')
                    ->label('Tanggal Booking')
                    ->searchable('booking_date'),
                TextColumn::make('passenger_name')
                    ->label('Nama Penumpang')
                    ->searchable('passenger_name'),
                TextColumn::make('passenger_phone')
                    ->label('Nomer Telepon')
                    ->searchable('passenger_phone'),
                TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR', 0, 'id-ID'),
                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->view('filament.tables.columns.status_badge'),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->options(Booking::STATUS_OPTIONS)
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListBookings::route('/'),
            // 'create' => Pages\CreateBooking::route('/create'),
            // 'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
