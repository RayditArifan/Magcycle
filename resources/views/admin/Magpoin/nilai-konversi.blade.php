@extends('layouts.admin')

@section('page_title', 'MagPoin')

@section('content')
@php
    $formatAngka = function ($value) {
        return rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
    };
@endphp

<div class="px-8 md:px-10 py-8">
    <div class="w-full rounded-2xl border border-[#159b78] overflow-hidden bg-white">
        <div class="flex items-center justify-between border-b border-[#159b78] px-8 py-5">
            <h2 class="text-[22px] font-semibold text-[#4a4a4a]">
                Daftar Nilai Konversi Poin
            </h2>

            <button
                type="button"
                id="openAddModal"
                class="rounded-xl border border-[#333] px-8 py-3 text-[17px] font-semibold text-[#4a4a4a] hover:bg-[#e9fff7] transition"
            >
                + Tambah Nilai Konversi
            </button>
        </div>

        <table class="w-full table-fixed text-[18px] text-[#3f3f3f]">
            <thead>
                <tr class="bg-white">
                    <th class="w-[45%] px-6 py-6 text-center font-semibold">
                        Nilai Berat Sampah
                    </th>
                    <th class="w-[35%] px-6 py-6 text-center font-semibold">
                        Jumlah Poin
                    </th>
                    <th class="w-[20%] px-6 py-6 text-center font-semibold"></th>
                </tr>
            </thead>

            <tbody>
                @forelse($nilaiKonversi as $item)
                    <tr class="{{ $loop->odd ? 'bg-[#8ccfbd]' : 'bg-white' }}">
                        <td class="px-6 py-6 text-center">
                            {{ $formatAngka($item->berat_sampah) }} kg
                        </td>

                        <td class="px-6 py-6 text-center">
                            {{ $item->konversi_poin }}
                        </td>

                        <td class="px-6 py-6 text-center">
                            <button
                                type="button"
                                class="openEditModal min-w-[120px] rounded-lg border border-[#333] px-6 py-2 font-semibold text-[#4a4a4a] hover:bg-white/40 transition"
                                data-action="{{ route('admin.magpoin.nilai-konversi.update', $item->id) }}"
                                data-berat="{{ $formatAngka($item->berat_sampah) }}"
                                data-poin="{{ $item->konversi_poin }}"
                            >
                                Edit
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                            Belum ada nilai konversi poin.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="conversionModal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/20 px-4">
    <div class="relative w-full max-w-[760px] rounded-lg border-2 border-[#333] bg-white px-12 md:px-36 py-16 shadow-xl">
        <button
            type="button"
            id="closeConversionModal"
            class="absolute right-6 top-4 text-5xl leading-none text-[#444]"
        >
            &times;
        </button>

        <h2 id="modalTitle" class="text-center text-3xl font-bold text-[#4a4a4a]">
            Tambah Nilai Konversi
        </h2>

        <p id="modalDescription" class="mb-10 mt-2 text-center text-lg text-gray-500">
            Isi form berikut untuk menambah nilai konversi baru
        </p>

        <form id="conversionForm" method="POST" action="{{ route('admin.magpoin.nilai-konversi.store') }}">
            @csrf
            <input type="hidden" name="_method" id="methodInput" value="PUT" disabled>

            <label class="block text-lg font-medium text-gray-500">
                Nilai Berat Sampah (kg)
            </label>
            <input
                type="text"
                name="berat_sampah"
                id="beratInput"
                placeholder="Contoh : 1 kg"
                class="mb-6 w-full rounded border border-gray-300 px-5 py-3 text-lg text-gray-600 outline-none focus:border-[#1fa16f]"
                required
            >

            <label class="block text-lg font-medium text-gray-500">
                Jumlah Poin
            </label>
            <input
                type="text"
                name="konversi_poin"
                id="poinInput"
                placeholder="Contoh : 10"
                class="w-full rounded border border-gray-300 px-5 py-3 text-lg text-gray-600 outline-none focus:border-[#1fa16f]"
                required
            >

            <div class="mt-20 text-center">
                <button
                    type="submit"
                    class="inline-flex items-center gap-3 rounded-md bg-[#1da078] px-8 py-3 text-lg font-bold text-white hover:bg-[#168864] transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5 4h11l3 3v13H5V4Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 4v6h8V4M8 17h8"/>
                    </svg>
                    SIMPAN
                </button>
            </div>
        </form>
    </div>
</div>

