<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PANDA - Platform Pembelajaran')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Sortable JS for drag & drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .btn-primary {
            @apply bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-300;
        }

        .btn-success {
            @apply bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-300;
        }

        .btn-danger {
            @apply bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-300;
        }

        .btn-warning {
            @apply bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-300;
        }

        .card {
            @apply bg-white rounded-xl shadow-lg p-6;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <div class="text-3xl">🐼</div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">PANDA</h1>
                        <p class="text-xs text-gray-500">Platform Pembelajaran</p>
                    </div>
                </div>

                @auth
                    <div class="flex items-center space-x-6">
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isGuru() ? route('guru.dashboard') : route('wali-murid.dashboard')) }}"
                            class="text-gray-700 hover:text-blue-600 transition">
                            <i class="fas fa-home mr-1"></i> Dashboard
                        </a>

                        <a href="{{ route('materi.index') }}" class="text-gray-700 hover:text-blue-600 transition">
                            <i class="fas fa-book mr-1"></i> Materi
                        </a>

                        <a href="{{ route('permainan.index') }}" class="text-gray-700 hover:text-blue-600 transition">
                            <i class="fas fa-gamepad mr-1"></i> Permainan
                        </a>

                        <a href="{{ route('kuis.index') }}" class="text-gray-700 hover:text-blue-600 transition">
                            <i class="fas fa-clipboard-question mr-1"></i> Kuis
                        </a>

                        @if (auth()->user()->isAdmin())
                            <div class="relative group">
                                <button class="text-gray-700 hover:text-blue-600 transition">
                                    <i class="fas fa-users-cog mr-1"></i> Kelola
                                </button>
                                <div
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 hidden group-hover:block z-50">
                                    <a href="{{ route('admin.whitelist.index') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                                        <i class="fas fa-list mr-2"></i> Whitelist HP
                                    </a>
                                    <a href="{{ route('admin.akun.index') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                                        <i class="fas fa-user-circle mr-2"></i> Kelola Akun
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center space-x-3">
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-700">
                                    {{ auth()->user()->nama_orangtua ?? auth()->user()->username }}</p>
                                <p class="text-xs text-gray-500 capitalize">
                                    {{ str_replace('_', ' ', auth()->user()->role) }}</p>
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                    <i class="fas fa-sign-out-alt text-xl"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white mt-12 py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-600">
            <p>&copy; 2024 PANDA - Platform Pembelajaran Anak. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
