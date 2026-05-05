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
                <div class="W-full">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Password minimal 6 karakter"
                        class="h-13 w-full rounded border border-[#cfcfcf] bg-white px-4 text-base text-[#555] outline-none placeholder:text-[#888] focus:border-[#21a078]"
                    >

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
                            class="h-[52px] w-full rounded border border-[#cfcfcf] bg-gray-100 px-4 text-base text-[#555] outline-none"
                        >

                        <select
                            name="kab_kota"
                            id="kab_kota"
                            class="h-[52px] w-full rounded border border-[#cfcfcf] bg-white px-4 text-base text-[#555] outline-none focus:border-[#21a078]"
                        >
                            <option value="">Kab/Kota</option>
                            <option value="Jember" {{ old('kab_kota') === 'Jember' ? 'selected' : '' }}>Jember</option>
                            <option value="Kediri" {{ old('kab_kota') === 'Kediri' ? 'selected' : '' }}>Kediri</option>
                            <option value="Malang" {{ old('kab_kota') === 'Malang' ? 'selected' : '' }}>Malang</option>
                        </select>

                        <select
                            name="kecamatan"
                            id="kecamatan"
                            class="h-[52px] w-full rounded border border-[#cfcfcf] bg-white px-4 text-base text-[#555] outline-none focus:border-[#21a078]"
                        >
                            <option value="">Kecamatan</option>
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

            <p id="popupInvalidMessage" class="text-3xl font-extrabold leading-tight text-[#66708a]">
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
            const dataKecamatan = {
            Jember: [
                'Sumbersari',
                'Kaliwates',
                'Patrang',
                'Ajung',
                'Ambulu',
                'Arjasa',
                'Balung',
                'Bangsalsari',
                'Jelbuk',
                'Kalisat',
                'Kencong',
                'Mayang',
                'Pakusari',
                'Panti',
                'Rambipuji',
                'Sukorambi',
                'Tanggul',
                'Wuluhan'
            ],
            Kediri: [
                'Mojoroto',
                'Pesantren',
                'Kota',
                'Gampengrejo',
                'Banyakan',
                'Puncu',
                'Ngasem',
                'Semampir'
            ],
            Malang: [
                'Klojen',
                'Blimbing',
                'Lowokwaru',
                'Sukun',
                'Kedungkandang'
            ],
        };

        const kabKotaSelect = document.getElementById('kab_kota');
        const kecamatanSelect = document.getElementById('kecamatan');

        kabKotaSelect.addEventListener('change', function () {
            const kabKota = this.value;

            kecamatanSelect.innerHTML = '<option value="">Kecamatan</option>';

            if (!kabKota || !dataKecamatan[kabKota]) {
                return;
            }

            dataKecamatan[kabKota].forEach(function (kecamatan) {
                const option = document.createElement('option');
                option.value = kecamatan;
                option.textContent = kecamatan;
                kecamatanSelect.appendChild(option);
            });
        });

        const oldKabKota = @json(old('kab_kota'));
        const oldKecamatan = @json(old('kecamatan'));
        function loadKecamatan(kabKota, selectedKecamatan = '') {
            kecamatanSelect.innerHTML = '<option value="">Kecamatan</option>';

            if (!kabKota || !dataKecamatan[kabKota]) {
                return;
            }

            dataKecamatan[kabKota].forEach(function (kecamatan) {
                const option = document.createElement('option');
                option.value = kecamatan;
                option.textContent = kecamatan;

                if (kecamatan === selectedKecamatan) {
                    option.selected = true;
                }

                kecamatanSelect.appendChild(option);
            });
        }

        kabKotaSelect.addEventListener('change', function () {
            loadKecamatan(this.value);
        });

        if (oldKabKota) {
            kabKotaSelect.value = oldKabKota;
            loadKecamatan(oldKabKota, oldKecamatan);
        }

            registerForm.addEventListener('submit', function(event) {
                const username = document.getElementById('username').value.trim();
                const email = document.getElementById('email').value.trim();
                const noHp = document.getElementById('no_hp').value.trim();
                const password = document.getElementById('password').value.trim();
                const alamat = document.getElementById('alamat').value.trim();
                const provinsi = document.getElementById('provinsi').value.trim();
                const kabKota = document.getElementById('kab_kota').value.trim();
                const kecamatan = document.getElementById('kecamatan').value.trim();

                const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                const noHpValid = /^[0-9]+$/.test(noHp);
                const passwordValid = password.length >= 6;

                const wilayahValid =
                    !/[0-9]/.test(provinsi) &&
                    !/[0-9]/.test(kabKota) &&
                    !/[0-9]/.test(kecamatan);

                if (
                    username === '' ||
                    email === '' ||
                    noHp === '' ||
                    password === '' ||
                    alamat === '' ||
                    provinsi === '' ||
                    kabKota === '' ||
                    kecamatan === ''
                ) {
                    event.preventDefault();
                    showInvalidPopup('Semua field<br>wajib diisi');
                    return;
                }

                if (!emailValid) {
                    event.preventDefault();
                    showInvalidPopup('Email tidak valid.<br>Masukkan email yang dapat dihubungi');
                    return;
                }

                if (!noHpValid) {
                    event.preventDefault();
                    showInvalidPopup('Nomor telepon<br>hanya boleh angka');
                    return;
                }

                if (!passwordValid) {
                    event.preventDefault();
                    showInvalidPopup('Password minimal<br>6 karakter');
                    return;
                }

                if (!wilayahValid) {
                    event.preventDefault();
                    showInvalidPopup('Data kota/kabupaten/provinsi<br>tidak valid');
                    return;
                }
            });

            function showInvalidPopup(message) {
                const popup = document.getElementById('popupInvalid');
                const messageBox = document.getElementById('popupInvalidMessage');

                messageBox.innerHTML = message;

                popup.style.display = '';
                popup.classList.remove('hidden');
                popup.classList.add('flex');
            }

            function showPopup(id) {
                const popup = document.getElementById(id);

                popup.style.display = '';
                popup.classList.remove('hidden');
                popup.classList.add('flex');
            }

            function closePopup(popup) {
                popup.classList.add('hidden');
                popup.classList.remove('flex');
                popup.style.display = '';
            }

            function closePopup(popup) {
                popup.classList.add('hidden');
                popup.style.display = 'none';
            }

            document.querySelectorAll('.popup').forEach(function(popup) {
                popup.addEventListener('click', function() {
                    closePopup(popup);
                });
            });

            @if (session('popup') === 'required')
                showInvalidPopup('Semua field<br>wajib diisi');
            @endif

            @if (session('popup') === 'email_invalid')
                showInvalidPopup('Email tidak valid.<br>Masukkan email yang dapat dihubungi');
            @endif

            @if (session('popup') === 'phone_invalid')
                showInvalidPopup('Nomor telepon<br>hanya boleh angka');
            @endif

            @if (session('popup') === 'password_invalid')
                showInvalidPopup('Password minimal<br>6 karakter');
            @endif

            @if (session('popup') === 'wilayah_invalid')
                showInvalidPopup('Data kota/kabupaten/provinsi<br>tidak valid');
            @endif

            @if (session('popup') === 'invalid')
                showInvalidPopup('Data tidak valid.<br>Silahkan isi kembali');
            @endif

            @if (session('popup') === 'registered')
                showPopup('popupRegistered');
            @endif

            @if (session('popup') === 'success')
                showPopup('popupSuccess');
            @endif
        </script>
</body>
</html>
