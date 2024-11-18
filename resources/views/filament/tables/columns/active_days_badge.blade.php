@php
    $activeDays = is_array($getRecord()->active_days) ? $getRecord()->active_days : json_decode($getRecord()->active_days, true) ?? [];
    $daysOfWeek = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu'
    ];
    @endphp

<div class="flex flex-wrap space-x-2 gap-1 py-1" style="max-width: 270px !important; min-width: 110px !important;">
    @foreach ($daysOfWeek as $key => $day)
        <div class="w-1/12">
            @if (in_array($key, $activeDays))
                <x-filament::badge color="success">
                    {{ $day }}
                </x-filament::badge>
            @else
                <x-filament::badge color="danger">
                    {{ $day }}
                </x-filament::badge>
            @endif
        </div>
    @endforeach
</div>
