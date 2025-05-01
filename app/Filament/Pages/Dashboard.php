<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsWidget;
use App\Filament\Widgets\WelcomeWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            WelcomeWidget::class,
            StatsWidget::class,
        ];
    }
}
