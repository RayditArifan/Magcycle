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
               class="hidden h-[46px] min-w-[100px] items-center justify-center rounded-[12px] border border-gray-400 px-6 text-[16px] font-medium text-gray-600 hover:bg-gray-100 transition">
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
                <input type="text" value="{{ $profile->username ?? '' }}" readonly
                    class="h-[42px] w-full max-w-[360px] cursor-not-allowed rounded-[10px] border border-[#555] bg-gray-100 px-4 text-[#777] outline-none">

                <label class="text-[#333]">Email</label>
                <input type="email" name="email" value="{{ old('email', $profile->email ?? '') }}"
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]">

                <label class="text-[#333]">Nomor Telepon</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $profile->no_hp ?? '') }}"
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]">

                <label class="text-[#333]">Password Lama</label>
                <input type="password" name="old_password" placeholder="Masukkan password lama"
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]">

                <label class="text-[#333]">Password Baru</label>
                <div>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diganti"
                        class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]">

                    <p class="mt-1 text-sm font-semibold text-[#66708a]">
                        Password baru minimal 6 karakter
                    </p>
                </div>

                <label class="text-[#333]">Alamat</label>
                <input type="text" name="alamat" value="{{ old('alamat', $alamatParts['alamat'] ?? '') }}"
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]">

                <label class="text-[#333]">Provinsi</label>
                <input
                    type="text"
                    name="provinsi"
                    id="provinsi"
                    value="Jawa Timur"
                    readonly
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-gray-100 px-4 text-[#4a4a4a] outline-none"
                >

                <label class="text-[#333]">Kabupaten/Kota</label>
                <select
                    name="kab_kota"
                    id="kab_kota"
                    data-selected="{{ old('kab_kota', $alamatParts['kab_kota'] ?? '') }}"
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]"
                >
                    <option value="">Pilih Kab/Kota</option>
                </select>

                <label class="text-[#333]">Kecamatan</label>
                <select
                    name="kecamatan"
                    id="kecamatan"
                    data-selected="{{ old('kecamatan', $alamatParts['kecamatan'] ?? '') }}"
                    class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#4a4a4a] outline-none focus:border-[#24b18a]"
                >
                    <option value="">Pilih Kecamatan</option>
                </select>
            </div>

            <div class="mt-8 flex justify-end gap-4">
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
                'Kota',
                'Pesantren',
                'Gampengrejo',
                'Ngasem',
                'Puncu',
                'Semanu',
                'Banyakan',
                'Gurah',
                'Kepung',
                'Plosoklaten',
                'Pulung',
                'Pujon',
                'Tamanan'
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

        const selectedKabKota = kabKotaSelect.dataset.selected;
        const selectedKecamatan = kecamatanSelect.dataset.selected;

        function loadKabKota() {
            kabKotaSelect.innerHTML = '<option value="">Pilih Kab/Kota</option>';

            Object.keys(dataKecamatan).forEach(function (kabKota) {
                const option = document.createElement('option');
                option.value = kabKota;
                option.textContent = kabKota;

                if (kabKota === selectedKabKota) {
                    option.selected = true;
                }

                kabKotaSelect.appendChild(option);
            });
        }

        function loadKecamatan(kabKota, selectedValue = '') {
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

            if (!kabKota || !dataKecamatan[kabKota]) {
                return;
            }

            dataKecamatan[kabKota].forEach(function (kecamatan) {
                const option = document.createElement('option');
                option.value = kecamatan;
                option.textContent = kecamatan;

                if (kecamatan === selectedValue) {
                    option.selected = true;
                }

                kecamatanSelect.appendChild(option);
            });
        }

        loadKabKota();

        if (selectedKabKota) {
            loadKecamatan(selectedKabKota, selectedKecamatan);
        }

        kabKotaSelect.addEventListener('change', function () {
            loadKecamatan(this.value);
        });
    </script>
</section>
@endsection
