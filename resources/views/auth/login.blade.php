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
            <input type="password" id="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500">
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
