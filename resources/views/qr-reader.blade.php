@extends('app')

@section('title', 'Scan QR Code')

@section('content')
<div class="container mx-auto my-8 text-center">
    <h3 class="text-3xl font-bold mb-6 text-gray-800 hidden sm:block">Scan QR Code</h3>

    <!-- Video Element untuk Menampilkan Aliran Kamera -->
    <video id="video" class="mx-auto mb-4 bg-slate-100 border border-slate-300" width="300" height="300"></video>
    
    <!-- Canvas untuk Mengambil Gambar dari Video -->
    <canvas id="canvas" hidden></canvas>

    <!-- Output untuk Menampilkan Data dari QR Code -->
    <p id="output" class="text-lg text-gray-700"></p>

    <!-- Audio Element for Success Sound -->
    <audio id="successSound" src="{{ asset('audio/barcode-scan-sound.mp3') }}" preload="auto"></audio>

    <!-- Tombol untuk Mengontrol Pemindaian -->
    <button id="startScan" class="px-4 py-2 bg-indigo-500 text-white rounded-md mt-4">Start Scan</button>
    <button id="stopScan" class="px-4 py-2 bg-red-500 text-white rounded-md mt-4 ml-2" disabled>Stop Scan</button>
</div>

@include('components.alert')
@endsection

@push('scripts')
<script>
    $(document).ready(async function() {
        const isLoggedIn = checkLoginStatus();
        if (!isLoggedIn) {
            await showToastWithRedirect(
                'warning',
                'Silakan login terlebih dahulu untuk melanjutkan.',
                1000,
                () => {
                    const redirectParams = {
                        w: ['redirect'],
                        d: {
                            redirect : '/qr-reader',
                        }
                    };
                    const encodedParams = btoa(JSON.stringify(redirectParams));
                    window.location.href = `/auth/login?d=${encodedParams}`;
                }
            );
        } else {
            const authUser = getAuthUser();
            
            if (authUser.role === 'user') {
                showToast('warning', 'Anda tidak memiliki akses ke halaman ini.', 15000);
                window.location.href = '/';
            }
        }
    });
</script>
@endpush

@push('scripts')
<script src="{{ asset('/js/jsQR.js') }}"></script>
<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const output = document.getElementById('output');
    const startScanButton = document.getElementById('startScan');
    const stopScanButton = document.getElementById('stopScan');
    const successSound = document.getElementById('successSound');

    let stream;
    let scanning = false;
    let isRequestInProgress = false;  // Flag to control scanning while request is in progress

    // Fungsi untuk memulai scan QR
    function startScan() {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(function (localStream) {
                stream = localStream;
                video.srcObject = stream;
                video.setAttribute("playsinline", true); // Required to play video inline on iOS
                video.play();
                scanning = true;
                startScanButton.disabled = true;
                stopScanButton.disabled = false;
                output.textContent = "";
                requestAnimationFrame(scanQRCode);
            })
            .catch(function (error) {
                console.error("Error accessing camera: ", error);
                output.textContent = "Unable to access camera.";
            });
    }

    // Fungsi untuk menghentikan scan QR
    function stopScan() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        video.srcObject = null;
        scanning = false;
        startScanButton.disabled = false;
        stopScanButton.disabled = true;
        output.textContent = "Scan stopped.";
    }

    // Fungsi untuk memindai QR Code
    function scanQRCode() {
        if (scanning && video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.height = video.videoHeight;
            canvas.width = video.videoWidth;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, canvas.width, canvas.height);

            if (code && !isRequestInProgress) {  // Only scan when no request is in progress
                successSound.play();
                output.textContent = "Processing...";
                
                // Validasi format QR Code
                const qrCodeData = code.data;
                const host = window.location.origin;
                const prefix = host + "/booking/detail/ticket/";
                
                if (qrCodeData.startsWith(prefix)) {
                    // Ekstrak ticket number
                    const ticketNumber = qrCodeData.split(prefix)[1]; // Mengambil bagian setelah prefix
                    
                    if (ticketNumber) {
                        isRequestInProgress = true;
                        sendQrCodeToServer(ticketNumber);
                    } else {
                        output.textContent = "Invalid ticket number format.";
                    }
                } else {
                    output.textContent = "Invalid QR Code. Prefix not matching.";
                }
            }
        }

        if (scanning) {
            requestAnimationFrame(scanQRCode);
        }
    }

    // Fungsi untuk mengirimkan data QR ke server
    function sendQrCodeToServer(ticketNumber) {
        const token = localStorage.getItem('jwt_token'); // Ambil JWT token dari localStorage atau sessionStorage
        const url = '{{ route("qr.reader") }}';

        // Melakukan request POST menggunakan fetch
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`, // Mengirim JWT token di header
            },
            body: JSON.stringify({ ticket_number: ticketNumber })
        })
        .then(response => response.json())
        .then(data => {
            isRequestInProgress = false;  // Reset the flag after the request is completed
            if (data.status == 'success') {
                alert(`Ticket ${ticketNumber} successfully used.`);
            } else {
                alert(`Error: ${data.message}`);
            }
        })
        .catch(error => {
            isRequestInProgress = false;  // Reset the flag in case of error
            console.error('Error:', error);
            alert("Failed to process QR code.");
        })
        .finally(() => {
            output.textContent = "";
        });
    }

    // Event Listener untuk tombol Start Scan
    startScanButton.addEventListener('click', startScan);

    // Event Listener untuk tombol Stop Scan
    stopScanButton.addEventListener('click', stopScan);
</script>
@endpush