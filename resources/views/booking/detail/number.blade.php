@extends('app')

@section('title', 'Detail Booking')

@section('content')
<div class="container mx-auto my-8">
    <h3 class="text-3xl font-bold mb-6 text-center text-gray-800 hidden sm:block">Detail Booking</h3>
    
    <div class="main-card bg-white shadow-md rounded-lg overflow-hidden">
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
                    <tr 
                        @if ($booking->payment_status === \App\Models\Booking::STATUS_PENDING)
                            class="border-b"
                        @endif
                    >
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
                    @if ($booking->payment_status === \App\Models\Booking::STATUS_PENDING)
                        <tr>
                            <td class="py-2 px-4 font-medium">Sisa Waktu Pembayaran</td>
                            <td class="py-2 px-4">
                                <span id="countdown" ></span>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if ($booking->payment_status === \App\Models\Booking::STATUS_PENDING)
            <div class="bg-gray-100 px-6 py-4 flex justify-between sm:justify-start">
                <!-- Tombol Batalkan Booking -->
                <button type="button" id="cancelBookingButton" class="w-full sm:w-auto bg-red-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-700 transition duration-300">
                    Cancel
                </button>
                
                <!-- Tombol Lanjutkan Pembayaran -->
                <button type="button" id="bookingButton" class="w-full sm:w-auto bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition duration-300 ml-4">
                    Bayar
                </button>
            </div>
        @endif
    </div>

    @if ($booking->payment_status === \App\Models\Booking::STATUS_PAID)
        <div class="mt-8">
            <h3 class="text-2xl font-bold mb-6 text-center text-gray-800">Daftar Tiket</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach ($booking->tickets as $ticket)
                    <div class="main-card bg-white shadow-md rounded-lg overflow-hidden">
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
<script>
    const countdown = document.getElementById('countdown');
    const booking = @json($booking);
    let remainingTime = new Date(booking.created_at).getTime();

    if (countdown) {
        const countDownTime = new Date(booking.created_at).getTime() + 15 * 60 * 1000;

        const x = setInterval(function() {
            const now = new Date().getTime();
            const distance = countDownTime - now;
            remainingTime = distance;

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdown.innerHTML = `${minutes} Menit ${seconds} Detik`;
            if (distance < 0) {
                clearInterval(x);
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        });
    }
</script>
@endpush

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    const bookingButton = document.getElementById('bookingButton');
    const cancelBookingButton = document.getElementById('cancelBookingButton');
    const loadingOverlay = document.getElementById('loading-overlay');

    cancelBookingButton?.addEventListener('click', function () {
        if (document.getElementById('toast-container').children.length === 0) {
            showToast('question', 'Apakah Anda yakin ingin membatalkan booking ini?', 10000);
        }
        
        // cek jika toast muncul
        $('#confirm').off('click').on('click', function() {
            console.log('Booking dibatalkan');
            hideToast()

            // cancel payment
            fetch(`/api/payment/cancel/{{ $booking->booking_number }}`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(async (data) => {
                if (data.status === 'success') {
                    await showToastWithRedirect(
                        'success', 
                        'Booking berhasil dibatalkan!', 
                        2000,
                        () => {
                            window.location.reload();
                        }
                    );
                } else {
                    showToast('warning', data.message || 'Terjadi kesalahan saat membatalkan pembayaran.', 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Terjadi kesalahan dalam komunikasi dengan server.', 3000);
            });
        });
    });

    bookingButton?.addEventListener('click', function () {
        // Tampilkan loading overlay
        loadingOverlay.classList.remove('hidden');

        fetch(`/api/payment/pay/{{ $booking->booking_number }}`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                remainingTime: Math.floor(remainingTime / 1000)
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);

            if (data.status === 'success') {
                const snapToken = data.data.snapToken;
                snap.pay(snapToken, {
                    onSuccess: function () {
                        showToast('success', 'Pembayaran berhasil!', 3000);
                        window.location.reload();
                    },
                    onPending: function () {
                        showToast('warning', 'Pembayaran ditunda!', 3000);
                        window.location.reload();
                    },
                    onError: function () {
                        showToast('warning', 'Pembayaran gagal!', 3000);
                        window.location.reload();
                    },
                    onClose: function () {
                        console.log('Customer closed the popup without finishing the payment');
                    }
                });
            } else {
                showToast('warning', data.message || 'Terjadi kesalahan saat memproses pembayaran.', 3000);
                console.error(data.errors);
                window.location.reload();
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
