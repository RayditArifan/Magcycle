<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Mitra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-[#f5f5f5]">
    <div class="w-[430px] rounded-xl border border-[#21a078] bg-white p-8 shadow">
        <h1 class="mb-6 text-center text-3xl font-bold text-[#4a4a4a]">
            Reset Password
        </h1>

        @if ($errors->any())
            <div class="mb-4 rounded bg-red-50 px-4 py-3 text-sm font-semibold text-red-600">
                Password minimal 6 karakter dan konfirmasi harus sama.
            </div>
        @endif

        <form action="{{ route('mitra.password.update', $token) }}" method="POST">
            @csrf

            <label class="mb-2 block font-semibold text-[#66708a]">
                Password Baru
            </label>

            <div class="relative mb-2">
                <input
                    type="password"
                    name="password"
                    id="reset_password"
                    placeholder="Minimal 6 karakter"
                    class="h-[48px] w-full rounded border border-gray-300 px-4 pr-12 outline-none focus:border-[#21a078]"
                >
                <button
                    type="button"
                    data-toggle-password
                    data-target="reset_password"
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

            <p class="mb-4 text-sm font-semibold text-[#66708a]">
                Password minimal 6 karakter
            </p>

            <label class="mb-2 block font-semibold text-[#66708a]">
                Konfirmasi Password
            </label>

            <div class="relative mb-6">
                <input
                    type="password"
                    name="password_confirmation"
                    id="reset_password_confirmation"
                    placeholder="Ulangi password baru"
                    class="h-[48px] w-full rounded border border-gray-300 px-4 pr-12 outline-none focus:border-[#21a078]"
                >
                <button
                    type="button"
                    data-toggle-password
                    data-target="reset_password_confirmation"
                    class="absolute inset-y-0 right-3 flex items-center text-[#66708a] hover:text-[#21a078]"
                    aria-label="Lihat konfirmasi password"
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

            <button
                type="submit"
                class="h-[48px] w-full rounded bg-[#21a078] font-bold text-white hover:bg-[#188d69]"
            >
                RESET PASSWORD
            </button>
        </form>
    </div>

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
