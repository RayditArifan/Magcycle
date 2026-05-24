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

<div class="px-8 md:px-10 py-20 overflow-x-hidden">
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
@endsection
