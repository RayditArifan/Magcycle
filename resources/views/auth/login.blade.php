<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MagCycle</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f5f5]">
    <main class="min-h-screen flex flex-col items-center px-4 pt-16 pb-10">
        <section class="text-center mb-12">
            <h1 class="text-3xl md:text-5xl font-bold text-[#4a4a4a]">
                Selamat Datang di Magcycle
            </h1>
            <p class="mt-3 text-lg md:text-2xl font-semibold text-[#1ea36f]">
                Silahkan login untuk melanjutkan
            </p>
        </section>

        <section class="w-full max-w-[520px] bg-white shadow-[0_4px_12px_rgba(0,0,0,0.18)] px-7 md:px-16 py-10 md:py-12">
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
                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan Password"
                        class="w-full h-12 rounded-[3px] border border-gray-300 px-4 text-sm text-gray-700 placeholder:text-gray-400 focus:border-[#22a06b] focus:ring-2 focus:ring-[#22a06b]/20 outline-none"
                        required
                    >
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
</body>
</html>
