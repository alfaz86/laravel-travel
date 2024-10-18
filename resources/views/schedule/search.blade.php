<h5 class="text-gray-700 text-2xl font-bold mb-2">Pesan Tiket Sekarang</h5>
<form action="{{ route('schedule.search') }}" method="post">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Input Origin Card -->
        <div class="bg-indigo-50 shadow-lg shadow-indigo-300/50 rounded-lg p-4 flex items-center">
            <svg class="w-8 h-8 text-gray-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
            </svg>
            <div class="flex-grow">
                <label for="origin" class="block text-sm font-medium text-gray-700">Keberangkatan</label>
                <select id="origin" name="origin" class="bg-indigo-50 border-b-2 border-gray-300 focus:outline-none focus:border-indigo-500 w-full select2">
                    <option value="">Pilih lokasi</option>
                </select>
            </div>
        </div>
    
        <!-- Input Destination Card -->
        <div class="bg-indigo-50 shadow-lg shadow-indigo-300/50 rounded-lg p-4 flex items-center">
            <svg class="w-8 h-8 text-gray-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            <div class="flex-grow">
                <label for="origin" class="block text-sm font-medium text-gray-700">Tujuan</label>
                <select id="destination" name="destination" class="bg-indigo-50 border-b-2 border-gray-300 focus:outline-none focus:border-indigo-500 w-full select2" disabled>
                    <option value="">Pilih lokasi</option>
                </select>
            </div>
        </div>
    
        <!-- Input Passengers Card -->
        <div class="bg-indigo-50 shadow-lg shadow-indigo-300/50 rounded-lg p-4 flex items-center">
            <svg class="w-8 h-8 text-gray-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <div class="flex-grow">
                <label for="origin" class="block text-sm font-medium text-gray-700">Jumlah Penumpang</label>
                <input type="number" id="passengers" name="passengers" placeholder="1" value="1" class="bg-indigo-50 border-b-2 border-gray-300 focus:outline-none focus:border-indigo-500 w-full">
            </div>
        </div>
    
        <!-- Input Departure Time Card -->
        <div class="bg-indigo-50 shadow-lg shadow-indigo-300/50 rounded-lg p-4 flex items-center">
            <svg class="w-8 h-8 text-gray-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <div class="flex-grow">
                <label for="origin" class="block text-sm font-medium text-gray-700">Jadwal</label>
                <input input type="date" id="date" name="date" class="bg-indigo-50 border-b-2 border-gray-300 focus:outline-none focus:border-indigo-500 w-full" min="{{ date('Y-m-d') }}">
            </div>
        </div>
    </div>
    
    <!-- Search Button -->
    <div class="flex justify-center mt-4">
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-3 px-10 rounded-lg focus:outline-none focus:shadow-outline w-full">
            Cari Tiket
        </button>
    </div>
</form>

@section('scripts')
<script>
$(document).ready(function() {
    $('#origin').select2({
        placeholder: 'Pilih lokasi',
        width: '100%',
        minimumInputLength: 0,
        ajax: {
            url: '{{ route('location.search') }}',
            dataType: 'json',
            delay: 250,
            cache: true,
            data: function(params) {
                return {
                    q: params.term || '',
                    limit: 10
                };
            },
            processResults: function(data) {
                return {
                    results: data.items.map(item => {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    })
                };
            },
        },
    });

    $('#destination').select2({
        placeholder: 'Pilih tujuan',
        width: '100%',
        minimumInputLength: 0,
        ajax: {
            url: '{{ route('location.search') }}',
            dataType: 'json',
            delay: 250,
            cache: true,
            data: function(params) {
                return {
                    q: params.term || '',
                    limit: 10,
                    origin: $('#origin').val()
                };
            },
            processResults: function(data) {
                return {
                    results: data.items.map(item => {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    })
                };
            },
        }
    });

    $('#origin').on('change', function() {
        var selectedOrigin = $(this).val();
        if (selectedOrigin) {
            $('#destination').prop('disabled', false);
        } else {
            $('#destination').prop('disabled', true);
        }
    });

    $('#select2-destination-container').on('click', function() {
        if ($('#destination').prop('disabled')) {
            showToast('Silakan pilih lokasi asal terlebih dahulu sebelum memilih tujuan.');
            $('#destination').select2('close'); 
        }
    });

    $('form').on('submit', function() {
        if (!$('#origin').val() || !$('#destination').val() || !$('#passengers').val() || !$('#date').val()) {
            showToast('Silakan lengkapi semua data sebelum melanjutkan.');
            return false;
        }
    });
});
</script>
@endsection