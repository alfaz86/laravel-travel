@extends('app')

@section('title', 'Detail Booking')

@section('content')
<div class="container mx-auto my-8">
    <h3 class="text-3xl font-bold mb-6 text-center text-gray-800 hidden sm:block">Detail Booking</h3>
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="py-6 px-3">
            <table class="table-auto w-full text-left text-gray-700 border-collapse">
                <tbody>
                    <tr class="border-b">
                        <td class="py-2 px-4 font-medium">Nomor Booking</td>
                        <td class="py-2 px-4">{{ $booking->booking_number }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4 font-medium">Nama Penumpang</td>
                        <td class="py-2 px-4">{{ $booking->passenger_name }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4 font-medium">Nomor Telepon</td>
                        <td class="py-2 px-4">{{ $booking->passenger_phone }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4 font-medium">Jumlah Tiket</td>
                        <td class="py-2 px-4">{{ $booking->quantity }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4 font-medium">Total Harga</td>
                        <td class="py-2 px-4">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 font-medium">Status Pembayaran</td>
                        <td class="py-2 px-4">
                            @if ($booking->payment_status === \App\Models\Booking::STATUS_PENDING)
                                <span class="bg-yellow-500 text-white text-xs font-bold py-1 px-2 rounded-full">
                                    {{ Str::upper($booking->payment_status) }}
                                </span>
                            @elseif ($booking->payment_status === \App\Models\Booking::STATUS_PAID)
                                <span class="bg-green-500 text-white text-xs font-bold py-1 px-2 rounded-full">
                                    {{ Str::upper($booking->payment_status) }}
                                </span>
                            @else
                                <span class="bg-red-500 text-white text-xs font-bold py-1 px-2 rounded-full">
                                    {{ Str::upper($booking->payment_status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @if ($booking->payment_status === \App\Models\Booking::STATUS_PENDING)
            <div class="bg-gray-100 px-6 py-4">
                <button type="button" id="booking" class="w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 transition duration-300">
                    Lanjutkan Pembayaran
                </button>
            </div>
        @endif
    </div>

    @if ($booking->payment_status === \App\Models\Booking::STATUS_PAID)
        <div class="mt-8">
            <h3 class="text-2xl font-bold mb-6 text-center text-gray-800">Daftar Tiket</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach ($booking->tickets as $ticket)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <div class="py-6 px-3">
                            <table class="table-auto w-full text-left text-gray-700 border-collapse">
                                <tbody>
                                    <tr class="border-b">
                                        <td class="py-2 px-4 font-medium">Nomor Tiket</td>
                                        <td class="py-2 px-4">{{ $ticket->ticket_number }}</td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2 px-4 font-medium">Status</td>
                                        <td class="py-2 px-4">
                                            @if ($ticket->status === \App\Models\Ticket::STATUS_NOT_USED)
                                                <span class="bg-yellow-500 text-white text-xs font-bold py-1 px-2 rounded-full">
                                                    {{ Str::upper($ticket->status) }}
                                                </span>
                                            @elseif ($ticket->status === \App\Models\Ticket::STATUS_USED)
                                                <span class="bg-green-500 text-white text-xs font-bold py-1 px-2 rounded-full">
                                                    {{ Str::upper($ticket->status) }}
                                                </span>
                                            @else
                                                <span class="bg-red-500 text-white text-xs font-bold py-1 px-2 rounded-full">
                                                    {{ Str::upper($ticket->status) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Share URL -->
                            <div class="mt-4 text-center">
                                <a 
                                    href="{{ url('/booking/detail/ticket/' . $ticket->ticket_number) }}" 
                                    class="text-indigo-600 font-bold hover:underline"
                                    target="_blank"
                                >
                                    Lihat Detail Tiket
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="text-white text-lg font-bold">Memproses pembayaran...</div>
</div>

@include('components.alert')
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    const bookingButton = document.getElementById('booking');
    const loadingOverlay = document.getElementById('loading-overlay');

    bookingButton?.addEventListener('click', function () {
        // Tampilkan loading overlay
        loadingOverlay.classList.remove('hidden');

        fetch(`/api/payment/pay/{{ $booking->booking_number }}`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);

            if (data.status === 'success') {
                const snapToken = data.data.snapToken;
                snap.pay(snapToken, {
                    onSuccess: function () {
                        alert('Pembayaran berhasil!');
                        window.location.href = '/api/payment/redirect';
                    },
                    onPending: function () {
                        alert('Pembayaran ditunda!');
                        window.location.href = '/api/payment/redirect';
                    },
                    onError: function () {
                        alert('Pembayaran gagal!');
                    },
                    onClose: function () {
                        console.log('Customer closed the popup without finishing the payment');
                    }
                });
            } else {
                alert(data.message || 'Terjadi kesalahan saat memproses pembayaran.');
                console.error(data.errors);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan dalam komunikasi dengan server.');
        })
        .finally(() => {
            // Sembunyikan loading overlay
            loadingOverlay.classList.add('hidden');
        });
    });
</script>
@endpush
