<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PANDA - Pembelajaran Anak Dengan Asyik')</title>

    <!-- Tailwind CSS (Built with Vite) -->
    @vite(['resources/css/app.css'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Sortable JS for drag & drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .btn-primary {
            @apply bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-300 text-sm sm:text-base;
        }

        .btn-success {
            @apply bg-green-500 hover:bg-green-600 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-300 text-sm sm:text-base;
        }

        .btn-danger {
            @apply bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-300 text-sm sm:text-base;
        }

        .btn-warning {
            @apply bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-300 text-sm sm:text-base;
        }

        .card {
            @apply bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6;
        }

        /* Utility classes for common gradients */
        .gradient-primary {
            @apply bg-gradient-to-r from-blue-100 to-indigo-100;
        }

        .gradient-secondary {
            @apply bg-gradient-to-r from-purple-100 to-pink-100;
        }

        .gradient-accent {
            @apply bg-gradient-to-r from-yellow-100 to-orange-100;
        }

        /* Prevent horizontal scroll */
        body {
            overflow-x: hidden;
        }

        /* Better touch targets for mobile */
        @media (max-width: 768px) {

            button,
            a {
                min-height: 44px;
                min-width: 44px;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-blue-100 shadow-lg sticky top-0 z-50" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <div class="text-2xl sm:text-3xl">🐼</div>
                    <div>
                        <h1 class="text-base sm:text-xl font-bold text-gray-800">PANDA</h1>
                        <p class="text-[10px] sm:text-xs text-gray-500">Pembelajaran Anak Dengan Asyik</p>
                    </div>
                </div>

                @auth
                    <!-- Desktop Navigation (Right-aligned) -->
                    <div class="hidden md:flex items-center space-x-4 lg:space-x-6">
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isGuru() ? route('guru.dashboard') : route('wali-murid.dashboard')) }}"
                            class="text-gray-700 hover:text-blue-600 transition text-sm lg:text-base">
                            <i class="fas fa-home mr-1"></i> Dashboard
                        </a>

                        <a href="{{ route('materi.index') }}"
                            class="text-gray-700 hover:text-blue-600 transition text-sm lg:text-base">
                            <i class="fas fa-book mr-1"></i> Materi
                        </a>

                        <a href="{{ route('permainan.index') }}"
                            class="text-gray-700 hover:text-blue-600 transition text-sm lg:text-base">
                            <i class="fas fa-gamepad mr-1"></i> Permainan
                        </a>

                        <a href="{{ route('kuis.index') }}"
                            class="text-gray-700 hover:text-blue-600 transition text-sm lg:text-base">
                            <i class="fas fa-clipboard-question mr-1"></i> Kuis
                        </a>

                        @if (auth()->user()->isAdmin())
                            <div class="relative group">
                                <button class="text-gray-700 hover:text-blue-600 transition text-sm lg:text-base">
                                    <i class="fas fa-users-cog mr-1"></i> Kelola
                                </button>
                                <div
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                    <a href="{{ route('admin.whitelist.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150">
                                        <i class="fas fa-list mr-2"></i> Whitelist Email
                                    </a>
                                    <a href="{{ route('admin.akun.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150">
                                        <i class="fas fa-user-circle mr-2"></i> Kelola Akun
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- User Info & Logout (Always Visible on Desktop) -->
                        <div class="text-right mr-2">
                            <p class="text-sm font-semibold text-gray-700">
                                {{ auth()->user()->nama ?? auth()->user()->username }}</p>
                            <p class="text-xs text-gray-500 capitalize">
                                {{ str_replace('_', ' ', auth()->user()->role) }}</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="ml-2">
                            @csrf
                            <button type="submit" onclick="confirmLogout(event)"
                                class="text-red-500 hover:text-red-700 transition p-2">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Mobile: User Info & Hamburger Menu -->
                    <div class="md:hidden flex items-center space-x-2">
                        <div class="text-right">
                            <p class="text-xs sm:text-sm font-semibold text-gray-700 truncate max-w-[100px]">
                                {{ auth()->user()->nama ?? auth()->user()->username }}</p>
                            <p class="text-[10px] sm:text-xs text-gray-500 capitalize">
                                {{ str_replace('_', ' ', auth()->user()->role) }}</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" onclick="confirmLogout(event)"
                                class="text-red-500 hover:text-red-700 transition p-2">
                                <i class="fas fa-sign-out-alt text-base"></i>
                            </button>
                        </form>

                        <!-- Mobile menu button -->
                        <button @click="open = !open" type="button"
                            class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                            :aria-expanded="open" aria-controls="mobile-menu">
                            <span class="sr-only">Toggle main menu</span>
                            <svg :class="{ 'hidden': open, 'block': !open }" class="block h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg :class="{ 'block': open, 'hidden': !open }" class="hidden h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Mobile menu -->
        @auth
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="md:hidden fixed inset-0 z-40 bg-black bg-opacity-50"
                id="mobile-menu" x-cloak>
                <div x-show="open" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="transform translate-x-full" x-transition:enter-end="transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform translate-x-0"
                    x-transition:leave-end="transform translate-x-full"
                    class="fixed inset-y-0 right-0 max-w-[85%] w-full bg-white shadow-xl flex flex-col">
                    <div class="flex items-center justify-between p-3 border-b flex-shrink-0">
                        <div class="flex items-center space-x-2">
                            <div class="text-xl">🐼</div>
                            <span class="text-base font-bold text-gray-800">PANDA</span>
                        </div>
                        <button @click.stop="open = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-3 py-4 space-y-3 overflow-y-auto flex-1">
                        <!-- User Info in Mobile Menu -->
                        <div class="border-b pb-3 mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-700">
                                        {{ auth()->user()->nama ?? auth()->user()->username }}</p>
                                    <p class="text-xs text-gray-500 capitalize">
                                        {{ str_replace('_', ' ', auth()->user()->role) }}</p>
                                </div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="confirmLogout(event)"
                                        class="text-red-500 hover:text-red-700 transition p-2">
                                        <i class="fas fa-sign-out-alt text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Navigation Links -->
                        <nav class="space-y-1">
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isGuru() ? route('guru.dashboard') : route('wali-murid.dashboard')) }}"
                                @click="open = false"
                                class="flex items-center px-3 py-2.5 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition text-sm">
                                <i class="fas fa-home mr-3 w-5"></i> Dashboard
                            </a>

                            <a href="{{ route('materi.index') }}" @click="open = false"
                                class="flex items-center px-3 py-2.5 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition text-sm">
                                <i class="fas fa-book mr-3 w-5"></i> Materi
                            </a>

                            <a href="{{ route('permainan.index') }}" @click="open = false"
                                class="flex items-center px-3 py-2.5 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition text-sm">
                                <i class="fas fa-gamepad mr-3 w-5"></i> Permainan
                            </a>

                            <a href="{{ route('kuis.index') }}" @click="open = false"
                                class="flex items-center px-3 py-2.5 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition text-sm">
                                <i class="fas fa-clipboard-question mr-3 w-5"></i> Kuis
                            </a>

                            @if (auth()->user()->isAdmin())
                                <div class="border-t pt-3 mt-3">
                                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                        Kelola</p>
                                    <a href="{{ route('admin.whitelist.index') }}" @click="open = false"
                                        class="flex items-center px-3 py-2.5 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition text-sm">
                                        <i class="fas fa-list mr-3 w-5"></i> Whitelist Email
                                    </a>
                                    <a href="{{ route('admin.akun.index') }}" @click="open = false"
                                        class="flex items-center px-3 py-2.5 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition text-sm">
                                        <i class="fas fa-user-circle mr-3 w-5"></i> Kelola Akun
                                    </a>
                                </div>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        @endauth
    </nav>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white py-4 sm:py-6 shadow-lg">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 text-center text-gray-600">
            <p class="text-xs sm:text-sm">&copy; 2025 PANDA - Pembelajaran Anak Dengan Asyik. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')

    <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Anda akan keluar dari akun Anda.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                backdrop: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }
    </script>
</body>

</html>
