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
                    <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 
                              {{ Route::is('home') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500' }} 
                              text-sm font-medium hover:border-gray-300 hover:text-gray-700">
                        Home
                    </a>

                    <a href="{{ route('booking.list.page') }}" class="inline-flex items-center px-1 pt-1 border-b-2 
                              {{ Route::is('booking.list.page') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500' }} 
                              text-sm font-medium hover:border-gray-300 hover:text-gray-700" style="display: none;" data-role="user">
                        Daftar Booking
                    </a>
                    <a href="{{ route('qr.reader.page') }}" class="inline-flex items-center px-1 pt-1 border-b-2 
                              {{ Route::is('qr.reader.page') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500' }} 
                              text-sm font-medium hover:border-gray-300 hover:text-gray-700" style="display: none;" data-role="admin">
                        Scan Tiket
                    </a>
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
            <div class="hidden sm:flex border-t border-gray-200 pt-4 pb-3">
                <a href="{{ route('auth.login') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-800" style="display: none;" id="loginBtn">Login</a>
            </div>

            <div class="hidden sm:flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse" id="profile" style="display: none;">
                <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full bg-slate-400" src="/images/user-avatar-male-5.png" alt="user photo">
                </button>

                <!-- Dropdown menu -->
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white user-name"></span>
                        <span class="block text-sm  text-gray-500 truncate dark:text-gray-400 user-email"></span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Profile</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white logoutBtn" onclick="logout()">Sign out</a>
                        </li>
                    </ul>
                </div>
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
            <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 border-l-4 
                      {{ Route::is('home') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                Home
            </a>

            <a href="{{ route('booking.list.page') }}" class="block pl-3 pr-4 py-2 border-l-4 
                      {{ Route::is('booking.list.page') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}" style="display: none;" data-role="user">
                Daftar Booking
            </a>
            <a href="{{ route('qr.reader.page') }}" class="block pl-3 pr-4 py-2 border-l-4 
                      {{ Route::is('qr.reader.page') ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}" style="display: none;" data-role="admin">
                Scan Tiket
            </a>
        </div>
        <div class="border-t border-gray-200 pt-4 pb-3" id="mobile-menu-login" style="display: none;">
            <a href="{{ route('auth.login') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-800">Login</a>
        </div>
        <div class="border-t border-gray-200 pt-4 pb-3" id="mobile-menu-logout" style="display: none;">
            <div class="px-3">
                <span class="block text-sm text-gray-900 dark:text-white user-name"></span>
                <span class="block text-sm  text-gray-500 truncate dark:text-gray-400 user-email"></span>
            </div>
            <a href="{{ route('auth.login') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-800 logoutBtn" onclick="logout()">Sign Out</a>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    $(function() {
        const authUser = getAuthUser();
        
        // DOM Elements
        const $mobileMenuButton = $('[aria-controls="mobile-menu"]');
        const $mobileMenu = $('#mobile-menu');
        const $mobileMenuLogin = $('#mobile-menu-login');
        const $mobileMenuLogout = $('#mobile-menu-logout');
        const $loginButton = $('#loginBtn');
        const $userProfileMenu = $('#profile');
        const $logoutButton = $('.logoutBtn');
        const $navUser = $('a[data-role="user"]');
        const $navAdmin = $('a[data-role="admin"]');
        const $authUserProfile = $('.user-name, .user-email');

        // Toggle mobile menu visibility
        const toggleMobileMenu = () => {
            const isExpanded = $mobileMenuButton.attr('aria-expanded') === 'true';
            $mobileMenuButton.attr('aria-expanded', !isExpanded);
            $mobileMenu.toggleClass('hidden');
        };

        // Update user profile information
        const updateUserProfile = () => {
            $authUserProfile.each(function() {
                if ($(this).hasClass('user-name')) {
                    $(this).text(authUser.name);
                } else {
                    $(this).text(authUser.email);
                }
            });
        };

        // Handle UI updates based on authentication status
        const updateUIForAuthUser = () => {
            $userProfileMenu.css('display', '')
            $loginButton.hide();
            $mobileMenuLogin.hide();
            $mobileMenuLogout.show();
            updateUserProfile();

            if (authUser.role === 'user') {
                $navUser.show();
                $navAdmin.hide();
            } else {
                $navUser.show();
                $navAdmin.show();
            }
        };

        // Handle UI updates for non-authenticated user
        const updateUIForGuest = () => {
            $loginButton.show();
            $userProfileMenu.hide();
            $mobileMenuLogin.show();
            $mobileMenuLogout.hide();
        };

        // Handle UI for auth page
        const updateUIForAuthPage = () => {
            $loginButton.hide();
            $userProfileMenu.hide();
        };

        // Initialize UI based on user authentication status
        if (authUser) {
            updateUIForAuthUser();
        } else if (isAuthPage) {
            updateUIForAuthPage();
        } else {
            updateUIForGuest();
        }

        // Add event listener for mobile menu toggle
        $mobileMenuButton.on('click', toggleMobileMenu);
    });

    async function logout() {
        const logoutStatus = await authLogout();
        if (logoutStatus) {
            window.location.href = '/';
        }
    }
</script>
@endpush