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
@endphp

<div class="px-8 md:px-10 py-8">
    <div class="w-full rounded-2xl border border-[#159b78] overflow-hidden bg-white">
        <div class="flex items-center justify-between border-b border-[#159b78] px-8 py-5">
            <h2 class="text-[22px] font-semibold text-[#4a4a4a]">
                Daftar Transaksi Poin Mitra
            </h2>

            <a href="{{ route('admin.magpoin.riwayat-transaksi') }}"
               class="rounded-xl border border-[#333] px-8 py-3 text-[17px] font-semibold text-[#4a4a4a] hover:bg-[#e9fff7] transition">
                Riwayat Transaksi
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] table-fixed text-[16px] text-[#3f3f3f]">
                <thead>
                    <tr class="bg-white">
                        <th class="w-[70px] px-3 py-4"></th>
                        <th class="w-[15%] px-3 py-4 text-left font-semibold">Username</th>
                        <th class="w-[13%] px-3 py-4 text-center font-semibold">Tanggal<br>Pengajuan</th>
                        <th class="w-[12%] px-3 py-4 text-center font-semibold">Jumlah Poin<br>Ditukar</th>
                        <th class="w-[12%] px-3 py-4 text-center font-semibold">Nominal<br>Uang</th>
                        <th class="w-[12%] px-3 py-4 text-center font-semibold">Jenis<br>E-Wallet</th>
                        <th class="w-[13%] px-3 py-4 text-center font-semibold">Nomor<br>E-Wallet</th>
                        <th class="w-[23%] px-3 py-4 text-center font-semibold">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($transaksiPoin as $item)
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

                            <td class="px-3 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <form method="POST" action="{{ route('admin.magpoin.transaksi-poin.setuju', $item->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <button type="submit"
                                                class="rounded-xl border border-[#333] px-5 py-2 text-sm font-semibold text-[#4a4a4a] hover:bg-white/50">
                                            SETUJU
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.magpoin.transaksi-poin.tolak', $item->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <button type="submit"
                                                class="rounded-xl border border-[#333] px-5 py-2 text-sm font-semibold text-[#4a4a4a] hover:bg-white/50">
                                            TOLAK
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                Belum ada transaksi poin yang menunggu konfirmasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4">
            {{ $transaksiPoin->links() }}
        </div>
    </div>
</div>

@if(session('success'))
    <div id="flashPopup" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/10 px-4">
        <div class="w-full max-w-[440px] bg-white px-8 py-8 text-center shadow-xl">
            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-[#1da078] text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-11 w-11" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h3 class="text-[26px] font-bold leading-tight text-gray-500">
                {{ session('success') }}
            </h3>
        </div>
    </div>
@endif

@if(session('error'))
    <div id="flashPopup" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/10 px-4">
        <div class="w-full max-w-[440px] bg-white px-8 py-8 text-center shadow-xl">
            <div class="mx-auto mb-5 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                </svg>
            </div>

            <h3 class="text-[26px] font-bold leading-tight text-gray-500">
                {{ session('error') }}
            </h3>
        </div>
    </div>
@endif

<script>
    const flashPopup = document.getElementById('flashPopup');

    if (flashPopup) {
        setTimeout(() => {
            flashPopup.classList.add('hidden');
        }, 1800);
    }
</script>
@endsection
