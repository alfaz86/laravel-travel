<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Bus;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Jadwal';

    protected static ?string $breadcrumb = 'Jadwal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Bus')
                    ->schema([
                        Select::make('bus_id')
                            ->label('ID Bus')
                            ->live(onBlur: true)
                            ->options(Bus::all()->pluck('name', 'id'))
                            ->searchable()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $bus = Bus::find($state);
                                $set('available_seats', $bus->capacity);
                                $set('number_plate', $bus->number_plate);
                                $set('type', $bus->type);
                            })
                            ->required(),
                        TextInput::make('available_seats')
                            ->label('Kursi Tersedia')
                            ->readOnly()
                            ->visible( fn($get) => $get('available_seats') !== null),
                        TextInput::make('number_plate')
                            ->label('Plat Nomor')
                            ->readOnly()
                            ->visible( fn($get) => $get('number_plate') !== null),
                        TextInput::make('type')
                            ->label('Jenis')
                            ->readOnly()
                            ->visible( fn($get) => $get('type') !== null),
                    ]),
                TimePicker::make('departure_time')
                    ->label('Waktu Keberangkatan')
                    ->required(),
                TimePicker::make('arrive_time')
                    ->label('Waktu Tiba')
                    ->required(),
                Select::make('origin_id')
                    ->relationship(
                        name: 'origin', 
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query)=> $query->orderBy('id','asc'),
                    )
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Lokasi')
                            ->required(),
                        Forms\Components\TextInput::make('regency')
                            ->label('Kabupaten/Kota')
                            ->required(),
                    ])
                    ->label('Asal')
                    ->required(),
                Select::make('destination_id')
                    ->relationship(
                        name: 'destination', 
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query)=> $query->orderBy('id','asc'),
                    )
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Lokasi')
                            ->required(),
                        Forms\Components\TextInput::make('regency')
                            ->label('Kabupaten/Kota')
                            ->required(),
                    ])
                    ->label('Tujuan')
                    ->required(),
                    
                TextInput::make('price')
                    ->label('Harga')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->required(),

                Section::make('Operasional')
                    ->schema([
                        CheckboxList::make('active_days')
                            ->label('')
                            ->options([
                                'Monday' => 'Senin',
                                'Tuesday' => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday' => 'Kamis',
                                'Friday' => 'Jumat',
                                'Saturday' => 'Sabtu',
                                'Sunday' => 'Minggu',
                            ])
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bus.name')
                    ->label('Nama Bus')
                    ->url(fn (Schedule $schedule) => "/admin/buses/{$schedule->bus->id}/edit"),
                TextColumn::make('departure_time')
                    ->label('Waktu Keberangkatan'),
                TextColumn::make('available_seats')
                    ->label('Kursi Tersedia'),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', 0, 'id-ID'),
                TextColumn::make('origin.name')
                    ->label('Asal'),
                TextColumn::make('destination.name')
                    ->label('Tujuan'),
                ViewColumn::make('active_days')
                    ->label('Operasional')
                    ->view('filament.tables.columns.active_days_badge')
                
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