<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/10 px-4">
    <div class="w-full max-w-[440px] bg-white px-10 py-8 text-center shadow-xl">
        <h3 class="mb-8 text-[26px] font-bold leading-tight text-gray-500">
            Apakah anda yakin dengan<br>
            perubahan yang dilakukan?
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

@if(session('success'))
    <div id="flashPopup" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/10 px-4">
        <div class="w-full max-w-[430px] bg-white px-8 py-8 text-center shadow-xl">
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
        <div class="w-full max-w-[430px] bg-white px-8 py-8 text-center shadow-xl">
            <div class="mx-auto mb-5 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                </svg>
            </div>

            <h3 class="text-[26px] font-bold leading-tight text-gray-500">
                Data tidak valid.<br>
                Silahkan isi kembali
            </h3>
        </div>
    </div>
@endif

<script>
    const conversionModal = document.getElementById('conversionModal');
    const confirmModal = document.getElementById('confirmModal');

    const openAddModal = document.getElementById('openAddModal');
    const closeConversionModal = document.getElementById('closeConversionModal');

    const conversionForm = document.getElementById('conversionForm');
    const methodInput = document.getElementById('methodInput');

    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const beratInput = document.getElementById('beratInput');
    const poinInput = document.getElementById('poinInput');

    const confirmYes = document.getElementById('confirmYes');
    const confirmNo = document.getElementById('confirmNo');

    let formMode = 'create';
    let editConfirmed = false;

    function showConversionModal() {
        conversionModal.classList.remove('hidden');
        conversionModal.classList.add('flex');
    }

    function hideConversionModal() {
        conversionModal.classList.add('hidden');
        conversionModal.classList.remove('flex');
    }

    function showConfirmModal() {
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
    }

    function hideConfirmModal() {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
    }

    openAddModal.addEventListener('click', () => {
        formMode = 'create';
        editConfirmed = false;

        modalTitle.textContent = 'Tambah Nilai Konversi';
        modalDescription.textContent = 'Isi form berikut untuk menambah nilai konversi baru';

        conversionForm.action = "{{ route('admin.magpoin.nilai-konversi.store') }}";
        methodInput.disabled = true;
        methodInput.value = '';

        beratInput.value = '';
        poinInput.value = '';

        beratInput.placeholder = 'Contoh : 1 kg';
        poinInput.placeholder = 'Contoh : 10';

        showConversionModal();
    });

    document.querySelectorAll('.openEditModal').forEach(button => {
        button.addEventListener('click', () => {
            formMode = 'edit';
            editConfirmed = false;

            modalTitle.textContent = 'Ubah Nilai Konversi';
            modalDescription.textContent = 'Isi form berikut untuk mengubah nilai konversi';

            conversionForm.action = button.dataset.action;
            methodInput.disabled = false;
            methodInput.value = 'PUT';

            beratInput.value = button.dataset.berat;
            poinInput.value = button.dataset.poin;

            showConversionModal();
        });
    });

    closeConversionModal.addEventListener('click', hideConversionModal);

    conversionModal.addEventListener('click', event => {
        if (event.target === conversionModal) {
            hideConversionModal();
        }
    });

    conversionForm.addEventListener('submit', event => {
        if (formMode === 'edit' && !editConfirmed) {
            event.preventDefault();
            showConfirmModal();
        }
    });

    confirmYes.addEventListener('click', () => {
        editConfirmed = true;
        hideConfirmModal();
        conversionForm.submit();
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

    @if(old('berat_sampah') !== null || old('konversi_poin') !== null)
        @if(session('edit_id'))
            formMode = 'edit';
            editConfirmed = false;
            modalTitle.textContent = 'Ubah Nilai Konversi';
            modalDescription.textContent = 'Isi form berikut untuk mengubah nilai konversi';
            conversionForm.action = "{{ route('admin.magpoin.nilai-konversi.update', ['id' => ':id']) }}".replace(':id', @json(session('edit_id')));
            methodInput.disabled = false;
            methodInput.value = 'PUT';
        @else
            formMode = 'create';
            editConfirmed = false;
            modalTitle.textContent = 'Tambah Nilai Konversi';
            modalDescription.textContent = 'Isi form berikut untuk menambah nilai konversi baru';
            conversionForm.action = "{{ route('admin.magpoin.nilai-konversi.store') }}";
            methodInput.disabled = true;
            methodInput.value = '';
        @endif
        beratInput.value = @json(old('berat_sampah'));
        poinInput.value = @json(old('konversi_poin'));
        showConversionModal();
    @endif
</script>
@endsection
