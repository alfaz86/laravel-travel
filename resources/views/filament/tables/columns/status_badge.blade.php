@php
    $status = $getRecord()->payment_status;
@endphp
@if ($status === \App\Models\Booking::STATUS_PENDING)
    <x-filament::badge color="warning">
        {{ Str::upper($status) }}
    </x-filament::badge>
@elseif($status === \App\Models\Booking::STATUS_PAID)
    <x-filament::badge color="success">
        {{ Str::upper($status) }}
    </x-filament::badge>
@else
    <x-filament::badge color="danger">
        {{ Str::upper($status) }}
    </x-filament::badge>
@endif