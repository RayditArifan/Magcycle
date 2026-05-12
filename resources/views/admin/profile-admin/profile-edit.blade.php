@extends('layouts.admin')

@section('page_title', 'Edit Profil Saya')

@section('content')
<section class="p-6 md:p-10">
    <div class="ml-4 md:ml-8 max-w-[790px] rounded-[20px] border border-[#24b18a] bg-white px-6 py-6 md:px-12 md:py-10">
        <div class="mb-8 flex items-center justify-between">
            <h2 class="text-[24px] md:text-[26px] font-bold text-[#4a4a4a]">
                Edit Profil Saya
            </h2>

            <a href="{{ route('admin.profile') }}"
               class="inline-flex h-[46px] min-w-[100px] items-center justify-center rounded-[12px] border border-gray-400 px-6 text-[16px] font-medium text-gray-600 hover:bg-gray-100 transition">
                Batal
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-[220px_1fr] items-center gap-y-4 text-[18px]">
                <label class="text-[#333]">Username</label>
                <input type="text" name="username" value="{{ old('username', $profile->username ?? '') }}"
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]">

                <label class="text-[#333]">Email</label>
                <input type="email" name="email" value="{{ old('email', $profile->email ?? '') }}"
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]">

                <label class="text-[#333]">Nomor Telepon</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $profile->no_hp ?? '') }}"
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]">

                <label class="text-[#333]">Password Lama</label>
                <div class="relative w-full max-w-[360px]">
                    <input
                        type="password"
                        name="old_password"
                        id="old_password"
                        placeholder="Masukkan password lama"
                        class="h-[42px] w-full rounded-[10px] border border-[#555] bg-white px-4 pr-12 text-[#4a4a4a] outline-none focus:border-[#24b18a]"
                    >
        <button
            type="button"
            data-toggle-password
            data-target="old_password"
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

                <label class="text-[#333]">Password Baru</label>
                <div>
                    <div class="relative w-full max-w-[360px]">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Kosongkan jika tidak diganti"
                            class="h-[42px] w-full rounded-[10px] border border-[#555] bg-white px-4 pr-12 text-[#4a4a4a] outline-none focus:border-[#24b18a]"
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

                    <p class="mt-1 text-sm font-semibold text-[#66708a]">
                        Password baru minimal 6 karakter
                    </p>
                </div>

                <label class="text-[#333]">Alamat</label>
                <input type="text" name="alamat" value="{{ old('alamat', $profile->alamat ?? '') }}"
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]">

                <label class="text-[#333]">Provinsi</label>
                <input
                    type="text"
                    value="Jawa Timur"
                    readonly
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-gray-100 px-4 text-[#4a4a4a] outline-none"
                >

                <label class="text-[#333]">Kabupaten/Kota</label>
                <select
                    name="id_kota"
                    id="id_kota"
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]"
                >
                    <option value="">Pilih Kab/Kota</option>
                    @foreach ($kabKotaList as $kota)
                        <option value="{{ $kota->id_kota }}"
                            {{ (old('id_kota', $profile->id_kota ?? '') == $kota->id_kota) ? 'selected' : '' }}>
                            {{ $kota->nama_kab_kota }}
                        </option>
                    @endforeach
                </select>

                <label class="text-[#333]">Kecamatan</label>
                <select
                    name="id_kecamatan"
                    id="id_kecamatan"
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]"
                >
                    <option value="">Pilih Kecamatan</option>
                    @foreach ($kecamatanList as $kec)
                        <option value="{{ $kec->id_kecamatan }}"
                            {{ (old('id_kecamatan', $profile->id_kecamatan ?? '') == $kec->id_kecamatan) ? 'selected' : '' }}>
                            {{ $kec->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit"
                    class="inline-flex h-[46px] min-w-[120px] items-center justify-center rounded-[12px] bg-[#24b18a] px-6 text-[17px] font-semibold text-white hover:bg-[#1e9d79] transition">
                    Simpan
                </button>

                <a href="{{ route('admin.profile') }}"
                   class="inline-flex h-[46px] min-w-[100px] items-center justify-center rounded-[12px] border border-gray-400 px-6 text-[16px] font-medium text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>


    <script>
        const kabKotaSelect    = document.getElementById('id_kota');
        const kecamatanSelect  = document.getElementById('id_kecamatan');
        const selectedKecamatan = @json(old('id_kecamatan', $profile->id_kecamatan ?? null));
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

        // Pre-load kecamatan sesuai kab/kota yang sudah dipilih
        if (kabKotaSelect.value) {
            loadKecamatan(kabKotaSelect.value, selectedKecamatan);
        }

        kabKotaSelect.addEventListener('change', function () {
            loadKecamatan(this.value, null);
        });
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

</section>
@endsection
