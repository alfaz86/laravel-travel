@extends('app')

@section('content')
<div class="container mx-auto">
    <!-- Jumbotron -->
    <div class="bg-indigo-50 shadow-lg shadow-indigo-300/50 rounded-lg overflow-hidden mb-8">
        <div class="bg-cover bg-center h-64" style="background-image: url('/images/cloudart.jpg');">
            <div class="md:p-8 p-6 h-full flex flex-col justify-end">
                <h1 class="text-white text-4xl font-bold mb-2">Selamat datang di situs perjalanan kami</h1>
                <p class="text-gray-200 text-lg">Temukan penawaran terbaik dan mulailah perjalanan Anda bersama kami!</p>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    @include('schedule.search')
</div>

@include('components.alert')
@endsection