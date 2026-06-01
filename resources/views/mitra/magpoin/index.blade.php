<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MagPoin - MagCycle</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/notifikasi.css') }}">
</head>

<body class="bg-[#f6f6f6] text-[#4a4a4a]">
<div class="flex min-h-screen">
    @include('partials.mitra-sidebar')

@php
    $saldoPoin = (int) ($mitra->saldo_poin ?? 0);
    $nominalRupiah = $saldoPoin;

    $formatAngka = fn ($value) => number_format((float) $value, 0, ',', '.');
    $formatRupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');

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
            'menunggu konfirmasi' => 'bg-[#d28a00]',
            'dikonfirmasi' => 'bg-[#087a5b]',
            'ditolak' => 'bg-red-600',
            default => 'bg-gray-500',
        };
    };
@endphp

<div class="flex min-h-screen flex-1 flex-col bg-white">
    <header style="height: 125px;" class="flex items-center justify-between border-b border-gray-300 px-9">
        <h1 class="text-[46px] font-bold text-[#404040]">
            MagPoin
        </h1>

        @include('partials.mitra-topbar-actions', [
            'notificationCount' => $notificationCount ?? 0
        ])
    </header>

    <main class="relative px-12 py-6">
        <div class="flex items-start justify-between">
            <section>
                <h2 class="mb-3 text-[25px] font-bold text-[#4a4a4a]">
                    Jumlah Poin
                </h2>

                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-400 text-2xl font-bold text-white shadow">
                        $
                    </div>

                    <p class="text-[42px] font-bold text-[#3f3f3f]">
                        {{ $formatAngka($saldoPoin) }} poin
                    </p>
                </div>

                <p class="mt-2 text-[25px] font-bold text-[#4a4a4a]">
                    = {{ $formatRupiah($nominalRupiah) }}
                </p>
            </section>

            <button
                type="button"
                id="openTukarModal"
                class="mt-4 rounded-xl border border-[#333] px-7 py-3 text-[19px] font-semibold text-[#3f3f3f] hover:bg-[#e9fff7]"
            >
                Tukar Poin &gt;
            </button>
        </div>

        @if($riwayatTransaksi->isNotEmpty())
            <section class="mt-16 w-full rounded-2xl border border-[#159b78] bg-white overflow-hidden">
                <div class="border-b border-[#159b78] px-7 py-4">
                    <h2 class="text-[22px] font-semibold text-[#4a4a4a]">
                        Riwayat Transaksi Poin
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] table-fixed text-[15px] text-[#3f3f3f]">
                        <thead>
                            <tr class="bg-white">
                                <th class="w-[17%] px-4 py-4 text-center font-semibold">Tanggal</th>
                                <th class="w-[17%] px-4 py-4 text-center font-semibold">Jumlah Poin</th>
                                <th class="w-[17%] px-4 py-4 text-center font-semibold">Nominal</th>
                                <th class="w-[15%] px-4 py-4 text-center font-semibold">E-Wallet</th>
                                <th class="w-[18%] px-4 py-4 text-center font-semibold">Nomor</th>
                                <th class="w-[16%] px-4 py-4 text-center font-semibold">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($riwayatTransaksi as $item)
                                <tr class="{{ $loop->odd ? 'bg-[#8ccfbd]' : 'bg-white' }}">
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                    </td>

                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        {{ $formatAngka($item->poin_tukar) }}
                                    </td>

                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        {{ $formatRupiah($item->nominal_uang) }}
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-block min-w-[85px] rounded px-3 py-1 text-xs font-semibold text-white {{ $walletClass($item->nama_ewallet) }}">
                                            {{ strtoupper($item->nama_ewallet) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        {{ $item->nomor_ewallet }}
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-block rounded px-3 py-1 text-xs font-semibold text-white {{ $statusClass($item->status_penukaran) }}">
                                            • {{ ucfirst($item->status_penukaran) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </main>
</div>

{{-- Modal Tukar Poin --}}
<div id="tukarModal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/45 px-4">
    <div class="relative w-full max-w-[720px] rounded-lg border-2 border-[#333] bg-white px-12 md:px-32 py-16 shadow-xl">
        <button
            type="button"
            id="closeTukarModal"
            class="absolute right-6 top-4 text-5xl leading-none text-[#444]"
        >
            &times;
        </button>

        <h2 class="text-center text-3xl font-bold text-[#4a4a4a]">
            Tukar Poin
        </h2>

        <p class="mb-10 mt-2 text-center text-lg text-gray-500">
            Isi form berikut untuk mengajukan penukaran poin
        </p>

        <form id="tukarForm" method="POST" action="{{ route('mitra.magpoin.tukar-poin.store') }}">
            @csrf

            <label class="block text-lg font-medium text-gray-500">
                Jumlah Poin Ditukar
            </label>
            <input
                type="text"
                name="poin_tukar"
                id="poinTukarInput"
                placeholder="Contoh : 50000"
                value="{{ old('poin_tukar') }}"
                class="mb-5 w-full rounded border border-gray-300 px-5 py-3 text-lg text-gray-600 outline-none focus:border-[#1fa16f]"
                required
            >

            <label class="block text-lg font-medium text-gray-500">
                Nominal Uang
            </label>
            <input
                type="text"
                id="nominalUangInput"
                value="{{ old('poin_tukar') ? $formatRupiah(old('poin_tukar')) : '' }}"
                placeholder="Otomatis sesuai jumlah poin"
                class="mb-5 w-full rounded border border-gray-300 px-5 py-3 text-lg text-gray-600 outline-none"
                readonly
            >

            <label class="block text-lg font-medium text-gray-500">
                Jenis E-Wallet
            </label>
            <select
                name="e_wallet_id"
                class="mb-5 w-full rounded border border-gray-300 px-5 py-3 text-lg text-gray-600 outline-none focus:border-[#1fa16f]"
                required
            >
                <option value="">Pilih E-Wallet</option>
                @foreach($eWallets as $wallet)
                    <option value="{{ $wallet->id }}" {{ old('e_wallet_id') == $wallet->id ? 'selected' : '' }}>
                        {{ strtoupper($wallet->nama_ewallet) }}
                    </option>
                @endforeach
            </select>

            <label class="block text-lg font-medium text-gray-500">
                Nomor E-Wallet
            </label>
            <input
                type="text"
                name="nomor_ewallet"
                value="{{ old('nomor_ewallet') }}"
                placeholder="Contoh : 081234567890"
                class="w-full rounded border border-gray-300 px-5 py-3 text-lg text-gray-600 outline-none focus:border-[#1fa16f]"
                required
            >

            <div class="mt-14 text-center">
                <button
                    type="submit"
                    class="rounded-md bg-[#1da078] px-8 py-3 text-lg font-bold text-white hover:bg-[#168864]"
                >
                    Ajukan Penukaran
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/45 px-4">
    <div class="w-full max-w-[440px] bg-white px-10 py-8 text-center shadow-xl">
        <h3 class="mb-8 text-[26px] font-bold leading-tight text-gray-500">
            Apakah anda yakin ingin<br>
            melakukan penukaran poin?
        </h3>

        <div class="flex justify-center gap-6">
            <button
                type="button"
                id="confirmYes"
                class="w-[150px] rounded bg-[#1da078] py-4 text-lg font-bold text-white hover:bg-[#168864]"
            >
                YA
            </button>

            <button
                type="button"
                id="confirmNo"
                class="w-[150px] rounded bg-red-600 py-4 text-lg font-bold text-white hover:bg-red-700"
            >
                TIDAK
            </button>
        </div>
    </div>
</div>

{{-- Popup Sukses --}}
@if(session('success'))
    <div id="flashPopup" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/45 px-4">
        <div class="w-full max-w-[440px] bg-white px-8 py-8 text-center shadow-xl">
            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-[#1da078] text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-11 w-11" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h3 class="text-[26px] font-bold leading-tight text-gray-500">
                {{ session('success') }}
            </h3>
        </div>
    </div>
@endif

{{-- Popup Error --}}
@if(session('error'))
    <div id="flashPopup" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/45 px-4">
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

@include('partials.error-popup')

<script>
    const tukarModal = document.getElementById('tukarModal');
    const confirmModal = document.getElementById('confirmModal');

    const openTukarModal = document.getElementById('openTukarModal');
    const closeTukarModal = document.getElementById('closeTukarModal');

    const tukarForm = document.getElementById('tukarForm');
    const confirmYes = document.getElementById('confirmYes');
    const confirmNo = document.getElementById('confirmNo');

    const poinTukarInput = document.getElementById('poinTukarInput');
    const nominalUangInput = document.getElementById('nominalUangInput');

    let confirmed = false;

    function formatRupiah(value) {
        const number = parseInt(value || 0);
        if (isNaN(number)) {
            return 'Rp 0';
        }
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function showTukarModal() {
        tukarModal.classList.remove('hidden');
        tukarModal.classList.add('flex');
    }

    function hideTukarModal() {
        tukarModal.classList.add('hidden');
        tukarModal.classList.remove('flex');
    }

    function showConfirmModal() {
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
    }

    function hideConfirmModal() {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
    }

    openTukarModal.addEventListener('click', showTukarModal);

    closeTukarModal.addEventListener('click', hideTukarModal);

    tukarModal.addEventListener('click', event => {
        if (event.target === tukarModal) {
            hideTukarModal();
        }
    });

    poinTukarInput.addEventListener('input', () => {
        nominalUangInput.value = formatRupiah(poinTukarInput.value);
    });

    tukarForm.addEventListener('submit', event => {
        if (!confirmed) {
            event.preventDefault();
            showConfirmModal();
        }
    });

    confirmYes.addEventListener('click', () => {
        confirmed = true;
        hideConfirmModal();
        tukarForm.submit();
    });

    confirmNo.addEventListener('click', () => {
        hideConfirmModal();
    });

    confirmModal.addEventListener('click', event => {
        if (event.target === confirmModal) {
            hideConfirmModal();
        }
    });

    const flashPopup = document.getElementById('flashPopup');

    if (flashPopup) {
        setTimeout(() => {
            flashPopup.classList.add('hidden');
        }, 1800);
    }

    @if(session('error') || session('error_popup'))
        showTukarModal();
    @endif
</script>

</div>
</div>
<script src="{{ asset('js/notifikasi.js') }}"></script>
</body>
</html>
