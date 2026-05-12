<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - MagCycle</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-[#4a4a4a]">
    <div class="flex min-h-screen">
        @include('partials.mitra-sidebar')

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="border-b border-[#c8c8c8] bg-white">
                <div class="flex items-center justify-between" style="height: 110px; padding-left: 50px; padding-right: 50px;">
                    <h1 class="font-medium text-[#4a4a4a]" style="font-size: 42px;">
                        Profil Saya
                    </h1>

                    @include('partials.mitra-topbar-actions', [
                        'notificationCount' => $notificationCount ?? 0
                    ])
                </div>
            </header>

            <main class="flex-1 px-20 py-10">
                <section class="mx-auto w-full max-w-[790px] rounded-[14px] border border-[#21a078] bg-white px-12 py-8">
                    <div class="mb-7 flex items-center justify-between">
                        <h2 class="text-[25px] font-bold text-[#4a4a4a]">
                            Profil Saya
                        </h2>

                        <a
                            href="{{ route('mitra.profile.edit') }}"
                            class="flex h-[52px] w-[92px] items-center justify-center rounded-[10px] border border-[#21a078] text-[17px] font-bold text-[#21a078] hover:bg-[#21a078] hover:text-white"
                        >
                            Edit
                        </a>
                    </div>

                    <div class="grid grid-cols-[200px_1fr] items-center gap-y-4 text-[19px]">
                        <label>Username</label>
                        <input value="{{ $mitra->username }}" disabled class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 text-[#888]">

                        <label>Email</label>
                        <input value="{{ $mitra->email }}" disabled class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 text-[#888]">

                        <label>Nomor Telepon</label>
                        <input value="{{ $mitra->no_hp }}" disabled class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 text-[#888]">

                        <label>Password</label>
                        <input value="********" disabled class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 text-[#888]">

                        <label>Alamat</label>
                        <input value="{{ $mitra->alamat }}" disabled class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 text-[#888]">

                        <label>Provinsi</label>
                        <input value="{{ $mitra->nama_provinsi ?? '' }}" disabled class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 text-[#888]">

                        <label>Kabupaten/Kota</label>
                        <input value="{{ $mitra->nama_kab_kota ?? '' }}" disabled class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 text-[#888]">

                        <label>Kecamatan</label>
                        <input value="{{ $mitra->nama_kecamatan ?? '' }}" disabled class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 text-[#888]">
                    </div>
                </section>
            </main>
        </div>
    </div>
@include('partials.success-popup')
</body>
</html>
