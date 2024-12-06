<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;

class MidtransSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Midtrans';
    protected static ?string $slug = 'settings/midtrans';
    protected static string $view = 'filament.pages.midtrans-settings';
    protected static array|string $routeMiddleware = ['role:dev'];

    public $midtrans_environment;
    public $midtrans_server_key;
    public $midtrans_client_key;
    

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user && $user->role === 'dev';
    }

    public function mount()
    {
        $this->midtrans_environment = env('MIDTRANS_ENVIRONMENT', 'sandbox');
        $this->midtrans_server_key = env('MIDTRANS_SERVER_KEY');
        $this->midtrans_client_key = env('MIDTRANS_CLIENT_KEY');
    }

    public function save()
    {
        $this->updateEnv([
            'MIDTRANS_ENVIRONMENT' => $this->midtrans_environment,
            'MIDTRANS_SERVER_KEY' => $this->midtrans_server_key,
            'MIDTRANS_CLIENT_KEY' => $this->midtrans_client_key,
        ]);

        Artisan::call('config:clear');
        Notification::make()
            ->title('Success')
            ->body('Midtrans settings updated successfully!')
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
            $env = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $env
            );
        }

        file_put_contents($path, $env);
    }
}
