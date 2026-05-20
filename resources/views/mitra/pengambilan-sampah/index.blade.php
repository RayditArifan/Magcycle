<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengambilan Sampah - MagCycle</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f6f6f6] text-[#4a4a4a]">
    <div class="flex min-h-screen">
        @include('partials.mitra-sidebar')

        <div class="flex min-h-screen flex-1 flex-col">
            {{-- Topbar --}}
            <header class="border-b border-[#c8c8c8] bg-white">
                <div class="flex items-center justify-between" style="height: 70px; padding-left: 50px; padding-right: 50px;">
                    <h1 class="text-[38px] font-medium text-[#4a4a4a]">
                        Pengambilan Sampah
                    </h1>

                    @include('partials.mitra-topbar-actions', [
                        'notificationCount' => $notificationCount ?? 0
                    ])
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 px-10 pb-10">
                <section class="px-8 py-8 md:px-10">
                        <div class="overflow-hidden rounded-[18px] border border-[#1fa16f] bg-white">
                            <div class="flex items-center justify-between border-b border-[#1fa16f] px-10 py-5">
                                <h2 class="text-[24px] font-semibold text-[#4a4a4a]">
                                    Pengajuan Jadwal Pengambilan Sampah
                                </h2>

                                <div class="flex items-center gap-5">
                                    <button
                                        type="button"
                                        id="openCreateModal"
                                        class="rounded-xl border border-[#4a4a4a] px-5 py-3 text-[18px] font-semibold text-[#4a4a4a] transition hover:bg-[#e8f6f1]"
                                    >
                                        + Buat Jadwal
                                    </button>

                                    <a
                                        href="{{ route('mitra.pengambilan-sampah.riwayat') }}"
                                        class="rounded-xl border border-[#4a4a4a] px-5 py-3 text-[18px] font-semibold text-[#4a4a4a] transition hover:bg-[#e8f6f1]"
                                    >
                                        Riwayat Pengambilan
                                    </a>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[900px] text-left text-[#4a4a4a]">
                                    <thead>
                                        <tr class="text-[18px] font-semibold">
                                            <th class="px-10 py-4 font-semibold">Tanggal Pengambilan</th>
                                            <th class="px-10 py-4 font-semibold">Berat Sampah</th>
                                            <th class="px-10 py-4 font-semibold">Alamat</th>
                                            <th class="px-10 py-4 font-semibold">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-[18px]">
                                        @forelse ($jadwalPengambilan as $jadwal)
                                            <tr
                                                class="cursor-pointer odd:bg-[#93d1bd] even:bg-white hover:bg-[#bfe6da]"
                                                onclick="window.location='{{ route('mitra.pengambilan-sampah.show', $jadwal->id) }}'"
                                            >
                                                <td class="px-10 py-4">
                                                    {{ \Carbon\Carbon::parse($jadwal->tanggal_pengambilan)->translatedFormat('d F Y') }}
                                                </td>
                                                <td class="px-10 py-4">
                                                    {{ rtrim(rtrim(number_format((float) $jadwal->berat_sampah, 2, ',', '.'), '0'), ',') }} kg
                                                </td>
                                                <td class="max-w-[420px] truncate px-10 py-4">
                                                    {{ $jadwal->alamat_pengambilan }}
                                                </td>
                                                <td class="px-10 py-4">
                                                    @if ($jadwal->status_text === 'menunggu konfirmasi')
                                                        <span class="inline-flex items-center rounded bg-[#d38b00] px-2 py-1 text-[13px] font-medium leading-tight text-white">
                                                            &bull; Menunggu<br>Konfirmasi
                                                        </span>
                                                    @elseif ($jadwal->status_text === 'disetujui')
                                                        <span class="inline-flex items-center rounded bg-[#087250] px-2 py-1 text-[13px] font-medium text-white">
                                                            &bull; Disetujui
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center rounded bg-[#4a4a4a] px-2 py-1 text-[13px] font-medium text-white">
                                                            &bull; {{ $jadwal->status_label }}
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-10 py-10 text-center text-[18px] text-[#4a4a4a]">
                                                    Jadwal pengambilan sampah belum tersedia.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                    <div id="createModal" class="fixed inset-0 z-[9998] hidden items-center justify-center bg-black/45">
                        <div class="relative w-[675px] rounded-md border border-[#252525] bg-white px-32 py-12 shadow-xl">
                            <button type="button" id="closeCreateModal" class="absolute right-5 top-4 text-[#4a4a4a]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <div class="text-center">
                                <h3 class="text-[26px] font-bold text-[#4a4a4a]">Buat Jadwal Pengambilan</h3>
                                <p class="mt-2 text-[17px] leading-relaxed text-[#66708a]">
                                    Isi form berikut untuk membuat jadwal<br>pengambilan sampah
                                </p>
                            </div>

                            <form method="POST" action="{{ route('mitra.pengambilan-sampah.store') }}" class="mt-5 space-y-4">
                                @csrf

                                <div>
                                    <label for="tanggal_pengambilan" class="block text-[17px] font-medium text-[#66708a]">Tanggal Pengambilan</label>
                                    <input
                                        id="tanggal_pengambilan"
                                        name="tanggal_pengambilan"
                                        type="date"
                                        value="{{ old('tanggal_pengambilan') }}"
                                        class="mt-1 h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#4a4a4a] outline-none focus:border-[#1fa16f]"
                                    >
                                    @error('tanggal_pengambilan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="berat_sampah" class="block text-[17px] font-medium text-[#66708a]">Berat Sampah</label>
                                    <input
                                        id="berat_sampah"
                                        name="berat_sampah"
                                        type="number"
                                        min="0.1"
                                        step="0.1"
                                        value="{{ old('berat_sampah') }}"
                                        placeholder="Contoh : 3 kg"
                                        class="mt-1 h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#4a4a4a] outline-none focus:border-[#1fa16f]"
                                    >
                                    @error('berat_sampah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="alamat" class="block text-[17px] font-medium text-[#66708a]">Alamat</label>
                                    <input
                                        id="alamat"
                                        name="alamat"
                                        type="text"
                                        maxlength="100"
                                        value="{{ old('alamat') }}"
                                        placeholder="Contoh: Jl. Sigma Permai II Blok Z No. 123"
                                        class="mt-1 h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#4a4a4a] outline-none focus:border-[#1fa16f]"
                                    >
                                    @error('alamat')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label for="id_kota" class="block text-[17px] font-medium text-[#66708a]">Kabupaten/Kota</label>
                                        <select
                                            id="id_kota"
                                            name="id_kota"
                                            class="mt-1 h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#4a4a4a] outline-none focus:border-[#1fa16f]"
                                        >
                                            <option value="">Pilih</option>
                                            @foreach ($kabKotaList as $kota)
                                                <option value="{{ $kota->id_kota }}"
                                                    {{ old('id_kota') == $kota->id_kota ? 'selected' : '' }}>
                                                    {{ $kota->nama_kab_kota }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_kota')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="id_kecamatan" class="block text-[17px] font-medium text-[#66708a]">Kecamatan</label>
                                        <select
                                            id="id_kecamatan"
                                            name="id_kecamatan"
                                            class="mt-1 h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#4a4a4a] outline-none focus:border-[#1fa16f]"
                                        >
                                            <option value="">Pilih</option>
                                        </select>
                                        @error('id_kecamatan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="pt-5 text-center">
                                    <button type="submit" class="rounded bg-[#1fa16f] px-12 py-4 text-[16px] font-bold text-white transition hover:bg-[#178a5d]">
                                        KIRIM
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const modal = document.getElementById('createModal');
                            const openButton = document.getElementById('openCreateModal');
                            const closeButton = document.getElementById('closeCreateModal');
                            const kabKotaSelect   = document.getElementById('id_kota');
                            const kecamatanSelect = document.getElementById('id_kecamatan');
                            const oldIdKecamatan  = @json(old('id_kecamatan'));
                            const kecamatanApiUrl = '{{ url('/api/kecamatan') }}';

                            function loadKecamatan(idKota, preselect) {
                                kecamatanSelect.innerHTML = '<option value="">Pilih</option>';
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
                                loadKecamatan(kabKotaSelect.value, oldIdKecamatan);
                            }

                            kabKotaSelect.addEventListener('change', function () {
                                loadKecamatan(this.value, null);
                            });

                            function openModal() {
                                modal.classList.remove('hidden');
                                modal.classList.add('flex');
                            }

                            function closeModal() {
                                modal.classList.add('hidden');
                                modal.classList.remove('flex');
                            }

                            openButton?.addEventListener('click', openModal);
                            closeButton?.addEventListener('click', closeModal);

                            modal?.addEventListener('click', function (event) {
                                if (event.target === modal) closeModal();
                            });

                            @if ($errors->any())
                                openModal();
                            @endif
                        });
                    </script>
            </main>
        </div>
    </div>

    @includeIf('partials.success-popup')
    @includeIf('partials.error-popup')
</body>
</html>
