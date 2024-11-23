@extends('app')

@section('title', 'Detail Booking')

@section('content')
<div class="container mx-auto my-8">
    <h3 class="text-2xl font-bold mb-5 text-center hidden sm:block">Detail Booking</h3>

    {{ $booking->ticket_number }}
</div>
@include('components.alert')
@endsection