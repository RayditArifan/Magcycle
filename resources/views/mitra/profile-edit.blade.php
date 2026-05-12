<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Saya - MagCycle</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-[#4a4a4a]">
    <div class="flex min-h-screen">
        @include('partials.mitra-sidebar')

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="border-b border-[#c8c8c8] bg-white">
                <div class="flex items-center justify-between" style="height: 110px; padding-left: 50px; padding-right: 50px;">
                    <h1 class="font-medium text-[#4a4a4a]" style="font-size: 42px;">
                        Edit Profil Saya
                    </h1>

                    @include('partials.mitra-topbar-actions', [
                        'notificationCount' => $notificationCount ?? 0
                    ])
                </div>
            </header>

            <main class="flex-1 px-20 py-10">
                <section class="mx-auto w-full max-w-[790px] rounded-[14px] border border-[#21a078] bg-white px-12 py-8">
                    <h2 class="mb-7 text-[25px] font-bold text-[#4a4a4a]">
                        Edit Profil Saya
                    </h2>

                    <form id="editProfileForm" action="{{ route('mitra.profile.update') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-[200px_1fr] items-center gap-y-4 text-[19px]">
                            <label>Username</label>
                            <input name="username" id="username" value="{{ old('username', $mitra->username) }}" class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 outline-none focus:border-[#21a078]">

                            <label>Email</label>
                            <input name="email" id="email" value="{{ old('email', $mitra->email) }}" class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 outline-none focus:border-[#21a078]">

                            <label>Nomor Telepon</label>
                            <input name="no_hp" id="no_hp" value="{{ old('no_hp', $mitra->no_hp) }}" class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 outline-none focus:border-[#21a078]">

                            <label>Password Lama</label>
                            <input
                                type="password"
                                name="old_password"
                                id="old_password"
                                placeholder="Masukkan password lama"
                                class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 outline-none focus:border-[#21a078]"
                            >

                            <label>Password Baru</label>
                            <div>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    placeholder="Kosongkan jika tidak diganti"
                                    class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 outline-none focus:border-[#21a078]"
                                >

                                <p class="mt-1 text-sm font-semibold text-[#66708a]">
                                    Password baru minimal 6 karakter
                                </p>
                            </div>

                            <label>Alamat</label>
                            <input name="alamat" id="alamat" value="{{ old('alamat', $mitra->alamat) }}" class="h-[42px] w-[300px] rounded-[10px] border border-[#555] px-3 outline-none focus:border-[#21a078]">

                            <label>Provinsi</label>
                            <input
                                type="text"
                                value="Jawa Timur"
                                readonly
                                class="h-[42px] w-[300px] rounded-[10px] border border-[#555] bg-gray-100 px-3 outline-none"
                            >

                            <label>Kabupaten/Kota</label>
                            <select
                                name="id_kota"
                                id="id_kota"
                                class="h-[42px] w-[300px] rounded-[10px] border border-[#555] bg-white px-3 outline-none focus:border-[#21a078]"
                            >
                                <option value="">Pilih Kab/Kota</option>
                                @foreach ($kabKotaList as $kota)
                                    <option value="{{ $kota->id_kota }}"
                                        {{ (old('id_kota', $mitra->id_kota ?? '') == $kota->id_kota) ? 'selected' : '' }}>
                                        {{ $kota->nama_kab_kota }}
                                    </option>
                                @endforeach
                            </select>

                            <label>Kecamatan</label>
                            <select
                                name="id_kecamatan"
                                id="id_kecamatan"
                                class="h-[42px] w-[300px] rounded-[10px] border border-[#555] bg-white px-3 outline-none focus:border-[#21a078]"
                            >
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatanList as $kec)
                                    <option value="{{ $kec->id_kecamatan }}"
                                        {{ (old('id_kecamatan', $mitra->id_kecamatan ?? '') == $kec->id_kecamatan) ? 'selected' : '' }}>
                                        {{ $kec->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-8 flex justify-end gap-4">
                            <a
                                href="{{ route('mitra.profile') }}"
                                class="flex h-[46px] w-[110px] items-center justify-center rounded bg-gray-300 text-[17px] font-bold text-[#4a4a4a] hover:bg-gray-400"
                            >
                                Batal
                            </a>

                            <button
                                type="button"
                                data-confirm-submit
                                data-form="editProfileForm"
                                class="h-[46px] w-[120px] rounded bg-[#21a078] text-[17px] font-bold text-white hover:bg-[#188d69]"
                            >
                                Simpan
                            </button>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>

    {{-- Popup data tidak valid --}}
    <div id="popupInvalid" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/45">
        <div class="flex min-h-[210px] w-[430px] flex-col items-center justify-center rounded bg-white p-7 text-center shadow-xl">
            <svg class="mb-5 h-[70px] w-[80px]" viewBox="0 0 100 90">
                <path d="M50 8 L92 82 H8 Z" fill="none" stroke="#ff1010" stroke-width="8" stroke-linejoin="round"/>
                <line x1="50" y1="31" x2="50" y2="55" stroke="#ff1010" stroke-width="8" stroke-linecap="round"/>
                <circle cx="50" cy="68" r="5" fill="#ff1010"/>
            </svg>

            <p class="text-3xl font-extrabold leading-tight text-[#66708a]">
                Data tidak valid.<br>
                Silahkan isi kembali
            </p>
        </div>
    </div>

    {{-- Popup salah password --}}
    <div id="popupWrongPassword" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/45">
        <div class="flex min-h-[210px] w-[430px] flex-col items-center justify-center rounded bg-white p-7 text-center shadow-xl">
            <svg class="mb-5 h-[70px] w-[80px]" viewBox="0 0 100 90">
                <path d="M50 8 L92 82 H8 Z" fill="none" stroke="#ff1010" stroke-width="8" stroke-linejoin="round"/>
                <line x1="50" y1="31" x2="50" y2="55" stroke="#ff1010" stroke-width="8" stroke-linecap="round"/>
                <circle cx="50" cy="68" r="5" fill="#ff1010"/>
            </svg>

            <p class="text-3xl font-extrabold leading-tight text-[#66708a]">
                Password salah.<br>
                Silahkan isi kembali
            </p>
        </div>
    </div>

    {{-- Popup password kosong --}}
    <div id="popupPasswordRequired" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/45">
        <div class="flex min-h-[210px] w-[430px] flex-col items-center justify-center rounded bg-white p-7 text-center shadow-xl">
            <svg class="mb-5 h-[70px] w-[80px]" viewBox="0 0 100 90">
                <path d="M50 8 L92 82 H8 Z" fill="none" stroke="#ff1010" stroke-width="8" stroke-linejoin="round"/>
                <line x1="50" y1="31" x2="50" y2="55" stroke="#ff1010" stroke-width="8" stroke-linecap="round"/>
                <circle cx="50" cy="68" r="5" fill="#ff1010"/>
            </svg>

            <p class="text-3xl font-extrabold leading-tight text-[#66708a]">
                Password<br>
                wajib diisi
            </p>
        </div>
    </div>

    {{-- Popup password minimal 6 karakter --}}
    <div id="popupPasswordMin" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/45">
        <div class="flex min-h-[210px] w-[430px] flex-col items-center justify-center rounded bg-white p-7 text-center shadow-xl">
            <svg class="mb-5 h-[70px] w-[80px]" viewBox="0 0 100 90">
                <path d="M50 8 L92 82 H8 Z" fill="none" stroke="#ff1010" stroke-width="8" stroke-linejoin="round"/>
                <line x1="50" y1="31" x2="50" y2="55" stroke="#ff1010" stroke-width="8" stroke-linecap="round"/>
                <circle cx="50" cy="68" r="5" fill="#ff1010"/>
            </svg>

            <p class="text-3xl font-extrabold leading-tight text-[#66708a]">
                Password baru<br>
                minimal 6 karakter
            </p>
        </div>
    </div>

    {{-- Popup username sudah terdaftar --}}
    <div id="popupRegistered" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/45">
        <div class="flex min-h-[210px] w-[430px] flex-col items-center justify-center rounded bg-white p-7 text-center shadow-xl">
            <svg class="mb-5 h-[70px] w-[80px]" viewBox="0 0 100 90">
                <path d="M50 8 L92 82 H8 Z" fill="none" stroke="#ff1010" stroke-width="8" stroke-linejoin="round"/>
                <line x1="50" y1="31" x2="50" y2="55" stroke="#ff1010" stroke-width="8" stroke-linecap="round"/>
                <circle cx="50" cy="68" r="5" fill="#ff1010"/>
            </svg>

            <p class="text-3xl font-extrabold leading-tight text-[#66708a]">
                Akun sudah pernah<br>
                terdaftar
            </p>
        </div>
    </div>


    <script>
        const form = document.getElementById('editProfileForm');
        const kabKotaSelect     = document.getElementById('id_kota');
        const kecamatanSelect   = document.getElementById('id_kecamatan');
        const selectedKecamatan = @json(old('id_kecamatan', $mitra->id_kecamatan ?? null));
        const kecamatanApiUrl   = '{{ url('/api/kecamatan') }}';

        function loadKecamatan(idKota, preselect) {
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            if (!idKota) return;

            fetch(`${kecamatanApiUrl}?id_kota=${idKota}`)
                .then(r => r.json())
                .then(data => {
                    data.forEach(kec => {
                        const opt = document.createElement('option');
                        opt.value = kec.id_kecamatan;
                        opt.textContent = kec.nama_kecamatan;
                        if (preselect && kec.id_kecamatan == preselect) opt.selected = true;
                        kecamatanSelect.appendChild(opt);
                    });
                });
        }

        if (kabKotaSelect.value) {
            loadKecamatan(kabKotaSelect.value, selectedKecamatan);
        }

        kabKotaSelect.addEventListener('change', function () {
            loadKecamatan(this.value, null);
        });

        function showPopup(id) {
            const popup = document.getElementById(id);
            popup.classList.remove('hidden');
            popup.classList.add('flex');
        }

        function hidePopup(popup) {
            popup.classList.add('hidden');
            popup.classList.remove('flex');
        }

        document.querySelectorAll('.fixed.inset-0').forEach(function (popup) {
            popup.addEventListener('click', function (event) {
                if (event.target === popup) hidePopup(popup);
            });
        });

        @if (session('popup') === 'invalid')
            showPopup('popupInvalid');
        @endif
        @if (session('popup') === 'registered')
            showPopup('popupRegistered');
        @endif
        @if (session('popup') === 'password_required')
            showPopup('popupPasswordRequired');
        @endif
        @if (session('popup') === 'wrong_password')
            showPopup('popupWrongPassword');
        @endif
        @if (session('popup') === 'password_min')
            showPopup('popupPasswordMin');
        @endif
    </script>
@include('partials.confirm-change-popup')
</body>
</html>

