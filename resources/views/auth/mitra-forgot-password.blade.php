<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password Mitra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-[#f5f5f5]">
    <div class="w-[430px] rounded-xl border border-[#21a078] bg-white p-8 shadow">
        <h1 class="mb-6 text-center text-3xl font-bold text-[#4a4a4a]">
            Lupa Password
        </h1>

        <form action="{{ route('mitra.password.sendLink') }}" method="POST">
            @csrf

            <label class="mb-2 block font-semibold text-[#66708a]">
                Email Akun Mitra
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email yang terdaftar"
                class="mb-6 h-[48px] w-full rounded border border-gray-300 px-4 outline-none focus:border-[#21a078]"
            >

            <button
                type="submit"
                class="h-[48px] w-full rounded bg-[#21a078] font-bold text-white hover:bg-[#188d69]"
            >
                Kirim link reset password
            </button>
        </form>

        <a href="{{ route('login') }}" class="mt-5 block text-center font-semibold text-[#21a078]">
            Kembali ke Login
        </a>
    </div>

    @if (session('popup') === 'email_not_found')
        <script>alert('Email mitra tidak ditemukan');</script>
    @endif

    @if (session('popup') === 'link_sent')
        <script>alert('Link reset password sudah dikirim ke email');</script>
    @endif

    @if (session('popup') === 'token_invalid')
        <script>alert('Link reset sudah tidak valid atau kedaluwarsa');</script>
    @endif
</body>
</html>
