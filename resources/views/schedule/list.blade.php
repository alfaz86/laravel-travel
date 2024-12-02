@extends('app')

@section('title', 'Pilih Tiket')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-center mb-5 hidden sm:block">Pilih Tiket</h1>

    <!-- Search Result -->
    <div class="flex justify-between items-center mb-5 px-5 py-3 search-result">
        <div>
            <h2 class="text-xl font-bold">Hasil Pencarian</h2>
            <p class="text-gray-600">{{ $date->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
            <p class="text-gray-600">Rute <b>{{ $origin['name'] }} - {{ $destination['name'] }}</b></p>
            <p class="text-gray-600">Untuk <b>{{ $passengers }}</b> orang.</p>
            <form method="GET" action="{{ route('home') }}">
                <input type="hidden" name="change-search" value="true">
                <button type="submit" class="text-indigo-500 underline hover:text-indigo-700">Ubah Pencarian</button>
            </form>
        </div>
        <!-- Continue Button -->
        <div>
            <button id="checkoutButton" type="button" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline w-full flex items-center justify-center">
                <span id="checkoutButtonSpinner" class="hidden spinner mr-2"></span>
                Lanjutkan
            </button>
        </div>
    </div>
    
    @if ($schedules->count() > 0)
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 mb-10">
        @foreach ($schedules as $schedule)
            <div class="card card-ticket bg-indigo-50 shadow-lg shadow-indigo-300/50 rounded-lg p-4" data-schedule-id="{{ $schedule['id'] }}">
                <div class="ticket-card-header mb-3 flex justify-between">
                    <div class="date">
                        <span class="date-day">{{ $date->format('d') }}</span>
                        <span class="date-month-year">{{ $date->format('M') }} '{{ $date->format('y') }}</span>
                    </div>
                    <div class="price">
                        IDR {{ number_format(floor($schedule['price']), 0, ',', '.') }}
                        <span class="small-font">{{ substr(number_format($schedule['price'], 2), -2) }}</span>
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
                <div class="text-end mt-3">
                    @if ($schedule['remaining_seats'] == 0)
                        <span class="bg-red-600 text-white text-xs font-bold py-1 px-2 rounded-sm">Habis</span>
                    @else
                        <span class="bg-green-600 text-white text-xs font-bold py-1 px-2 rounded-sm">Tersedia {{ $schedule['remaining_seats'] }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="flex justify-center">
        <h3 class="text-lg text-gray-600 my-5">Tidak ada jadwal tersedia</h3>
    @endif
</div>

<form id="scheduleForm" method="POST" action="{{ route('schedule.select-ticket') }}">
    @csrf
    <input type="hidden" name="schedule_id" id="scheduleIdInput">
</form>

@include('components.alert')
@endsection

@push('scripts')
<script>
    let selectedSchedule = null;

    $('.card-ticket').on('click', function() {
        let scheduleId = $(this).data('schedule-id');
        let card = $(this);
        let cardDash = $(this).find('.ticket-dash');
        let cardDashLine = $(this).find('.dash-line');
        let cardDot = $(this).find('.dot');

        // Deselect if already selected
        if (selectedSchedule === scheduleId) {
            selectedSchedule = null;
            card.removeClass('selected');
            cardDash.removeClass('darker');
            cardDashLine.removeClass('darker');
            cardDot.removeClass('darker');
        } else {
            selectedSchedule = scheduleId;
            $('.card-ticket').removeClass('selected');
            $('.ticket-dash').removeClass('darker');
            $('.dash-line').removeClass('darker');
            $('.dot').removeClass('darker');
            card.addClass('selected');
            cardDash.addClass('darker');
            cardDashLine.addClass('darker');
            cardDot.addClass('darker');
        }
    });

    $('#checkoutButton').on('click', async function() {
        setButtonLoading('checkoutButton', true);

        try {
            if (!selectedSchedule) {
                showToast('warning', 'Pilih jadwal untuk melanjutkan.');
                return;
            }

            const isLoggedIn = checkLoginStatus();
            if (!isLoggedIn) {
                await showToastWithRedirect(
                    'warning',
                    'Silakan login terlebih dahulu untuk melanjutkan.',
                    2000,
                    () => {
                        const redirectParams = {
                            w: ['redirect', 'callFunction'],
                            d: {
                                redirect: '/booking/detail',
                                callFunctionName: 'selectTicket',
                                callFunctionParams: [selectedSchedule],
                            },
                        };
                        const encodedParams = btoa(JSON.stringify(redirectParams));
                        window.location.href = `/auth/login?d=${encodedParams}`;
                    }
                );
                return;
            }

            await selectTicket(selectedSchedule);
        } catch (error) {
            console.error('Error:', error);
            showToast('warning', 'Terjadi kesalahan dalam komunikasi dengan server.');
        } finally {
            setButtonLoading('checkoutButton', false);
        }
    });
</script>
@endpush