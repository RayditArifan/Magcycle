@extends('layouts.admin')

@section('page_title', 'Pengambilan Sampah')

@section('content')
    <section class="px-8 py-8 md:px-10">
        <div class="overflow-hidden rounded-[18px] border border-[#1fa16f] bg-white">
            <div class="flex items-center justify-between border-b border-[#1fa16f] px-10 py-5">
                <h2 class="text-[24px] font-semibold text-[#4a4a4a]">
                    Daftar Jadwal Pengambilan Sampah
                </h2>

                <a
                    href="{{ route('admin.pengambilan-sampah.riwayat') }}"
                    class="rounded-xl border border-[#4a4a4a] px-5 py-3 text-[18px] font-semibold text-[#4a4a4a] transition hover:bg-[#e8f6f1]"
                >
                    Riwayat Pengambilan
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[980px] text-left text-[#4a4a4a]">
                    <thead>
                        <tr class="text-[18px] font-semibold">
                            <th class="px-10 py-4 font-semibold">Username</th>
                            <th class="px-10 py-4 font-semibold">Tanggal Pengambilan</th>
                            <th class="px-10 py-4 font-semibold">Berat Sampah</th>
                            <th class="px-10 py-4 font-semibold">Status</th>
                            <th class="px-10 py-4 font-semibold text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="text-[18px]">
                        @forelse ($jadwalPengambilan as $jadwal)
                            <tr class="odd:bg-[#93d1bd] even:bg-white">
                                <td class="px-10 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#d9dde0]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#161616]" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm-9 9a9 9 0 0 1 18 0H3Z" />
                                            </svg>
                                        </div>
                                        <span>{{ $jadwal->username ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-4">
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal_pengambilan)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-10 py-4">
                                    {{ rtrim(rtrim(number_format((float) $jadwal->berat_sampah, 2, ',', '.'), '0'), ',') }} kg
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
                                <td class="px-10 py-4">
                                    <div class="flex items-center justify-center gap-7">
                                        @if ($jadwal->status_text === 'menunggu konfirmasi')
                                            <button
                                                type="button"
                                                data-open-confirm
                                                data-title="Apakah anda yakin menyetujui jadwal pengambilan sampah?"
                                                data-action="{{ route('admin.pengambilan-sampah.update-status', $jadwal->id) }}"
                                                data-status-action="setuju"
                                                class="min-w-[82px] rounded-xl border border-[#4a4a4a] px-4 py-2 text-[14px] font-semibold text-[#4a4a4a] transition hover:bg-white"
                                            >
                                                SETUJU
                                            </button>

                                            <button
                                                type="button"
                                                data-open-confirm
                                                data-title="Apakah anda yakin menolak jadwal pengambilan sampah?"
                                                data-action="{{ route('admin.pengambilan-sampah.update-status', $jadwal->id) }}"
                                                data-status-action="tolak"
                                                class="min-w-[82px] rounded-xl border border-[#4a4a4a] px-4 py-2 text-[14px] font-semibold text-[#4a4a4a] transition hover:bg-white"
                                            >
                                                TOLAK
                                            </button>
                                        @elseif ($jadwal->status_text === 'disetujui')
                                            <button
                                                type="button"
                                                data-open-confirm
                                                data-title="Apakah anda yakin menyelesaikan jadwal pengambilan sampah?"
                                                data-action="{{ route('admin.pengambilan-sampah.update-status', $jadwal->id) }}"
                                                data-status-action="selesai"
                                                class="min-w-[88px] rounded-xl border border-[#4a4a4a] px-4 py-2 text-[14px] font-semibold text-[#4a4a4a] transition hover:bg-white"
                                            >
                                                SELESAI
                                            </button>
                                        @else
                                            <span class="text-sm text-[#4a4a4a]">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-10 py-10 text-center text-[18px] text-[#4a4a4a]">
                                    Belum ada jadwal pengambilan sampah yang perlu diproses.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div id="confirmActionModal" class="fixed inset-0 z-[9998] hidden items-center justify-center bg-black/20">
        <div class="w-[430px] bg-white px-10 py-8 text-center shadow-xl">
            <h3 id="confirmActionTitle" class="mb-7 text-[24px] font-extrabold leading-tight text-[#66708a]"></h3>

            <form id="confirmActionForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <input type="hidden" name="action" id="confirmActionValue">

                <div class="grid grid-cols-2 gap-6">
                    <button
                        type="submit"
                        class="rounded bg-[#1fa16f] px-8 py-3 text-[18px] font-bold text-white transition hover:bg-[#17895d]"
                    >
                        YA
                    </button>
                    <button
                        type="button"
                        id="closeConfirmActionModal"
                        class="rounded bg-[#ff1010] px-8 py-3 text-[18px] font-bold text-white transition hover:bg-[#dd0000]"
                    >
                        TIDAK
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('confirmActionModal');
            const title = document.getElementById('confirmActionTitle');
            const form = document.getElementById('confirmActionForm');
            const actionInput = document.getElementById('confirmActionValue');
            const closeButton = document.getElementById('closeConfirmActionModal');

            document.querySelectorAll('[data-open-confirm]').forEach(function (button) {
                button.addEventListener('click', function () {
                    title.textContent = button.dataset.title || 'Apakah anda yakin?';
                    form.action = button.dataset.action;
                    if (actionInput) actionInput.value = button.dataset.statusAction || '';
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                form.action = '';
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
