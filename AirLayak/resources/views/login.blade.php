<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirLayak — Masuk</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/login.css','resources/js/app.js'])
</head>

<body class="bg-[#04342C] text-white min-h-screen flex">

    <!-- LEFT -->
    <div class="w-1/2 p-12 flex flex-col justify-between">

        <!-- Logo -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-teal-400 rounded-lg flex items-center justify-center">
                💧
            </div>
            <div class="font-display text-xl font-extrabold">
                Air<span class="text-teal-200">Layak</span>
            </div>
        </div>

        <!-- Content -->
        <div>
            <p class="text-xs tracking-widest uppercase text-teal-200 mb-4">
                Sistem Pemantauan Air
            </p>

            <h1 class="font-display text-4xl font-extrabold leading-tight mb-4">
                Air bersih adalah <br>
                hak <span class="text-teal-200">semua</span> warga.
            </h1>

            <p class="text-sm text-white/60 max-w-md">
                Platform berbasis laporan warga untuk memantau kualitas air secara real-time.
            </p>

            <!-- Stats -->
            <div class="flex gap-8 mt-10">
                <div>
                    <div class="text-2xl font-bold text-teal-200">2.4K</div>
                    <div class="text-xs text-white/40">Laporan</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-teal-200">38</div>
                    <div class="text-xs text-white/40">Kelurahan</div>
                </div>
            </div>
        </div>

        <div class="text-xs text-white/30">
            © 2025 AirLayak
        </div>
    </div>

    <!-- RIGHT -->
    <div class="w-1/2 flex items-center justify-center bg-white text-gray-900">
        <div class="w-full max-w-md p-10 rounded-2xl shadow-2xl">

            <h2 class="font-display text-2xl font-bold mb-1">Selamat datang</h2>
            <p class="text-sm text-gray-400 mb-6">
                Masuk untuk melanjutkan
            </p>

            <form method="POST" action="{{ route('welcome') }}">
                @csrf

                <!-- Role -->
                <div class="flex bg-gray-100 p-1 rounded-lg mb-6 text-sm">
                    <button type="button" onclick="setRole(this, 'warga')"
                        class="role-btn flex-1 py-2 bg-white rounded-md text-teal-600 font-medium">
                        Warga
                    </button>
                    <button type="button" onclick="setRole(this, 'petugas')" class="role-btn flex-1 py-2 text-gray-400">
                        Petugas
                    </button>
                    <button type="button" onclick="setRole(this, 'dinas')" class="role-btn flex-1 py-2 text-gray-400">
                        Dinas
                    </button>
                </div>

                <input type="hidden" name="role" id="role-input" value="warga">

                <!-- Email -->
                <input name="email" type="email" required
                    class="w-full h-11 border rounded-lg px-3 mb-4 focus:outline-none focus:ring-2 focus:ring-teal-400"
                    placeholder="Email" value="{{ old('email') }}" />

                <!-- Password -->
                <input name="password" type="password" required
                    class="w-full h-11 border rounded-lg px-3 mb-2 focus:outline-none focus:ring-2 focus:ring-teal-400"
                    placeholder="Password" />

                <!-- Google Login -->
                <a href="{{ route('auth.google') }}" class="btn btn-google mb-2">
                    {{-- <a href="#" class="btn btn-google"> --}}
                        <svg class="google-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                fill="#4285F4" />
                            <path
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                fill="#34A853" />
                            <path
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                fill="#FBBC05" />
                            <path
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                fill="#EA4335" />
                        </svg>
                        Masuk dengan Google
                    </a>

                    <!-- Error -->
                    @error('email')
                    <div class="text-red-500 text-xs mb-2">{{ $message }}</div>
                    @enderror

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full h-12 bg-teal-600 text-white rounded-xl font-semibold hover:bg-teal-800 transition">
                        Masuk
                    </button>

                    <div class="text-right text-xs text-teal-600 mb-4 cursor-pointer">
                        {{-- <a href="{{ route('password.request') }}">Lupa password?</a> --}}
                        <a href="#">Lupa password?</a>
                    </div>
            </form>

            <!-- Divider -->
            <div class="flex items-center gap-2 my-6 text-xs text-gray-300">
                <div class="flex-1 h-px bg-gray-200"></div>
                atau
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- <a href="{{ route('guest') }}" --}} <a href="#"
                class="block text-center w-full h-11 leading-11 border rounded-xl hover:bg-gray-50">
                Masuk sebagai tamu
            </a>

        </div>
    </div>


    <script>
        function setRole(btn, role) {
            // remove active styles
            document.querySelectorAll('.role-btn').forEach(b => {
                b.classList.remove('bg-white', 'text-teal-600', 'font-medium');
                b.classList.add('text-gray-400');
            });

            // set active style
            btn.classList.add('bg-white', 'text-teal-600', 'font-medium');
            btn.classList.remove('text-gray-400');

            // store value for Laravel
            document.getElementById('role-input').value = role;
        }

        function togglePass() {
            const inp = document.getElementById('pass-input');
            inp.type = inp.type === 'password' ? 'text' : 'password';
        }

        function doLogin() {
            const email = document.getElementById('email-input').value;
            const pass = document.getElementById('pass-input').value;
            const err = document.getElementById('error-msg');
            if (!email || !pass) {
            err.style.display = 'block';
            err.textContent = 'Harap isi email dan kata sandi terlebih dahulu.';
            } else {
            err.style.display = 'none';
            alert('Login berhasil! Mengarahkan ke Dashboard...');
            }
        }

        function guestLogin() {
            alert('Masuk sebagai tamu — Mengarahkan ke peta laporan...');
        }
    </script>
</body>

</html>