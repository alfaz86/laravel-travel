<!-- Toast Warning -->
<div id="toast-warning" class="main-alert hidden flex items-center w-full max-w-sm p-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 mb-1" role="alert">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
        </svg>
        <span class="sr-only">Warning icon</span>
    </div>
    <div class="ms-3 text-sm font-normal" id="toast-warning-message">Improve password difficulty.</div>
</div>

<!-- Toast Success -->
<div id="toast-success" class="main-alert hidden flex items-center w-full max-w-sm p-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 mb-1" role="alert">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
        </svg>
        <span class="sr-only">Check icon</span>
    </div>
    <div class="ms-3 text-sm font-normal" id="toast-success-message">Improve password difficulty.</div>
</div>

<!-- Toast Question -->
<div id="toast-question" class="main-alert hidden flex items-center w-full max-w-sm p-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 mb-1" role="alert">
    <div class="flex">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:text-red-300 dark:bg-red-900">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
            </svg>
            <span class="sr-only">Warning icon</span>
        </div>
        <div class="ms-3 text-sm font-normal">
            <span class="mb-1 text-sm font-semibold text-gray-900 dark:text-white">Confirm</span>
            <div class="mb-2 text-sm font-normal" id="toast-question-message"></div> 
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <a href="#" class="inline-flex justify-center w-full px-2 py-1.5 text-xs font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800" id="confirm">Yes</a>
                </div>
                <div>
                    <a href="#" class="inline-flex justify-center w-full px-2 py-1.5 text-xs font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-600 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-700 dark:focus:ring-gray-700" onclick="hideToast()">No</a> 
                </div>
            </div>    
        </div>
    </div>
</div>

<script>
    let showToastTemplate;

    function showToast(type, message, time = 2000) {
        // Menampilkan toast
        var toastTemplate = $(`#toast-${type}`).clone().removeAttr('id').removeClass('hidden');
        showToastTemplate = toastTemplate;
        toastTemplate.find(`#toast-${type}-message`).text(message);
        $('#toast-container').append(toastTemplate);
        
        // Menampilkan toast dengan animasi
        setTimeout(() => {
            toastTemplate.fadeOut(300, function() { $(this).remove(); });
        }, time); // Menghilang setelah 2 detik
    }

    async function showToastWithRedirect(type, message, duration = 2000, redirect = null) {
        return new Promise((resolve) => {
            showToast(type, message, duration); // Menampilkan toast
            setTimeout(() => {
                resolve(); // Resolusi setelah durasi selesai
            }, duration);
        }).then(() => {
            if (typeof redirect === 'function') {
                redirect(); // Jalankan fungsi callback
            } else if (typeof redirect === 'string') {
                window.location.href = redirect; // Redirect ke URL
            }
        });
    }

    function hideToast() {
        showToastTemplate.fadeOut(200, function() { $(this).remove() });
    }
</script>

@if(session('alert'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('warning', '{{ session('alert') }}');
        });
    </script>
@endif
