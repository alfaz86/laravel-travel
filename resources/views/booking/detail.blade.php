@extends('app')

@section('title', 'Detail Booking')

@section('content')
<div class="container mx-auto my-8">
    <h3 class="text-2xl font-bold mb-5 text-center hidden sm:block">Detail Booking</h3>

    <!-- Table Wrapper -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">Bus</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Waktu Keberangkatan</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Asal</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Tujuan</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules as $schedule)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->bus->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->departure_time }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->origin->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->destination->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">IDR {{ number_format($schedule->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="bg-gray-50 font-bold">
                    <td class="border border-gray-300 px-4 py-2" colspan="4">Total</td>
                    <td class="border border-gray-300 px-4 py-2">IDR {{ number_format($schedules->sum('price'), 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Passenger Data -->
    <div class="card bg-white shadow-md rounded-lg p-6 mb-5 mt-3">
        <div class="card-body">
            <form action="#" method="post" class="space-y-4">
                @csrf
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="is_user" id="is_user" class="form-checkbox text-indigo-600">
                    <label for="is_user" class="text-gray-700">Penumpang adalah pemesan tiket</label>
                </div>
                <div class="form-group">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Penumpang</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div class="form-group">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
            </form>
        </div>
    </div>

    <button type="button" class="w-full bg-indigo-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700">
        Pesan
    </button>
</div>

@include('components.alert')
@endsection

@section('scripts')
<script>
    // Placeholder for future scripts
</script>
@endsection
