<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BookingResource;
use App\Filament\Resources\BusResource;
use App\Filament\Resources\ScheduleResource;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\Schedule;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Booking', Booking::count())
                ->description('Klik untuk melihat')
                ->url(BookingResource::getUrl())
                ->icon('heroicon-o-ticket'),
            Stat::make('Total Bus', Bus::count())
                ->description('Klik untuk melihat')
                ->url(BusResource::getUrl())
                ->icon('heroicon-o-truck'),
            Stat::make('Total Jadwal Pemberangkatan', value: Schedule::count())
                ->description('Klik untuk melihat')
                ->url(ScheduleResource::getUrl())
                ->icon('heroicon-o-calendar-days'),
        ];
    }
}
