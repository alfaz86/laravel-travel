@extends('app')

@section('title', 'Detail Tiket')

@section('content')
<div class="container mx-auto my-8 text-center">
    <h3 class="text-3xl font-bold mb-6 text-gray-800 hidden sm:block">Detail Tiket</h3>

    <!-- QR Code Center -->
    <div class="flex justify-center mb-6">
        <img 
            src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(url('/booking/detail/ticket/' . $ticket->ticket_number)) }}&size=320x320" 
            alt="QR Code {{ $ticket->ticket_number }}" 
            class="w-80 h-auto"
        />
    </div>

    <!-- Ticket Details -->
    <div class="main-card bg-white shadow-md rounded-lg px-6 py-4">
        <!-- Status -->
        <div class="mb-4">
            <span class="block text-gray-600 font-bold">Status:</span>
            @if ($ticket->status === \App\Models\Ticket::STATUS_NOT_USED)
                <span id="ticketStatus" class="bg-yellow-500 text-white text-xs font-bold py-1 px-2 rounded-full">
                    {{ Str::upper($ticket->status) }}
                </span>
            @elseif ($ticket->status === \App\Models\Ticket::STATUS_USED)
                <span id="ticketStatus" class="bg-green-500 text-white text-xs font-bold py-1 px-2 rounded-full">
                    {{ Str::upper($ticket->status) }}
                </span>
            @else
                <span id="ticketStatus" class="bg-red-500 text-white text-xs font-bold py-1 px-2 rounded-full">
                    {{ Str::upper($ticket->status) }}
                </span>
            @endif
        </div>

        <!-- Schedule Details -->
        <div class="mb-4">
            <span class="block text-gray-600 font-bold">Jadwal:</span>
            <p class="text-gray-700">{{ $ticket->booking->schedule->departure_time }} - {{ $ticket->booking->schedule->arrive_time }}</p>
            <span class="block text-gray-600 font-bold">Rute:</span>
            <p class="text-gray-700">{{ $ticket->booking->schedule->origin->name }} - {{ $ticket->booking->schedule->destination->name }}</p>
        </div>

        <!-- Copy Link -->
        <div class="mt-4">
            <button 
                id="copyLink" 
                class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition duration-300"
            >
                Salin Tautan Tiket
            </button>
            <p id="copyMessage" class="mt-2 text-green-500 hidden">Tautan berhasil disalin!</p>
        </div>
    </div>
</div>

@include('components.alert')
@endsection

@push('scripts')
@if (env('APP_PUSHER_SETTING', false))
<script>
    $(document).ready(function() {
        const currentTicketNumber = @json($ticket->ticket_number);
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });
    
        const channel = pusher.subscribe('status-ticket-updates');
        channel.bind('StatusTicketUpdated', function (data) {
            if (data.ticketNumber !== currentTicketNumber) {
                return;
            }

            const statusElement = document.getElementById('ticketStatus');
            const status = data.status;
            if (status === 'used') {
                statusElement.textContent = 'USED';
                statusElement.className = 'bg-green-500 text-white text-xs font-bold py-1 px-2 rounded-full';
                showToast('success', 'Tiket berhasil digunakan.', 5000);
            } else if (status === 'not_used') {
                statusElement.textContent = 'NOT USED';
                statusElement.className = 'bg-yellow-500 text-white text-xs font-bold py-1 px-2 rounded-full';
            } 
        });
    });
</script>
@endif

<script>
    document.getElementById('copyLink').addEventListener('click', function () {
        const ticketLink = "{{ url('/booking/detail/ticket/' . $ticket->ticket_number) }}";

        if (navigator.clipboard && navigator.clipboard.writeText) {
            // Jika clipboard API tersedia
            navigator.clipboard.writeText(ticketLink)
                .then(() => {
                    showCopySuccess();
                })
                .catch(err => {
                    console.error('Gagal menyalin tautan:', err);
                    alert('Gagal menyalin tautan.');
                });
        } else {
            // Alternatif untuk browser yang tidak mendukung clipboard API
            const tempInput = document.createElement('input');
            tempInput.value = ticketLink;
            document.body.appendChild(tempInput);
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // Untuk iOS

            try {
                document.execCommand('copy');
                showCopySuccess();
            } catch (err) {
                console.error('Gagal menyalin tautan:', err);
                alert('Gagal menyalin tautan.');
            }

            document.body.removeChild(tempInput);
        }

        function showCopySuccess() {
            const message = document.getElementById('copyMessage');
            message.classList.remove('hidden');
            setTimeout(() => message.classList.add('hidden'), 2000);
        }
    });

</script>
@endpush
