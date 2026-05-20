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
                        <div class="relative rounded-[18px] border border-[#1fa16f] bg-white px-7 py-7">
                            <a href="{{ route('mitra.pengambilan-sampah.index') }}" class="absolute right-5 top-5 text-[#4a4a4a]" aria-label="Kembali">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </a>

                            <h2 class="text-[24px] font-semibold text-[#4a4a4a]">
                                Detail Pengajuan Pengambilan Sampah
                            </h2>

                            <div class="mt-5 max-w-[1100px] space-y-8 text-[18px] text-[#4a4a4a]">
                                <div class="grid grid-cols-[210px_1fr] items-center gap-5">
                                    <p class="font-medium">Tanggal Pengambilan</p>
                                    <div class="rounded-xl border border-[#4a4a4a] px-6 py-2">
                                        {{ \Carbon\Carbon::parse($jadwal->tanggal_pengambilan)->translatedFormat('d F Y') }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-[210px_1fr] items-center gap-5">
                                    <p class="font-medium">Berat Sampah</p>
                                    <div class="rounded-xl border border-[#4a4a4a] px-6 py-2">
                                        {{ rtrim(rtrim(number_format((float) $jadwal->berat_sampah, 2, ',', '.'), '0'), ',') }} kg
                                    </div>
                                </div>

                                <div class="grid grid-cols-[210px_1fr] items-start gap-5">
                                    <p class="pt-1 font-medium">Alamat</p>
                                    <div class="min-h-[125px] rounded-xl border border-[#4a4a4a] px-6 py-4 leading-relaxed">
                                        {{ $jadwal->alamat_pengambilan }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-[210px_1fr] items-center gap-5">
                                    <p class="font-medium">Status</p>
                                    <div>
                                        @if ($jadwal->status_text === 'menunggu konfirmasi')
                                            <span class="inline-flex items-center rounded bg-[#d38b00] px-3 py-1 text-[13px] font-medium leading-tight text-white">
                                                &bull; Menunggu<br>Konfirmasi
                                            </span>
                                        @elseif ($jadwal->status_text === 'disetujui')
                                            <span class="inline-flex items-center rounded bg-[#087250] px-3 py-1 text-[13px] font-medium text-white">
                                                &bull; Disetujui
                                            </span>
                                        @elseif ($jadwal->status_text === 'ditolak')
                                            <span class="inline-flex items-center rounded bg-[#ff1010] px-3 py-1 text-[13px] font-medium text-white">
                                                &bull; Ditolak
                                            </span>
                                        @elseif (in_array($jadwal->status_text, ['batal', 'dibatalkan'], true))
                                            <span class="inline-flex items-center rounded bg-[#ff1010] px-3 py-1 text-[13px] font-medium text-white">
                                                &bull; Dibatalkan
                                            </span>
                                        @elseif ($jadwal->status_text === 'selesai')
                                            <span class="inline-flex items-center rounded bg-[#087250] px-3 py-1 text-[13px] font-medium text-white">
                                                &bull; Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded bg-[#4a4a4a] px-3 py-1 text-[13px] font-medium text-white">
                                                &bull; {{ $jadwal->status_label }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if ($jadwal->catatan)
                                    <div class="grid grid-cols-[210px_1fr] items-start gap-5">
                                        <p class="pt-1 font-medium">Catatan</p>
                                        <div class="min-h-[70px] rounded-xl border border-[#4a4a4a] px-6 py-4 leading-relaxed">
                                            {{ $jadwal->catatan }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if ($jadwal->status_text === 'menunggu konfirmasi')
                                <div class="mt-14 flex justify-end">
                                    <button
                                        type="button"
                                        id="openCancelModal"
                                        class="rounded-xl border border-[#1fa16f] px-5 py-4 text-[17px] font-semibold text-[#1fa16f] transition hover:bg-[#e8f6f1]"
                                    >
                                        Batalkan Jadwal
                                    </button>
                                </div>
                            @endif
                        </div>
                    </section>

                    <div id="cancelModal" class="fixed inset-0 z-[9998] hidden items-center justify-center bg-black/45">
                        <div class="relative w-[390px] bg-white px-10 py-6 text-center shadow-xl">
                            <p class="text-[22px] font-bold leading-tight text-[#66708a]">
                                Apakah anda yakin ingin<br>membatalkan jadwal pengambilan<br>sampah?
                            </p>

                            <form method="POST" action="{{ route('mitra.pengambilan-sampah.batal', $jadwal->id) }}" class="mt-7 flex items-center justify-center gap-6">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="min-w-[135px] rounded bg-[#1fa16f] px-7 py-3 text-[16px] font-bold text-white transition hover:bg-[#178a5d]">
                                    YA
                                </button>
                                <button type="button" id="closeCancelModal" class="min-w-[135px] rounded bg-[#ff1010] px-7 py-3 text-[16px] font-bold text-white transition hover:bg-[#dd0000]">
                                    TIDAK
                                </button>
                            </form>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const modal = document.getElementById('cancelModal');
                            const openButton = document.getElementById('openCancelModal');
                            const closeButton = document.getElementById('closeCancelModal');

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
                                if (event.target === modal) {
                                    closeModal();
                                }
                            });
                        });
                    </script>
            </main>
        </div>
    </div>

    @includeIf('partials.success-popup')
    @includeIf('partials.error-popup')
</body>
</html>
