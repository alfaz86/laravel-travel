<?php

namespace App\Filament\Resources;

use App\Filament\Exports\BusExporter;
use App\Filament\Imports\BusImporter;
use App\Filament\Resources\BusResource\Pages;
use App\Filament\Resources\BusResource\RelationManagers;
use App\Models\Bus;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BusResource extends Resource
{
    protected static ?string $model = Bus::class;

    protected static ?string $navigationIcon = 'heroicon-s-list-bullet';

    protected static ?string $navigationLabel = 'Daftar Bus';

    protected static ?string $breadcrumb = 'Bus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Bus')
                    ->required(),
                TextInput::make('number_plate')
                    ->label('Plat Nomor'),
                TextInput::make('type')
                    ->label('Jenis'),
                TextInput::make('capacity')
                    ->label('Kapasitas')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Bus')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('number_plate')
                    ->label('Plat Nomor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Jenis')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->searchable()
                    ->sortable(),
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
            ])
            ->headerActions([
                Tables\Actions\ImportAction::make('Import Bus')
                    ->importer(BusImporter::class),
                Tables\Actions\ExportAction::make('Export Bus')
                    ->exporter(BusExporter::class)
                    ->fileName('data-bus-' . now()->timestamp)
                    ->label('Ekspor bus')
                    ->color('primary')
                    ->formats([
                        ExportFormat::Xlsx,
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
            'index' => Pages\ListBuses::route('/'),
            'create' => Pages\CreateBus::route('/create'),
            'edit' => Pages\EditBus::route('/{record}/edit'),
        ];
    }

    public function export(Export $export)
    {
        $user = auth()->user();
        $targetUrl = route('buses.export', [
            'export' => $export->id,
            'type' => 'notification',
        ]);

        $notification = $user->unreadNotifications
            ->filter(function ($notification) use ($targetUrl) {
                $data = $notification->data;

                if (is_string($data)) {
                    $data = json_decode($data, true);
                }

                return isset($data['actions'][0]['url']) && $data['actions'][0]['url'] === $targetUrl;
            })
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }

        if ($export->file_data) {
            return response($export->file_data, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="data-laporan-' . $export->id . '.xlsx"',
            ]);
        }

        abort(404, 'File not found.');
    }
}
