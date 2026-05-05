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

            <input
                type="password"
                name="password"
                placeholder="Minimal 6 karakter"
                class="mb-2 h-[48px] w-full rounded border border-gray-300 px-4 outline-none focus:border-[#21a078]"
            >

            <p class="mb-4 text-sm font-semibold text-[#66708a]">
                Password minimal 6 karakter
            </p>

            <label class="mb-2 block font-semibold text-[#66708a]">
                Konfirmasi Password
            </label>

            <input
                type="password"
                name="password_confirmation"
                placeholder="Ulangi password baru"
                class="mb-6 h-[48px] w-full rounded border border-gray-300 px-4 outline-none focus:border-[#21a078]"
            >

            <button
                type="submit"
                class="h-[48px] w-full rounded bg-[#21a078] font-bold text-white hover:bg-[#188d69]"
            >
                RESET PASSWORD
            </button>
        </form>
    </div>
</body>
</html>
