<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;

class PusherSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Pusher';
    protected static ?string $slug = 'settings/pusher';
    protected static string $view = 'filament.pages.pusher-settings';
    protected static array|string $routeMiddleware = ['role:dev'];

    public $pusher_app_id;
    public $pusher_app_key;
    public $pusher_app_secret;
    public $app_pusher_setting;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user && $user->role === 'dev';
    }

    public function mount()
    {
        $this->pusher_app_id = env('PUSHER_APP_ID');
        $this->pusher_app_key = env('PUSHER_APP_KEY');
        $this->pusher_app_secret = env('PUSHER_APP_SECRET');
        $this->app_pusher_setting = env('APP_PUSHER_SETTING', false);
    }

    public function save()
    {
        $this->updateEnv([
            'PUSHER_APP_ID' => $this->pusher_app_id,
            'PUSHER_APP_KEY' => $this->pusher_app_key,
            'PUSHER_APP_SECRET' => $this->pusher_app_secret,
            'APP_PUSHER_SETTING' => $this->app_pusher_setting ? 'true' : 'false',
        ]);

        // Reload konfigurasi aplikasi
        Artisan::call('config:clear');

        Notification::make()
            ->title('Success')
            ->body('Pusher settings updated successfully!')
            ->success()
            ->send();
    }

    protected function updateEnv(array $data)
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            return;
        }

        $env = file_get_contents($path);
        foreach ($data as $key => $value) {
            // Escape special characters for regex
            $escapedValue = preg_quote($value, '/');
            $env = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$escapedValue}",
                $env
            );
        }

        file_put_contents($path, $env);
    }
}
