<?php

namespace App\Providers;

use App\Jobs\CustomExportCompletion;
use Filament\Actions\Exports\Jobs\ExportCompletion;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ExportCompletion::class, CustomExportCompletion::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Export::updated(function (Export $export) {
            if ($export->completed_at === null || !$export->file_name || $export->file_data !== null) {
                return;
            }

            $disk = $export->file_disk;
            $filePath = $export->file_name;
            $absolutePath = Storage::disk($disk)->path('filament_exports/' . $export->id . '/' . $filePath . '.xlsx');

            if (file_exists($absolutePath)) {
                $export->file_data = file_get_contents($absolutePath);
                $export->save();
                Log::info('File Data Disimpan', ['export_id' => $export->id]);

                // Sending notifications
                $recipient = auth()->user();

                Notification::make()
                    ->title('File Export Selesai')
                    ->body('File export Anda telah selesai diproses dan siap diunduh.')
                    ->icon('heroicon-o-check-circle')
                    ->iconColor('success')
                    ->actions([
                        Action::make('download_xlsx')
                            ->label('Unduh File')
                            ->url(route('buses.export', [
                                'export' => $export->id,
                                'type' => 'notification',
                            ]))
                            ->icon('heroicon-o-arrow-down-tray')
                            ->color('primary'),
                    ])
                    ->sendToDatabase($recipient, isEventDispatched: false);
                Log::info('Notifikasi Terkirim', ['export_id' => $export->id]);
            } else {
                Log::warning('File Tidak Ditemukan', ['path' => $absolutePath]);
            }
        });
    }
}
