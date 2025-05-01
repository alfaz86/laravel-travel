<?php

namespace App\Filament\Imports;

use App\Models\Bus;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;

class BusImporter extends Importer
{
    protected static ?string $model = Bus::class;

    public static function getColumns(): array
    {
        // variable numberplate with generated like "Z 0001 AB"
        $numberplate = 'Z ' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) . ' AB';

        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->exampleHeader('Nama Bus')
                ->example('BUS 001'),
            ImportColumn::make('number_plate')
                ->rules(['max:255'])
                ->exampleHeader('Plat Nomor')
                ->example($numberplate),
            ImportColumn::make('type')
                ->rules(['max:255'])
                ->exampleHeader('Jenis')
                ->example('BUS AKDP'),
            ImportColumn::make('capacity')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer'])
                ->exampleHeader('Kapasitas')
                ->example('40'),
        ];
    }

    public function resolveRecord(): ?Bus
    {
        return Bus::firstOrNew([
            'number_plate' => $this->data['number_plate'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your bus import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label('Update existing records'),
        ];
    }
}
