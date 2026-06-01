@extends('layouts.admin')

@section('page_title', 'LAPORANKU')

@section('content')
@php
    $namaBulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];
@endphp

<div class="px-8 md:px-10 py-10 overflow-x-hidden">

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.laporanku.laporan-produksi') }}" class="mb-5 flex items-center justify-end gap-3">
        <div class="flex items-center gap-1 rounded-lg bg-[#1fa16f] px-3 py-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <input
                type="text"
                name="dari"
                value="{{ $dari ?? '' }}"
                placeholder="mm/yyyy"
                maxlength="7"
                id="inputDari"
                class="w-[80px] bg-transparent text-white placeholder-white/70 text-[15px] outline-none"
            >
        </div>

        <span class="text-[17px] font-semibold text-[#4a4a4a]">s/d</span>

        <div class="flex items-center gap-1 rounded-lg bg-[#1fa16f] px-3 py-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <input
                type="text"
                name="sampai"
                value="{{ $sampai ?? '' }}"
                placeholder="mm/yyyy"
                maxlength="7"
                id="inputSampai"
                class="w-[80px] bg-transparent text-white placeholder-white/70 text-[15px] outline-none"
            >
        </div>

        <button type="submit" class="hidden" id="filterSubmit"></button>
    </form>

    <div class="w-full rounded-2xl border border-[#159b78] overflow-hidden bg-white">
        <table class="w-full table-fixed text-[18px] text-[#3f3f3f]">
            <thead>
                <tr class="bg-white">
                    <th class="w-1/4 px-4 py-6 text-center font-semibold">Total Panen</th>
                    <th class="w-1/4 px-4 py-6 text-center font-semibold">Total Kasgot</th>
                    <th class="w-1/4 px-4 py-6 text-center font-semibold">Bulan</th>
                    <th class="w-1/4 px-4 py-6 text-center font-semibold">Tahun</th>
                </tr>
            </thead>

            <tbody>
                @forelse($laporanProduksi as $item)
                    <tr class="{{ $loop->odd ? 'bg-[#8ccfbd]' : 'bg-white' }}">
                        <td class="px-4 py-7 text-center whitespace-nowrap">
                            {{ rtrim(rtrim(number_format($item->total_panen, 2, '.', ''), '0'), '.') }} kg
                        </td>

                        <td class="px-4 py-7 text-center whitespace-nowrap">
                            {{ rtrim(rtrim(number_format($item->total_kasgot, 2, '.', ''), '0'), '.') }} kg
                        </td>

                        <td class="px-4 py-7 text-center">
                            {{ $namaBulan[(int) $item->bulan] ?? $item->bulan }}
                        </td>

                        <td class="px-4 py-7 text-center">
                            {{ $item->tahun }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-gray-500">
                            Belum ada laporan produksi untuk lokasi admin ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-5 py-4">
            {{ $laporanProduksi->links() }}
        </div>
    </div>
</div>

<script>
    function enforceMonthYear(input) {
        input.addEventListener('input', function () {
            let val = this.value.replace(/\D/g, '');
            if (val.length > 2) {
                val = val.slice(0, 2) + '/' + val.slice(2, 6);
            }
            this.value = val;
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                document.getElementById('filterSubmit').click();
            }
        });
    }

    enforceMonthYear(document.getElementById('inputDari'));
    enforceMonthYear(document.getElementById('inputSampai'));

    // Auto-submit when both fields are complete (mm/yyyy = 7 chars)
    ['inputDari', 'inputSampai'].forEach(function (id) {
        document.getElementById(id).addEventListener('input', function () {
            const dari = document.getElementById('inputDari').value;
            const sampai = document.getElementById('inputSampai').value;
            if (dari.length === 7 && sampai.length === 7) {
                document.getElementById('filterSubmit').click();
            }
        });
    });
</script>
@endsection

