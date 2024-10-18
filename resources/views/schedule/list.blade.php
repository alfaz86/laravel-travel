@extends('app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-center mb-5">Pilih Jadwal</h1>
    @if ($schedules)
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($schedules as $schedule)
            <div class="card card-ticket bg-indigo-50 shadow-lg shadow-indigo-300/50 rounded-lg p-4" data-schedule-id="{{ $schedule['id'] }}">
                <div class="ticket-card-header mb-3 flex justify-between">
                    <div class="date">
                        <span class="date-day">{{ $date->format('d') }}</span>
                        <span class="date-month-year">{{ $date->format('M') }} '{{ $date->format('y') }}</span>
                    </div>
                    <div class="price">
                        IDR {{ number_format(floor($schedule['price'] / 1000)) }}
                        <span class="small-font">{{ str_pad($schedule['price'] % 1000, 3, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
                <div class="ticket-dash"></div>
                <div class="ticket-card-body">
                    <div class="departure">
                        <span class="label">Berangkat</span>
                        <span class="time">{{ $carbon::createFromFormat('H:i:s', $schedule['departure_time'])->format('H:i') }}</span>
                        <span class="location">{{ $schedule->origin['name'] }}</span>
                    </div>
                    <div class="interval">
                        <i class="location-icon left">
                            <svg class="w-[16px] h-[16px] text-gray-400 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z"/>
                            </svg>
                        </i>
                        <span class="interval-time">{{ $schedule->getIntervalTime() ?? '0j' }}</span>
                        <span class="dash-line"></span>
                        <span class="dot"></span>
                        <i class="location-icon right">
                            <svg class="w-[16px] h-[16px] text-gray-400 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.8 13.938h-.011a7 7 0 1 0-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155Z"/>
                            </svg>
                        </i>
                    </div>
                    <div class="arrival">
                        <span class="label">Tiba</span>
                        <span class="time">{{ $carbon::createFromFormat('H:i:s', $schedule['arrive_time'])->format('H:i') }}</span>
                        <span class="location">{{ $schedule->destination['name'] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else

    @endif
</div>

@include('components.alert')
@endsection

@section('scripts')
<script>
    $('.card-ticket').on('click', function() {
        let scheduleId = $(this).data('schedule-id');
        let url = "{{ route('schedule.select-ticket', ['schedule' => ':scheduleId']) }}";
        url = url.replace(':scheduleId', scheduleId);
        // window.location.href = url;
        console.log(url);
        
    });
</script>
@endsection