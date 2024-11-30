@extends('app')

@section('title', 'Detail Booking')

@section('content')
<div class="container mx-auto my-8">
    <h3 class="text-2xl font-bold mb-5 text-center hidden sm:block">Detail Booking</h3>

    <!-- Table Wrapper -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse border border-gray-300">
            <tbody>
                <tr>
                    <th colspan="2" class="border border-gray-300 px-4 py-2">Jadwal Tiket</th>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2 font-semibold">Bus</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->bus->name }}</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2 font-semibold">Waktu Keberangkatan</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->departure_time }}</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2 font-semibold">Asal</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->origin->name }}</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2 font-semibold">Tujuan</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $schedule->destination->name }}</td>
                </tr>
                <tr>
                    <th colspan="2" class="border border-gray-300 px-4 py-2">Pembayaran</th>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2 font-semibold">Harga</td>
                    <td class="border border-gray-300 px-4 py-2">IDR {{ number_format($schedule->price, 0, ',', '.') }}</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2 font-semibold">Jumlah Tiket</td>
                    <td class="border border-gray-300 px-4 py-2" id="passengers"></td>
                </tr>
                <tr class="bg-gray-50 font-bold">
                    <td class="border border-gray-300 px-4 py-2" colspan="1">Total</td>
                    <td class="border border-gray-300 px-4 py-2" id="total-price"></td>
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

    <button type="button" id="booking" class="w-full bg-indigo-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700">
        Pesan
    </button>
</div>

@include('components.alert')
@endsection

@push('scripts')
<script>
    $(function () {
        const authUser = getAuthUser();
        const bookingButton = document.getElementById('booking');
        const isUserCheckbox = document.getElementById('is_user');
        const nameInput = document.getElementById('name');
        const phoneInput = document.getElementById('phone');
        const schedule = @json($schedule);
        const searchSchedule = @json(Cache::get('schedules_' . session()->getId()));
        const passengers = document.getElementById('passengers');
        const totalPrice = document.getElementById('total-price');
        const total_price = schedule.price * searchSchedule.passengers;

        passengers.textContent = searchSchedule.passengers;
        totalPrice.textContent = `IDR ${total_price.toLocaleString('id-ID')}`;
        
        $('#is_user').on('change', function () {
            if ($(this).is(':checked')) {
                $('#name').val(authUser.name);
                $('#phone').val(authUser.phone);
            } else {
                $('#name').val('');
                $('#phone').val('');
            }
        });
    
        bookingButton.addEventListener('click', async () => {
            const token = localStorage.getItem('jwt_token');
            const isUser = isUserCheckbox.checked;
            const name = nameInput.value;
            const phone = phoneInput.value;
    
            if (!isUser && (!name || !phone)) {
                return alert('Nama dan Nomor Telepon harus diisi');
            }
    
            const response = await fetch('{{ route('booking.create') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify({
                    passenger_name: name,
                    passenger_phone: phone,
                    quantity: searchSchedule.passengers,
                    schedule_id: schedule.id,
                    total_price,
                    booking_date: searchSchedule.date
                })
            });
    
            const data = await response.json();
    
            if (response.ok) {
                window.location.href = data.data.redirect;
            } else {
                alert(data.message);
            }
        });
    });
</script>
@endpush
