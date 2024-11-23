@extends('app')

@section('title', 'Register')

@section('content')
<div class="container mx-auto my-8">
    <h2 class="text-2xl font-bold text-center mb-4 hidden sm:block">Register</h2>
    <form id="registerForm" method="POST" class="max-w-md mx-auto p-4 bg-white shadow-md rounded">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" id="name" name="name" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500">
            <p id="name_error" class="text-sm text-red-500 mt-1 hidden"></p>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500">
            <p id="email_error" class="text-sm text-red-500 mt-1 hidden"></p>
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">No Telepon/Whatsapp</label>
            <input type="phone" id="phone" name="phone" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500">
            <p id="phone_error" class="text-sm text-red-500 mt-1 hidden"></p>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="relative">
                <input type="password" id="password" name="password" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500 pe-10">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" id="togglePassword">
                    <svg id="showIcon" class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                        <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg id="hideIcon" class="w-5 h-5 text-gray-800 dark:text-white hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
            </div>
            <p id="password_error" class="text-sm text-red-500 mt-1 hidden"></p>
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <div class="relative">
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500 pe-10">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" id="toggleConfirmPassword">
                    <svg id="showConfirmIcon" class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                        <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg id="hideConfirmIcon" class="w-5 h-5 text-gray-800 dark:text-white hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
            </div>
            <p id="password_confirmation_error" class="text-sm text-red-500 mt-1 hidden"></p>
        </div>
        <button type="submit"
            class="w-full bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600 transition">
            Daftar
        </button>
        <p class="text-center text-sm mt-4">
            Sudah punya akun? <a href="{{ route('auth.login') }}" class="text-indigo-500 hover:underline">Login</a>
        </p>
    </form>
</div>

@include('components.alert')
@endsection

@push('scripts')
<script>
    // Handle password visibility toggle for password and confirmation fields
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#password');
    const showIcon = document.querySelector('#showIcon');
    const hideIcon = document.querySelector('#hideIcon');

    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirmPasswordField = document.querySelector('#password_confirmation');
    const showConfirmIcon = document.querySelector('#showConfirmIcon');
    const hideConfirmIcon = document.querySelector('#hideConfirmIcon');

    // Toggle for password
    togglePassword.addEventListener('click', () => {
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        showIcon.classList.toggle('hidden');
        hideIcon.classList.toggle('hidden');
    });

    // Toggle for password confirmation
    toggleConfirmPassword.addEventListener('click', () => {
        const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;
        showConfirmIcon.classList.toggle('hidden');
        hideConfirmIcon.classList.toggle('hidden');
    });

    document.querySelector('#registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        // Reset all error messages
        const errorFields = ['name', 'email', 'phone', 'password', 'password_confirmation'];
        errorFields.forEach(field => {
            document.querySelector(`#${field}_error`).classList.add('hidden');
            document.querySelector(`#${field}_error`).textContent = '';
        });

        // Ambil data dari form
        const name = document.querySelector('#name').value;
        const email = document.querySelector('#email').value;
        const phone = document.querySelector('#phone').value;
        const password = document.querySelector('#password').value;
        const password_confirmation = document.querySelector('#password_confirmation').value;

        const res = await authRegister(name, email, phone, password, password_confirmation);

        if (res && res.data.token) {
            alert('Registrasi berhasil!');
            
            // Redirect ke halaman lain jika diperlukan
            window.location.href = '/';
        } else if (res && res.data) {
            const errors = res.data;
            console.log(errors);
            
            // Tampilkan pesan error di form
            Object.keys(errors).forEach(field => {
                const errorElement = document.querySelector(`#${field}_error`);
                if (errorElement) {
                    errorElement.textContent = errors[field].join(', ');
                    errorElement.classList.remove('hidden');
                }
            });
        } else {
            alert('Registrasi gagal. Terjadi kesalahan server.');
        }
    });
</script>
@endpush