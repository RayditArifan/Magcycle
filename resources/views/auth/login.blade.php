<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MagCycle</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-hidden bg-[#fafafa] text-[#4a4a4a]">
    <main class="relative min-h-screen flex flex-col items-center px-4 pt-16 pb-10">
        <div class="absolute -top-24 -left-20 h-96 w-96 rounded-full bg-[#a4e0cf]/35"></div>
        <div class="absolute left-52 top-40 h-60 w-60 rounded-full bg-[#a4e0cf]/35"></div>
        <div class="absolute -top-20 -right-20 h-96 w-96 rounded-full bg-[#a4e0cf]/35"></div>
        <div class="absolute -bottom-36 -left-16 h-80 w-80 rounded-full bg-[#a4e0cf]/35"></div>
        <div class="absolute -bottom-40 -right-20 h-80 w-80 rounded-full bg-[#a4e0cf]/35"></div>
        <section class="relative z-10 text-center mb-12">
            <h1 class="text-3xl md:text-5xl font-bold text-[#4a4a4a]">
                Selamat Datang di Magcycle
            </h1>
            <p class="mt-3 text-lg md:text-2xl font-semibold text-[#1ea36f]">
                Silahkan login untuk melanjutkan
            </p>
        </section>

        <section class="relative z-10 w-full max-w-[520px] bg-white shadow-[0_4px_12px_rgba(0,0,0,0.18)] px-7 md:px-16 py-10 md:py-12">
            <div class="flex items-center justify-center gap-4 mb-10">
            <img
                src="{{ asset('assets/img/logo-magcycle.jpg') }}"
                alt="Logo MagCycle"
                class="w-[70px] md:w-[85px] h-auto object-contain">

            <h2 class="text-3xl md:text-4xl font-extrabold text-[#4a4a4a] leading-none">
                Magcycle </h2>
                </div>

            @if(session('login_error'))
            <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                    {{ session('login_error') }}
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <input
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukkan Username"
                        class="w-full h-12 rounded-[3px] border border-gray-300 px-4 text-sm text-gray-700 placeholder:text-gray-400 focus:border-[#22a06b] focus:ring-2 focus:ring-[#22a06b]/20 outline-none"
                        required
                    >
                    @error('username')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            id="login_password"
                            placeholder="Masukkan Password"
                            class="w-full h-12 rounded-[3px] border border-gray-300 px-4 pr-12 text-sm text-gray-700 placeholder:text-gray-400 focus:border-[#22a06b] focus:ring-2 focus:ring-[#22a06b]/20 outline-none"
                            required
                        >
        <button
            type="button"
            data-toggle-password
            data-target="login_password"
            class="absolute inset-y-0 right-3 flex items-center text-[#66708a] hover:text-[#21a078]"
            aria-label="Lihat password"
        >
            <svg class="icon-eye h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <svg class="icon-eye-off hidden h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.58 10.58A2 2 0 0 0 12 14a2 2 0 0 0 1.42-.58" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 4.35A10.8 10.8 0 0 1 12 4.13c6 0 9.75 6.75 9.75 6.75a17.2 17.2 0 0 1-2.34 3.07" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.42 6.42C3.84 8.1 2.25 12 2.25 12S6 18.75 12 18.75c1.36 0 2.62-.35 3.75-.9" />
            </svg>
        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <a href="{{ route('mitra.password.forgot') }}" class="mt-2 block text-left text-sm font-semibold text-[#21a078]">
                    Lupa Password Mitra?
                </a>
                <button
                    type="submit"
                    class="w-full h-11 rounded-[3px] bg-[#22a06b] text-white text-sm font-bold transition hover:bg-[#1c8b5d]"
                >
                    Login
                </button>

                <div class="flex items-center gap-3 text-xs text-gray-400">
                    <div class="h-px flex-1 bg-gray-300"></div>
                    <span>atau</span>
                    <div class="h-px flex-1 bg-gray-300"></div>
                </div>

                <a
                    href="{{ route('mitra.register') }}"
                    class="flex w-full h-11 items-center justify-center rounded-[3px] bg-[#22a06b] text-sm font-bold text-white transition hover:bg-[#1c8b5d]"
                >
                    Daftar
                </a>
            </form>
        </section>
    </main>

    @if(session('success') || session('login_error_popup'))
        <div id="statusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/25 px-4">
            <div>
                @if(session('success'))
                    <img
                        src="{{ asset('assets/img/login-success.jpg') }}"
                        alt="Berhasil Login"
                        class="w-full max-w-[360px] shadow-2xl"
                    >
                @else
                    <img
                        src="{{ asset('assets/img/login-salah.jpg') }}"
                        alt="Login Gagal"
                        class="w-full max-w-[360px] shadow-2xl"
                    >
                @endif
            </div>
        </div>

        <script>
            setTimeout(() => {
                const modal = document.getElementById('statusModal');
                if (modal) modal.style.display = 'none';
            }, 2500);
        </script>
    @endif

    @if (session('logout_success'))
        <div id="logoutSuccessPopup" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/45">
            <div class="flex min-h-[210px] w-[430px] flex-col items-center justify-center rounded bg-white p-7 text-center shadow-xl">
                <div class="mb-5 flex h-[76px] w-[76px] items-center justify-center rounded-full bg-[#21a078] text-6xl font-bold leading-none text-white">
                    ✓
                </div>

                <p class="text-3xl font-extrabold leading-tight text-[#66708a]">
                    Berhasil Logout
                </p>
            </div>
        </div>

        <script>
            const logoutSuccessPopup = document.getElementById('logoutSuccessPopup');

            if (logoutSuccessPopup) {
                logoutSuccessPopup.addEventListener('click', function () {
                    logoutSuccessPopup.remove();
                });

                setTimeout(function () {
                    if (logoutSuccessPopup) {
                        logoutSuccessPopup.remove();
                    }
                }, 1800);
            }
        </script>
    @endif

    @if (session('popup') === 'password_updated')
        <div id="passwordUpdatedPopup" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/45">
            <div class="flex min-h-[210px] w-[430px] flex-col items-center justify-center rounded bg-white p-7 text-center shadow-xl">
                <div class="mb-5 flex h-[76px] w-[76px] items-center justify-center rounded-full bg-[#21a078] text-6xl font-bold leading-none text-white">
                    ✓
                </div>

                <p class="text-3xl font-extrabold leading-tight text-[#66708a]">
                    Password berhasil<br>
                    diperbarui
                </p>
            </div>
        </div>

        <script>
            const passwordUpdatedPopup = document.getElementById('passwordUpdatedPopup');

            if (passwordUpdatedPopup) {
                passwordUpdatedPopup.addEventListener('click', function () {
                    passwordUpdatedPopup.remove();
                });

                setTimeout(function () {
                    if (passwordUpdatedPopup) {
                        passwordUpdatedPopup.remove();
                    }
                }, 1800);
            }
        </script>
    @endif

<script>
    (function () {
        document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(button.dataset.target);
                const eye = button.querySelector('.icon-eye');
                const eyeOff = button.querySelector('.icon-eye-off');

                if (!input) return;

                const willShow = input.type === 'password';
                input.type = willShow ? 'text' : 'password';

                if (eye && eyeOff) {
                    eye.classList.toggle('hidden', willShow);
                    eyeOff.classList.toggle('hidden', !willShow);
                }
            });
        });
    })();
</script>
</body>
</html>
