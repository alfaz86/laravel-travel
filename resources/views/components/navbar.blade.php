<nav class="bg-white border-b border-gray-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left side: Logo and Navigation -->
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    @if (request()->is('/'))
                        <img class="h-8 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
                    @else
                        <img class="h-8 w-auto img-app" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 text-gray-400 cursor-pointer nav-back" onclick="history.back()">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    @endif
                </div>

                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="/" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium text-gray-900">Home</a>
                    <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">Transaksi</a>
                </div>
            </div>

            <!-- Center: Title (using @yield('title')) -->
            <div class="flex-grow text-center hidden sm:flex nav-title">
                <h1 class="text-xl font-bold text-gray-900">
                    @yield('title')
                </h1>
            </div>

            <!-- Center: Title for Mobile -->
            <div class="flex-grow text-center sm:hidden flex items-center justify-center">
                <h1 class="text-xl font-bold text-gray-900">
                    @yield('title')
                </h1>
            </div>

            <!-- Right side: Login button -->
            <div class="hidden sm:flex sm:items-center">
                <a href="#" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Login</a>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="sm:hidden hidden" id="mobile-menu">
        <div class="space-y-1 pt-2 pb-3">
            <a href="/" class="block pl-3 pr-4 py-2 border-l-4 border-indigo-500 text-base font-medium text-indigo-700 bg-indigo-50">Home</a>
            <a href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800">Transaksi</a>
        </div>
        <div class="border-t border-gray-200 pt-4 pb-3">
            <a href="#" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-800">Login</a>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const mobileMenuButton = document.querySelector('[aria-controls="mobile-menu"]');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
            mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');
        });
    });
</script>
