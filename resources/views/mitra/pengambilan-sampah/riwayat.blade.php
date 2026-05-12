<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengambilan Sampah - MagCycle</title>
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
                        Riwayat Pengambilan Sampah
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
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[980px] text-left text-[#4a4a4a]">
                                    <thead>
                                        <tr class="text-[18px] font-semibold">
                                            <th class="px-10 py-4 font-semibold">Tanggal Pengambilan</th>
                                            <th class="px-10 py-4 font-semibold">Berat Sampah</th>
                                            <th class="px-10 py-4 font-semibold">Alamat</th>
                                            <th class="px-10 py-4 font-semibold">Status</th>
                                            <th class="px-10 py-4 font-semibold">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-[18px]">
                                        @forelse ($riwayatPengambilan as $jadwal)
                                            <tr class="odd:bg-[#93d1bd] even:bg-white">
                                                <td class="px-10 py-4">
                                                    {{ \Carbon\Carbon::parse($jadwal->tanggal_pengambilan)->translatedFormat('d F Y') }}
                                                </td>
                                                <td class="px-10 py-4">
                                                    {{ rtrim(rtrim(number_format((float) $jadwal->berat_sampah, 2, ',', '.'), '0'), ',') }} kg
                                                </td>
                                                <td class="max-w-[350px] truncate px-10 py-4">
                                                    {{ $jadwal->alamat_pengambilan }}
                                                </td>
                                                <td class="px-10 py-4">
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
                                                            &bull; Batal
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center rounded bg-[#4a4a4a] px-2 py-1 text-[12px] font-medium text-white">
                                                            &bull; {{ $jadwal->status_label }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="max-w-[320px] px-10 py-4 text-[15px] leading-tight">
                                                    {{ $jadwal->catatan ?: '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-10 py-10 text-center text-[18px] text-[#4a4a4a]">
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

                        <div class="mt-10 flex justify-end">
                            <a
                                href="{{ route('mitra.pengambilan-sampah.index') }}"
                                class="inline-flex rounded bg-[#ff1010] px-8 py-4 text-[16px] font-bold text-white transition hover:bg-[#dd0000]"
                            >
                                KEMBALI
                            </a>
                        </div>
                    </section>
            </main>
        </div>
    </div>

    @includeIf('partials.success-popup')
    @includeIf('partials.error-popup')
</body>
</html>
