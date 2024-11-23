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
            <input type="password" id="password" name="password" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500">
            <p id="password_error" class="text-sm text-red-500 mt-1 hidden"></p>
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500">
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