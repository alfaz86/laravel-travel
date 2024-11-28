<!-- Toast Warning -->
<div id="toast-warning" class="hidden flex items-center w-full max-w-xs p-4 text-gray-500 bg-indigo-50 rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 mb-1" role="alert">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
        </svg>
        <span class="sr-only">Warning icon</span>
    </div>
    <div class="ms-3 text-sm font-normal" id="toast-warning-message">Improve password difficulty.</div>
</div>

<!-- Toast Success -->
<div id="toast-success" class="hidden flex items-center w-full max-w-xs p-4 text-gray-500 bg-indigo-50 rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 mb-1" role="alert">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
        </svg>
        <span class="sr-only">Check icon</span>
    </div>
    <div class="ms-3 text-sm font-normal" id="toast-success-message">Improve password difficulty.</div>
</div>

<script>
    function showToast(type, message, time=2000) {
        // Menampilkan toast
        var toastTemplate = $(`#toast-${type}`).clone().removeAttr('id').removeClass('hidden');
        toastTemplate.find(`#toast-${type}-message`).text(message);
        $('#toast-container').append(toastTemplate);
        
        // Menampilkan toast dengan animasi
        setTimeout(() => {
            toastTemplate.fadeOut(300, function() { $(this).remove(); });
        }, time); // Menghilang setelah 3 detik
    }
</script>

@if(session('alert'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('warning', '{{ session('alert') }}');
        });
    </script>
@endif
