@extends('layouts.admin')

@section('page_title', 'LAPORANKU')

@section('content')
<div class="px-8 md:px-10 py-12 overflow-x-hidden">

    <div class="w-full rounded-2xl border border-[#159b78] overflow-hidden bg-white">
        <table class="w-full table-fixed text-[17px] text-[#3f3f3f]">
            <thead>
                <tr class="bg-white">
                    <th class="w-[80px] px-4 py-5"></th>
                    <th class="w-[22%] px-4 py-5 text-left font-semibold">Username</th>
                    <th class="w-[25%] px-4 py-5 text-left font-semibold">Tanggal Pengambilan</th>
                    <th class="w-[18%] px-4 py-5 text-left font-semibold">Berat Pengajuan</th>
                    <th class="w-[220px] px-4 py-5 text-center font-semibold"></th>
                </tr>
            </thead>

            <tbody>
                @forelse($dataPengambilan as $item)
                    <tr class="{{ $loop->odd ? 'bg-[#8ccfbd]' : 'bg-white' }}">
                        <td class="px-4 py-4">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center overflow-hidden rounded-full bg-[#D9E1E4]">
                                <svg
                                    viewBox="0 0 40 40"
                                    class="h-12 w-12 text-black"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <circle cx="20" cy="14" r="7"></circle>
                                    <path d="M7 38c1.6-9.2 7-14.5 13-14.5S31.4 28.8 33 38H7z"></path>
                                </svg>
                            </div>
                        </td>

                        <td class="px-4 py-4 truncate">{{ $item->username }}</td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->tanggal_pengambilan)->translatedFormat('d F Y') }}
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            {{ rtrim(rtrim(number_format($item->berat_sampah, 2, '.', ''), '0'), '.') }} kg
                        </td>

                        <td class="px-4 py-4 text-center">
                            <button
                                type="button"
                                class="open-modal rounded-xl border border-[#333] px-5 py-3 font-semibold hover:bg-white/40 whitespace-nowrap"
                                data-id="{{ $item->id }}"
                                data-username="{{ $item->username }}"
                                data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_pengambilan)->format('d/m/Y') }}"
                                data-berat="{{ rtrim(rtrim(number_format($item->berat_sampah, 2, '.', ''), '0'), '.') }}"
                            >
                                +
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-500">
                            Belum ada data pengambilan selesai yang dapat dibuat laporan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-5 py-4">
            {{ $dataPengambilan->links() }}
        </div>
    </div>
</div>

<div id="modalBg" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/20 px-4">
    <div class="relative w-full max-w-[680px] rounded-lg border-2 border-[#333] bg-white px-10 md:px-24 py-14 shadow-xl">
        <button id="closeModal" type="button" class="absolute right-6 top-3 text-5xl leading-none text-[#444]">
            &times;
        </button>

        <h2 class="text-center text-3xl font-bold text-[#4a4a4a]">Buat Laporan</h2>
        <p class="mb-9 mt-3 text-center text-lg text-gray-500">
            Isi form berikut untuk menambahkan berat valid
        </p>

        <form method="POST" action="{{ route('admin.laporanku.store-laporan-sampah') }}">
            @csrf

            <input type="hidden" name="pengambilan_sampah_id" id="pengambilanId">

            <label class="block text-lg font-medium text-gray-500">Username</label>
            <input id="username" type="text" readonly
                   class="mb-4 w-full rounded border border-gray-300 px-4 py-3 text-lg text-gray-600">

            <label class="block text-lg font-medium text-gray-500">Tanggal Pengambilan</label>
            <input id="tanggal" type="text" readonly
                   class="mb-4 w-full rounded border border-gray-300 px-4 py-3 text-lg text-gray-600">

            <label class="block text-lg font-medium text-gray-500">Berat Pengajuan</label>
            <input id="beratPengajuan" type="text" readonly
                   class="mb-4 w-full rounded border border-gray-300 px-4 py-3 text-lg text-gray-600">

            <label class="block text-lg font-medium text-gray-500">Berat Valid (kg)*</label>
            <input name="berat_valid" type="text" required
                   placeholder="Contoh : 3"
                   class="w-full rounded border border-gray-300 px-4 py-3 text-lg text-gray-600">

            <div class="mt-9 text-center">
                <button type="submit"
                        class="rounded-md bg-[#1da078] px-8 py-3 text-lg font-bold text-white hover:bg-[#168864]">
                    SIMPAN
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modalBg = document.getElementById('modalBg');
    const closeModal = document.getElementById('closeModal');

    document.querySelectorAll('.open-modal').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('pengambilanId').value = button.dataset.id;
            document.getElementById('username').value = button.dataset.username;
            document.getElementById('tanggal').value = button.dataset.tanggal;
            document.getElementById('beratPengajuan').value = button.dataset.berat + ' kg';

            modalBg.classList.remove('hidden');
            modalBg.classList.add('flex');
        });
    });

    closeModal.addEventListener('click', () => {
        modalBg.classList.add('hidden');
        modalBg.classList.remove('flex');
    });

    modalBg.addEventListener('click', e => {
        if (e.target === modalBg) {
            modalBg.classList.add('hidden');
            modalBg.classList.remove('flex');
        }
    });
</script>

@if(old('pengambilan_sampah_id'))
    @php
        $oldJadwal = $dataPengambilan->firstWhere('id', old('pengambilan_sampah_id'));
    @endphp
    @if($oldJadwal)
        @php
            $oldId = $oldJadwal->id;
            $oldUsername = $oldJadwal->username;
            $oldTanggal = \Carbon\Carbon::parse($oldJadwal->tanggal_pengambilan)->format('d/m/Y');
            $oldBerat = rtrim(rtrim(number_format($oldJadwal->berat_sampah, 2, '.', ''), '0'), '.') . ' kg';
            $oldBeratValid = old('berat_valid');
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('pengambilanId').value = @json($oldId);
                document.getElementById('username').value = @json($oldUsername);
                document.getElementById('tanggal').value = @json($oldTanggal);
                document.getElementById('beratPengajuan').value = @json($oldBerat);
                
                const beratValidInput = document.querySelector('input[name="berat_valid"]');
                if (beratValidInput) {
                    beratValidInput.value = @json($oldBeratValid);
                }

                const modalBg = document.getElementById('modalBg');
                modalBg.classList.remove('hidden');
                modalBg.classList.add('flex');
            });
        </script>
    @endif
@endif
@endsection
