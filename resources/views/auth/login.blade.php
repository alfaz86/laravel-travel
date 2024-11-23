@extends('app')

@section('title', 'Login')

@section('content')
<div class="container mx-auto my-8">
    <h2 class="text-2xl font-bold text-center mb-4 hidden sm:block">Login</h2>
    <form id="loginForm" method="POST" class="max-w-md mx-auto p-4 bg-white shadow-md rounded">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="relative mb-6">
                <input type="password" id="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500 pe-10">
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
        </div>
        <button type="submit" class="w-full bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600 transition">
            Login
        </button>
        <p class="text-center text-sm mt-4">
            Belum punya akun? <a href="{{ route('auth.register') }}" class="text-indigo-500 hover:underline">Daftar</a>
        </p>
    </form>
</div>

@include('components.alert')
@endsection

@push('scripts')
<script>
    // Handle password visibility toggle
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#password');
    const showIcon = document.querySelector('#showIcon');
    const hideIcon = document.querySelector('#hideIcon');

    togglePassword.addEventListener('click', () => {
        // Toggle the type of the password field
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;

        // Toggle the visibility of icons
        showIcon.classList.toggle('hidden');
        hideIcon.classList.toggle('hidden');
    });

    document.querySelector('#loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.querySelector('#email').value;
        const password = document.querySelector('#password').value;

        const res = await authLogin(email, password);

        if (res && res.data.token) {
            alert('Login berhasil!');

            // Redirect ke dashboard
            window.location.href = '/';
        } else {
            alert('Login gagal. Silakan periksa email atau password Anda.');
        }
    });
</script>
@endpush
