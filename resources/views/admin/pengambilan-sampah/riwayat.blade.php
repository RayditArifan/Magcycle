@extends('layouts.admin')

@section('page_title', 'Riwayat Pengambilan Sampah')

@section('content')
    <section class="px-8 py-8 md:px-10">
        <form method="GET" action="{{ route('admin.pengambilan-sampah.riwayat') }}" class="mb-3 flex items-center gap-3">
            <div class="flex h-[46px] flex-1 items-center rounded-xl border border-[#4a4a4a] bg-white px-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-4 h-6 w-6 shrink-0 text-[#4a4a4a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari username"
                    class="h-[34px] w-full rounded-lg border border-[#7a7a7a] px-4 text-[16px] text-[#4a4a4a] outline-none placeholder:text-[#8a8a8a]"
                >
            </div>

            <div class="relative">
                <input
                    id="tanggalFilter"
                    type="date"
                    name="tanggal"
                    value="{{ request('tanggal') }}"
                    class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                    onchange="this.form.submit()"
                >
                <button
                    type="button"
                    onclick="document.getElementById('tanggalFilter').showPicker ? document.getElementById('tanggalFilter').showPicker() : document.getElementById('tanggalFilter').click()"
                    class="flex h-[46px] w-[62px] items-center justify-center rounded-md bg-[#1fa16f] text-white"
                    aria-label="Filter tanggal"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" />
                    </svg>
                </button>
            </div>

            @if (request()->filled('search') || request()->filled('tanggal'))
                <a href="{{ route('admin.pengambilan-sampah.riwayat') }}" class="rounded-md border border-[#4a4a4a] px-4 py-3 text-[14px] font-semibold text-[#4a4a4a] transition hover:bg-[#e8f6f1]">
                    Reset
                </a>
            @endif
        </form>

        <div class="overflow-hidden rounded-[14px] border border-[#1fa16f] bg-white">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1080px] text-left text-[#4a4a4a]">
                    <thead>
                        <tr class="text-[17px] font-semibold">
                            <th class="px-8 py-4 font-semibold">Username</th>
                            <th class="px-8 py-4 font-semibold">Tanggal Pengambilan</th>
                            <th class="px-8 py-4 font-semibold">Berat Sampah</th>
                            <th class="px-8 py-4 font-semibold">Status</th>
                            <th class="px-8 py-4 font-semibold">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="text-[17px]">
                        @forelse ($riwayatPengambilan as $jadwal)
                            <tr class="odd:bg-[#93d1bd] even:bg-white">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#d9dde0]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#161616]" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm-9 9a9 9 0 0 1 18 0H3Z" />
                                            </svg>
                                        </div>
                                        <span>{{ $jadwal->username ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal_pengambilan)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-8 py-4">
                                    {{ rtrim(rtrim(number_format((float) $jadwal->berat_sampah, 2, ',', '.'), '0'), ',') }} kg
                                </td>
                                <td class="px-8 py-4">
                                    @if ($jadwal->status_text === 'selesai')
                                        <span class="inline-flex items-center rounded bg-[#087250] px-2 py-1 text-[12px] font-medium text-white">
                                            &bull; Selesai
                                        </span>
                                    @elseif ($jadwal->status_text === 'ditolak')
                                        <span class="inline-flex items-center rounded bg-[#ff1010] px-2 py-1 text-[12px] font-medium text-white">
                                            &bull; Ditolak
                                        </span>
                                    @elseif (in_array($jadwal->status_text, ['batal', 'dibatalkan'], true))
                                        <span class="inline-flex items-center rounded bg-[#ff1010] px-2 py-1 text-[12px] font-medium text-white">
                                            &bull; Dibatalkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded bg-[#4a4a4a] px-2 py-1 text-[12px] font-medium text-white">
                                            &bull; {{ $jadwal->status_label }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-3">
                                    <div class="flex items-center justify-between gap-4">
                                        <p class="max-w-[330px] text-[12px] leading-tight text-[#4a4a4a]">
                                            {{ $jadwal->catatan ?: '-' }}
                                        </p>

                                        @if (!in_array($jadwal->status_text, ['ditolak', 'batal', 'dibatalkan']) && empty($jadwal->catatan))
                                            <button
                                                type="button"
                                                data-open-catatan
                                                data-action="{{ route('admin.pengambilan-sampah.simpan-catatan', $jadwal->id) }}"
                                                data-catatan="{{ e($jadwal->catatan ?? '') }}"
                                                class="shrink-0 rounded-md border border-[#4a4a4a] px-4 py-2 text-[11px] font-medium leading-tight text-[#252525] transition hover:bg-white"
                                            >
                                                Beri<br>Catatan
                                            </button>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-10 text-center text-[17px] text-[#4a4a4a]">
                                    Riwayat pengambilan sampah belum tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between border-t border-[#1fa16f] px-8 py-3">
                <p class="text-[17px] text-[#4a4a4a]">
                    Menampilkan {{ $riwayatPengambilan->count() }} dari {{ $riwayatPengambilan->total() }} jadwal
                </p>

                @if ($riwayatPengambilan->hasPages())
                    <div class="flex items-center gap-3">
                        @if ($riwayatPengambilan->onFirstPage())
                            <span class="flex h-8 w-8 items-center justify-center rounded border border-[#1fa16f] text-[#4a4a4a] opacity-50">&lt;</span>
                        @else
                            <a href="{{ $riwayatPengambilan->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded border border-[#1fa16f] text-[#4a4a4a] transition hover:bg-[#e8f6f1]">&lt;</a>
                        @endif

                        @foreach ($riwayatPengambilan->getUrlRange(1, $riwayatPengambilan->lastPage()) as $page => $url)
                            @if ($page === $riwayatPengambilan->currentPage())
                                <span class="flex h-8 w-8 items-center justify-center rounded border border-[#1fa16f] bg-[#93d1bd] text-[#4a4a4a]">{{ $page }}</span>
                            @elseif ($page <= 3 || abs($page - $riwayatPengambilan->currentPage()) <= 1 || $page === $riwayatPengambilan->lastPage())
                                <a href="{{ $url }}" class="flex h-8 w-8 items-center justify-center rounded border border-[#1fa16f] text-[#4a4a4a] transition hover:bg-[#e8f6f1]">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($riwayatPengambilan->hasMorePages())
                            <a href="{{ $riwayatPengambilan->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded border border-[#1fa16f] text-[#4a4a4a] transition hover:bg-[#e8f6f1]">&gt;</a>
                        @else
                            <span class="flex h-8 w-8 items-center justify-center rounded border border-[#1fa16f] text-[#4a4a4a] opacity-50">&gt;</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <a
            href="{{ route('admin.pengambilan-sampah.index') }}"
            class="mt-9 inline-flex rounded bg-[#ff1010] px-8 py-4 text-[16px] font-bold text-white transition hover:bg-[#dd0000]"
        >
            KEMBALI
        </a>
    </section>

    <div id="catatanModal" class="fixed inset-0 z-[9998] hidden items-center justify-center bg-black/10">
        <div class="relative w-[585px] rounded-md border border-[#252525] bg-white px-28 py-12 shadow-xl">
            <button type="button" id="closeCatatanModal" class="absolute right-5 top-4 text-[#4a4a4a]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="text-center">
                <h3 class="text-[26px] font-bold text-[#4a4a4a]">Catatan</h3>
                <p class="mt-5 text-[17px] text-[#66708a]">Isi form berikut untuk memberi catatan baru</p>
            </div>

            <form id="catatanForm" method="POST" action="" class="mt-9">
                @csrf
                @method('PATCH')

                <label for="catatanInput" class="mb-2 block text-[17px] font-medium text-[#66708a]">Catatan</label>
                <textarea
                    id="catatanInput"
                    name="catatan"
                    rows="7"
                    maxlength="100"
                    required
                    placeholder="Isi catatan atau peringatan ke mitra"
                    class="w-full resize-none border border-[#cfcfcf] px-5 py-4 text-[15px] text-[#4a4a4a] outline-none placeholder:text-[#8a8a8a] focus:border-[#1fa16f]"
                ></textarea>
                @error('catatan')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-5 flex justify-center">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-3 rounded bg-[#1fa16f] px-5 py-3 text-[15px] font-bold text-white transition hover:bg-[#17895d]"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3h11l3 3v15H5V3Zm4 0v6h7V3M8 15h8" />
                        </svg>
                        SIMPAN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('catatanModal');
            const form = document.getElementById('catatanForm');
            const input = document.getElementById('catatanInput');
            const closeButton = document.getElementById('closeCatatanModal');

            document.querySelectorAll('[data-open-catatan]').forEach(function (button) {
                button.addEventListener('click', function () {
                    form.action = button.dataset.action;
                    input.value = button.dataset.catatan || '';
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    input.focus();
                });
            });

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                form.action = '';
                input.value = '';
            }

            closeButton.addEventListener('click', closeModal);

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
        });
    </script>
@endsection
