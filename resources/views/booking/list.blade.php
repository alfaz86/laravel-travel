@extends('app')

@section('title', 'List Booking')

@section('content')
<div class="container mx-auto my-8">
    <h3 class="text-2xl font-bold mb-5 text-center hidden sm:block">List Booking</h3>

    <div id="display-bookings"></div>
</div>
@include('components.alert')
@endsection

@push('scripts')
<script>
    $(async function () {
        const isLoggedIn = checkLoginStatus();
        if (!isLoggedIn) {
            await showToastWithRedirect(
                'warning',
                'Silakan login terlebih dahulu untuk melanjutkan.',
                2000,
                '/auth/login'
            );
        } else {
            fetch('/api/booking/list', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`
                },
            })
            .then(response => response.json())
            .then(data => {
                const apiResponse = data.data;
                const displayBookings = document.getElementById('display-bookings');

                apiResponse.forEach(booking => {
                    const bookingElement = document.createElement('div');
                    const bookingDetailUrl = '{{ route('booking.detail.number', ['bookingNumber' => ':bookingNumber']) }}'.replace(':bookingNumber', booking.booking_number);
                    const badge = generateBadge(booking.payment_status);
                    const formattedPrice = parseFloat(booking.total_price).toLocaleString('id-ID');

                    bookingElement.innerHTML = `
                        <div class="bg-white shadow-md rounded-lg p-6 mb-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-bold text-lg">${booking.booking_number}</h4>
                                    <p class="text-sm text-gray-500">Penumpang: ${booking.passenger_name}</p>
                                    <p class="text-sm text-gray-500">Telepon: ${booking.passenger_phone}</p>
                                    <p class="text-sm text-gray-500">Jumlah Penumpang: ${booking.quantity}</p>
                                    <p class="text-sm text-gray-500">Total Harga: IDR ${formattedPrice}</p>
                                    <p class="text-sm text-gray-500">Status Pembayaran: ${badge}</p>
                                </div>
                                <div>
                                    <a href="${bookingDetailUrl}" class="bg-indigo-600 text-white text-sm font-bold py-2 px-4 rounded-sm hover:bg-indigo-700 transition duration-300">Detail</a>
                                </div>
                            </div>
                        </div>
                    `;
                    displayBookings.appendChild(bookingElement);
                });

                if (apiResponse.length === 0) {
                    displayBookings.innerHTML = `<p class="text-center text-gray-500">Belum ada data booking.</p>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan dalam komunikasi dengan server.');
            });
        }
    });

    function generateBadge(status) {
        let badge = '';
        switch (status) {
            case 'pending':
                badge = `<span class="bg-yellow-500 text-white text-xs font-bold py-1 px-2 rounded-full">PENDING</span>`;
                break;
            case 'paid':
                badge = `<span class="bg-green-500 text-white text-xs font-bold py-1 px-2 rounded-full">PAID</span>`;
                break;
            default:
                badge = `<span class="bg-red-500 text-white text-xs font-bold py-1 px-2 rounded-full">${status.toUpperCase()}</span>`;
                break;
        }
        return badge;
    }
</script>
@endpush