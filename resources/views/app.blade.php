<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ env('APP_NAME', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @yield('styles')
    </head>
    <body class="min-h-screen flex flex-col">
        <!-- Include Navbar -->
        @include('components.navbar')

        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-5 z-50 w-full mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Toast akan dimasukkan di sini -->
        </div>

        <!-- Content Section -->
        <main class="my-5 flex-grow">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>

        <!-- Include Footer -->
        @include('components.footer')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        @stack('scripts')
    </body>
</html>
