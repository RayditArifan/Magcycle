@extends('layouts.admin')

@section('page_title', 'MagPoin')

@section('content')
@php
    $formatRupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $formatAngka = fn ($value) => number_format((float) $value, 0, ',', '.');

    $walletClass = function ($wallet) {
        $wallet = strtolower($wallet);

        return match ($wallet) {
            'dana' => 'bg-[#1c85d8]',
            'shopeepay' => 'bg-[#f04b2f]',
            'ovo' => 'bg-[#8f098f]',
            'gopay' => 'bg-[#11a8cf]',
            default => 'bg-gray-500',
        };
    };

    $statusClass = function ($status) {
        $status = strtolower($status);

        return match ($status) {
            'dikonfirmasi' => 'bg-[#087a5b]',
            'ditolak' => 'bg-red-600',
            default => 'bg-gray-500',
        };
    };
@endphp

<div class="px-8 md:px-10 py-20">
    <div class="w-full rounded-2xl border border-[#159b78] overflow-hidden bg-white">
        <div class="flex items-center justify-between border-b border-[#159b78] px-8 py-5">
            <h2 class="text-[22px] font-semibold text-[#4a4a4a]">
                Riwayat Transaksi Poin Mitra
            </h2>

            <a
                href="{{ route('admin.magpoin.transaksi-poin') }}"
                class="rounded-xl border border-[#333] px-8 py-3 text-[17px] font-semibold text-[#4a4a4a] hover:bg-[#e9fff7] transition inline-block"
            >
                Kembali
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1050px] table-fixed text-[16px] text-[#3f3f3f]">
                <thead>
                    <tr class="bg-white">
                        <th class="w-[70px] px-3 py-4"></th>
                        <th class="w-[16%] px-3 py-4 text-left font-semibold">Username</th>
                        <th class="w-[14%] px-3 py-4 text-center font-semibold">Tanggal Pengajuan</th>
                        <th class="w-[13%] px-3 py-4 text-center font-semibold">Jumlah Poin<br>Ditukar</th>
                        <th class="w-[13%] px-3 py-4 text-center font-semibold">Nominal<br>Uang</th>
                        <th class="w-[12%] px-3 py-4 text-center font-semibold">Jenis<br>E-Wallet</th>
                        <th class="w-[15%] px-3 py-4 text-center font-semibold">Nomor<br>E-Wallet</th>
                        <th class="w-[12%] px-3 py-4 text-center font-semibold">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($riwayatTransaksi as $item)
                        <tr class="{{ $loop->odd ? 'bg-[#8ccfbd]' : 'bg-white' }}">
                            <td class="px-3 py-3">
                                <div class="mx-auto flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-[#D9E1E4]">
                                    <svg viewBox="0 0 40 40" class="h-10 w-10 text-black" fill="currentColor" aria-hidden="true">
                                        <circle cx="20" cy="14" r="7"></circle>
                                        <path d="M7 38c1.6-9.2 7-14.5 13-14.5S31.4 28.8 33 38H7z"></path>
                                    </svg>
                                </div>
                            </td>

                            <td class="px-3 py-3 truncate">
                                {{ $item->username }}
                            </td>

                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                            </td>

                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                {{ $formatAngka($item->poin_tukar) }}
                            </td>

                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                {{ $formatRupiah($item->nominal_uang) }}
                            </td>

                            <td class="px-3 py-3 text-center">
                                <span class="inline-block min-w-[90px] rounded px-3 py-1 text-xs font-semibold text-white {{ $walletClass($item->nama_ewallet) }}">
                                    {{ strtoupper($item->nama_ewallet) }}
                                </span>
                            </td>

                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                {{ $item->nomor_ewallet }}
                            </td>

                            <td class="px-3 py-3 text-center">
                                <span class="inline-block rounded px-3 py-1 text-xs font-semibold text-white {{ $statusClass($item->status_penukaran) }}">
                                    • {{ ucfirst($item->status_penukaran) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                Belum ada riwayat transaksi poin mitra.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4">
            {{ $riwayatTransaksi->links() }}
        </div>
    </div>
</div>
@endsection
