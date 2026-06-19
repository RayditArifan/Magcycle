<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Mitra</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-hidden bg-[#fafafa] text-[#4a4a4a]">
    <main class="relative min-h-screen pt-14">
        <div class="absolute -top-24 -left-20 h-96 w-96 rounded-full bg-[#a4e0cf]/35"></div>
        <div class="absolute left-52 top-40 h-60 w-60 rounded-full bg-[#a4e0cf]/35"></div>
        <div class="absolute -top-20 -right-20 h-96 w-96 rounded-full bg-[#a4e0cf]/35"></div>
        <div class="absolute -bottom-36 -left-16 h-80 w-80 rounded-full bg-[#a4e0cf]/35"></div>
        <div class="absolute -bottom-40 -right-20 h-80 w-80 rounded-full bg-[#a4e0cf]/35"></div>

        <h1 class="relative z-10 mb-12 text-center text-4xl font-extrabold tracking-wide text-[#444]">
            DAFTAR AKUN MITRA
        </h1>

        <section class="relative z-10 mx-auto min-h-[560px] w-[860px] bg-white px-24 py-14 shadow-lg max-[950px]:w-[calc(100%-40px)] max-[950px]:px-8">
            <a href="{{ url('/login') }}" class="absolute right-6 top-4 text-5xl font-light leading-none text-[#333] no-underline">
                &times;
            </a>

            <p class="mb-9 text-center text-lg font-bold text-[#66708a]">
                Silahkan Masukkan Data
            </p>

            <form id="registerForm" action="{{ route('mitra.register.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-2 gap-x-12 gap-y-7 max-[950px]:grid-cols-1">
                    <input
                        type="text"
                        name="username"
                        id="username"
                        placeholder="Username"
                        value="{{ old('username') }}"
                        class="h-13 w-full rounded border border-[#cfcfcf] bg-white px-4 text-base text-[#555] outline-none placeholder:text-[#888] focus:border-[#21a078]"
                    >

                    <input
                        type="email"
                        name="email"
                        id="email"
                        placeholder="Email"
                        value="{{ old('email') }}"
                        class="h-13 w-full rounded border border-[#cfcfcf] bg-white px-4 text-base text-[#555] outline-none placeholder:text-[#888] focus:border-[#21a078]"
                    >

                    <input
                        type="text"
                        name="no_hp"
                        id="no_hp"
                        placeholder="Nomor Telepon"
                        value="{{ old('no_hp') }}"
                        class="h-13 w-full rounded border border-[#cfcfcf] bg-white px-4 text-base text-[#555] outline-none placeholder:text-[#888] focus:border-[#21a078]"
                    >

                    <div class="w-full">
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Password"
                                class="h-13 w-full rounded border border-[#cfcfcf] bg-white px-4 pr-12 text-base text-[#555] outline-none placeholder:text-[#888] focus:border-[#21a078]"
                            >
                        <button
                            type="button"
                            data-toggle-password
                            data-target="password"
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
                    </div>

                    <input
                        type="text"
                        name="alamat"
                        id="alamat"
                        placeholder="Alamat"
                        value="{{ old('alamat') }}"
                        class="col-span-2 h-13 w-full rounded border border-[#cfcfcf] bg-white px-4 text-base text-[#555] outline-none placeholder:text-[#888] focus:border-[#21a078] max-[950px]:col-span-1"
                    >


                    <div class="col-span-2 grid grid-cols-3 gap-8 max-[950px]:col-span-1 max-[950px]:grid-cols-1">
                        <input
                            type="text"
                            name="provinsi"
                            id="provinsi"
                            value="Jawa Timur"
                            readonly
                            class="h-13 w-full rounded border border-[#cfcfcf] bg-gray-100 px-4 text-base text-[#555] outline-none"
                        >

                        <select
                            name="id_kota"
                            id="id_kota"
                            class="h-13 w-full rounded border border-[#cfcfcf] bg-white px-4 text-base text-[#555] outline-none focus:border-[#21a078]"
                        >
                            <option value="">Pilih Kab/Kota</option>
                            @foreach ($kabKotaList as $kota)
                                <option value="{{ $kota->id_kota }}"
                                    {{ old('id_kota') == $kota->id_kota ? 'selected' : '' }}>
                                    {{ $kota->nama_kab_kota }}
                                </option>
                            @endforeach
                        </select>

                        <select
                            name="id_kecamatan"
                            id="id_kecamatan"
                            class="h-13 w-full rounded border border-[#cfcfcf] bg-white px-4 text-base text-[#555] outline-none focus:border-[#21a078]"
                        >
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>
                </div>

                <div class="mt-10 text-center">
                    <button
                        type="submit"
                        class="h-13 w-[310px] rounded bg-[#21a078] text-lg font-extrabold text-white hover:bg-[#188d69] max-[950px]:w-full"
                    >
                        DAFTAR
                    </button>
                </div>
            </form>
        </section>
    </main>

    {{-- Popup data tidak valid --}}
    <div id="popupInvalid" class="popup fixed inset-0 z-50 hidden items-center justify-center bg-black/45">
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

    {{-- Popup akun sudah terdaftar --}}
    <div id="popupRegistered" class="popup fixed inset-0 z-50 hidden items-center justify-center bg-black/45">
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

    <div id="popupSuccess" class="popup fixed inset-0 z-50 hidden items-center justify-center bg-black/45">
        <div class="flex min-h-[210px] w-[430px] flex-col items-center justify-center rounded bg-white p-7 text-center shadow-xl">
            <div class="mb-5 flex h-[76px] w-[76px] items-center justify-center rounded-full bg-[#21a078] text-6xl font-bold leading-none text-white">
                ✓
            </div>

            <p class="text-3xl font-extrabold leading-tight text-[#66708a]">
                Akun Berhasil Dibuat
            </p>
        </div>
    </div>

    <script>
        const registerForm = document.getElementById('registerForm');
        const kabKotaSelect     = document.getElementById('id_kota');
        const kecamatanSelect   = document.getElementById('id_kecamatan');
        const selectedKecamatan = @json(old('id_kecamatan', null));
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

        registerForm.addEventListener('submit', function(event) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const noTelp = document.getElementById('no_hp').value.trim();
            const password = document.getElementById('password').value.trim();
            const alamat = document.getElementById('alamat').value.trim();
            const provinsi = document.getElementById('provinsi').value.trim();
            const idKota = kabKotaSelect.value;
            const idKecamatan = kecamatanSelect.value;

            const noTelpValid = /^[0-9]+$/.test(noTelp);
            if (
                username === '' ||
                email === '' ||
                noTelp === '' ||
                password === '' ||
                alamat === '' ||
                provinsi === '' ||
                idKota === '' ||
                idKecamatan === '' ||
                !noTelpValid
            ) {
                event.preventDefault();
                showPopup('popupInvalid');
            }
        });

        function showPopup(id) {
            const popup = document.getElementById(id);
            popup.classList.remove('hidden');
            popup.classList.add('flex');
        }

        function closePopup(popup) {
            popup.classList.add('hidden');
            popup.classList.remove('flex');
        }

        document.querySelectorAll('.popup').forEach(function(popup) {
            popup.addEventListener('click', function() {
                closePopup(popup);
            });
        });

        @if (session('popup') === 'invalid')
            showPopup('popupInvalid');
        @endif

        @if (session('popup') === 'registered')
            showPopup('popupRegistered');
        @endif

        @if (session('popup') === 'success')
            showPopup('popupSuccess');
        @endif
    </script>

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
