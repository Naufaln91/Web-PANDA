<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - PANDA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body class="bg-gradient-to-br from-blue-400 via-purple-400 to-pink-400 min-h-screen flex items-center justify-center">

    <div class="container max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2">
                <!-- Left Side - Illustration -->
                <div
                    class="bg-gradient-to-br from-blue-500 to-indigo-600 p-12 flex flex-col justify-center items-center text-white">
                    <div class="text-8xl mb-6">🐼</div>
                    <h1 class="text-4xl font-bold mb-4">PANDA</h1>
                    <p class="text-center text-lg opacity-90">Platform Pembelajaran Anak</p>
                    <div class="mt-8 text-center">
                        <div class="flex justify-center space-x-6 text-5xl">
                            <span>📚</span>
                            <span>🎮</span>
                            <span>✏️</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Forms -->
                <div class="p-12">
                    <!-- Tab Buttons -->
                    <div class="flex space-x-2 mb-8">
                        <button onclick="showTab('admin')" id="tab-admin"
                            class="flex-1 py-3 px-4 rounded-lg font-semibold transition bg-blue-500 text-white">
                            Admin
                        </button>
                        <button onclick="showTab('user')" id="tab-user"
                            class="flex-1 py-3 px-4 rounded-lg font-semibold transition bg-gray-200 text-gray-700">
                            Guru / Wali Murid
                        </button>
                    </div>

                    <!-- Admin Login Form -->
                    <div id="form-admin" class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Login Admin</h2>

                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('login.admin') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Username</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" name="username" required
                                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                        placeholder="Masukkan username">
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-gray-700 font-semibold mb-2">Password</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" name="password" required
                                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                        placeholder="Masukkan password">
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg transition duration-300">
                                <i class="fas fa-sign-in-alt mr-2"></i> Login
                            </button>
                        </form>
                    </div>

                    <!-- User Login Form (Guru/Wali Murid) -->
                    <div id="form-user" class="space-y-6 hidden">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Login Guru / Wali Murid</h2>

                        <!-- Step 1: Input Email -->
                        <div id="step-email">
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Email</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" id="email"
                                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                        placeholder="nama@email.com">
                                </div>
                                <p id="error-email" class="text-red-500 text-sm mt-1 hidden"></p>
                            </div>

                            <button onclick="requestOtp()" id="btn-request-otp"
                                class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition duration-300">
                                <i class="fas fa-paper-plane mr-2"></i> Minta Kode OTP
                            </button>
                        </div>

                        <!-- Step 2: Input OTP -->
                        <div id="step-otp" class="hidden">
                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg mb-4">
                                <p class="font-semibold">Kode OTP telah dikirim ke email Anda</p>
                                <p class="text-sm mt-1">Kode berlaku selama 5 menit</p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Masukkan Kode OTP</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-key"></i>
                                    </span>
                                    <input type="text" id="otp_code" maxlength="6"
                                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none text-2xl tracking-widest text-center font-bold"
                                        placeholder="000000">
                                </div>
                                <p id="error-otp" class="text-red-500 text-sm mt-1 hidden"></p>
                            </div>

                            <div class="mb-4">
                                <button onclick="resendOtp()" id="btn-resend-otp"
                                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 rounded-lg transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-envelope mr-2"></i> Kirim Ulang OTP
                                </button>
                                <p id="resend-info" class="text-sm text-gray-600 mt-1 text-center">
                                    <span id="resend-countdown" class="hidden">Kirim ulang tersedia dalam <span
                                            id="countdown-timer">30</span> detik</span>
                                    <span id="resend-limit" class="hidden">Batas kirim ulang tercapai</span>
                                </p>
                            </div>

                            <button onclick="verifyOtp()" id="btn-verify-otp"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg transition duration-300">
                                <i class="fas fa-check mr-2"></i> Verifikasi OTP
                            </button>

                            <button onclick="backToEmail()"
                                class="w-full mt-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 rounded-lg transition duration-300 flex items-center justify-center">
                                <i class="fas fa-arrow-left "></i><span class="ml-2">Kembali</span>
                            </button>
                        </div>

                        <!-- Step 3: Complete Profile (New User) -->
                        <div id="step-profile" class="hidden">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Lengkapi Profil Anda</h3>

                            <div class="space-y-4">
                                <!-- Form untuk Guru -->
                                <div id="form-guru" class="hidden">
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Nama</label>
                                        <input type="text" id="nama_guru"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                            placeholder="Masukkan nama lengkap">
                                    </div>
                                </div>

                                <!-- Form untuk Wali Murid -->
                                <div id="form-wali-murid" class="hidden">
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Nama Orang Tua</label>
                                        <input type="text" id="nama_orangtua"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                            placeholder="Masukkan nama orang tua">
                                    </div>

                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Nama Anak</label>
                                        <input type="text" id="nama_anak"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                            placeholder="Masukkan nama anak">
                                    </div>

                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Kelas Anak</label>
                                        <select id="kelas_anak"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                                            <option value="">Pilih kelas</option>
                                            <option value="TK A">TK A</option>
                                            <option value="TK B">TK B</option>
                                        </select>
                                    </div>
                                </div>

                                <p id="error-profile" class="text-red-500 text-sm hidden"></p>

                                <button onclick="confirmCompleteProfile()" id="btn-complete-profile"
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg transition duration-300">
                                    <i class="fas fa-save mr-2"></i> Simpan & Masuk
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentEmail = '';
        let currentRole = '';

        function showTab(tab) {
            if (tab === 'admin') {
                document.getElementById('form-admin').classList.remove('hidden');
                document.getElementById('form-user').classList.add('hidden');
                document.getElementById('tab-admin').classList.add('bg-blue-500', 'text-white');
                document.getElementById('tab-admin').classList.remove('bg-gray-200', 'text-gray-700');
                document.getElementById('tab-user').classList.add('bg-gray-200', 'text-gray-700');
                document.getElementById('tab-user').classList.remove('bg-blue-500', 'text-white');
            } else {
                document.getElementById('form-admin').classList.add('hidden');
                document.getElementById('form-user').classList.remove('hidden');
                document.getElementById('tab-user').classList.add('bg-blue-500', 'text-white');
                document.getElementById('tab-user').classList.remove('bg-gray-200', 'text-gray-700');
                document.getElementById('tab-admin').classList.add('bg-gray-200', 'text-gray-700');
                document.getElementById('tab-admin').classList.remove('bg-blue-500', 'text-white');
            }
        }

        function requestOtp() {
            const email = document.getElementById('email').value;
            const errorDiv = document.getElementById('error-email');
            const btn = document.getElementById('btn-request-otp');

            errorDiv.classList.add('hidden');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';

            $.ajax({
                url: '{{ route('login.request-otp') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: email
                },
                success: function(response) {
                    if (response.success) {
                        currentEmail = email;
                        currentRole = response.role;
                        document.getElementById('step-email').classList.add('hidden');
                        document.getElementById('step-otp').classList.remove('hidden');
                        startResendCountdown();
                    } else {
                        errorDiv.textContent = response.message;
                        errorDiv.classList.remove('hidden');
                    }
                },
                error: function() {
                    errorDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                    errorDiv.classList.remove('hidden');
                },
                complete: function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Minta Kode OTP';
                }
            });
        }

        function verifyOtp() {
            const otpCode = document.getElementById('otp_code').value;
            const errorDiv = document.getElementById('error-otp');
            const btn = document.getElementById('btn-verify-otp');

            errorDiv.classList.add('hidden');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memverifikasi...';

            $.ajax({
                url: '{{ route('login.verify-otp') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: currentEmail,
                    otp_code: otpCode
                },
                success: function(response) {
                    if (response.success) {
                        if (response.is_new_user) {
                            document.getElementById('step-otp').classList.add('hidden');
                            document.getElementById('step-profile').classList.remove('hidden');
                            // Tampilkan form sesuai role
                            showProfileForm(currentRole);
                        } else {
                            window.location.href = response.redirect_url;
                        }
                    } else {
                        errorDiv.textContent = response.message;
                        errorDiv.classList.remove('hidden');
                    }
                },
                error: function() {
                    errorDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                    errorDiv.classList.remove('hidden');
                },
                complete: function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check mr-2"></i> Verifikasi OTP';
                }
            });
        }

        function confirmCompleteProfile() {
            const errorDiv = document.getElementById('error-profile');
            errorDiv.classList.add('hidden');

            // Validate form
            let isValid = true;
            let data = {
                _token: '{{ csrf_token() }}',
                email: currentEmail
            };

            if (currentRole === 'guru') {
                const namaGuru = document.getElementById('nama_guru').value;
                if (!namaGuru) {
                    errorDiv.textContent = 'Nama harus diisi!';
                    errorDiv.classList.remove('hidden');
                    return;
                }
                data.nama = namaGuru;
            } else {
                const namaOrangtua = document.getElementById('nama_orangtua').value;
                const namaAnak = document.getElementById('nama_anak').value;
                const kelasAnak = document.getElementById('kelas_anak').value;

                if (!namaOrangtua || !namaAnak || !kelasAnak) {
                    errorDiv.textContent = 'Semua field harus diisi!';
                    errorDiv.classList.remove('hidden');
                    return;
                }
                data.nama_orangtua = namaOrangtua;
                data.nama_anak = namaAnak;
                data.kelas_anak = kelasAnak;
            }

            // Show confirmation popup
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin data sudah diisi dengan benar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    completeProfile(data);
                }
            });
        }

        function completeProfile(data) {
            const errorDiv = document.getElementById('error-profile');
            const btn = document.getElementById('btn-complete-profile');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';

            $.ajax({
                url: '{{ route('login.complete-profile') }}',
                method: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Akun Anda berhasil dibuat.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    } else {
                        errorDiv.textContent = response.message;
                        errorDiv.classList.remove('hidden');
                    }
                },
                error: function() {
                    errorDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                    errorDiv.classList.remove('hidden');
                },
                complete: function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan & Masuk';
                }
            });
        }

        function resendOtp() {
            const btn = document.getElementById('btn-resend-otp');
            const errorDiv = document.getElementById('error-otp');

            errorDiv.classList.add('hidden');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim ulang...';

            $.ajax({
                url: '{{ route('login.resend-otp') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: currentEmail
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        startResendCountdown();
                        if (response.remaining_resend <= 0) {
                            disableResendButton();
                        }
                    } else {
                        errorDiv.textContent = response.message;
                        errorDiv.classList.remove('hidden');
                        if (response.message.includes('tercapai')) {
                            disableResendButton();
                        }
                    }
                },
                error: function() {
                    errorDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                    errorDiv.classList.remove('hidden');
                },
                complete: function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-envelope mr-2"></i> Kirim Ulang OTP';
                }
            });
        }

        function startResendCountdown() {
            let countdown = 30;
            const countdownElement = document.getElementById('countdown-timer');
            const countdownDiv = document.getElementById('resend-countdown');
            const resendBtn = document.getElementById('btn-resend-otp');

            resendBtn.disabled = true;
            countdownDiv.classList.remove('hidden');
            document.getElementById('resend-limit').classList.add('hidden');

            const timer = setInterval(() => {
                countdown--;
                countdownElement.textContent = countdown;

                if (countdown <= 0) {
                    clearInterval(timer);
                    countdownDiv.classList.add('hidden');
                    resendBtn.disabled = false;
                }
            }, 1000);
        }

        function disableResendButton() {
            const resendBtn = document.getElementById('btn-resend-otp');
            const countdownDiv = document.getElementById('resend-countdown');
            const limitDiv = document.getElementById('resend-limit');

            resendBtn.disabled = true;
            countdownDiv.classList.add('hidden');
            limitDiv.classList.remove('hidden');
        }

        function backToEmail() {
            document.getElementById('step-otp').classList.add('hidden');
            document.getElementById('step-email').classList.remove('hidden');
            document.getElementById('otp_code').value = '';
        }

        function showProfileForm(role) {
            if (role === 'guru') {
                document.getElementById('form-guru').classList.remove('hidden');
                document.getElementById('form-wali-murid').classList.add('hidden');
            } else {
                document.getElementById('form-guru').classList.add('hidden');
                document.getElementById('form-wali-murid').classList.remove('hidden');
            }
        }
    </script>
</body>

</html>
