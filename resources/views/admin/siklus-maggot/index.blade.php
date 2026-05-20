@extends('layouts.admin')

@section('page_title', 'Siklus Maggot')

@section('content')
    <section class="px-8 py-8 md:px-10">
        <form method="GET" action="{{ route('admin.siklus-maggot.index') }}" class="mb-4 flex items-center gap-3">
            <div class="flex h-[46px] flex-1 items-center rounded-xl border border-[#4a4a4a] bg-white px-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-4 h-6 w-6 shrink-0 text-[#4a4a4a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama batch"
                    class="h-[34px] w-full rounded-lg border border-[#7a7a7a] px-4 text-[16px] text-[#4a4a4a] outline-none placeholder:text-[#8a8a8a]"
                >
            </div>

            <button
                type="submit"
                class="h-[46px] rounded-lg bg-[#1fa16f] px-6 text-[15px] font-bold text-white transition hover:bg-[#178a5d]"
            >
                Cari
            </button>

            @if (request()->filled('search'))
                <a
                    href="{{ route('admin.siklus-maggot.index') }}"
                    class="flex h-[46px] items-center rounded-lg border border-[#4a4a4a] px-5 text-[15px] font-semibold text-[#4a4a4a] transition hover:bg-[#e8f6f1]"
                >
                    Reset
                </a>
            @endif
        </form>

    <div class="overflow-hidden rounded-[18px] border border-[#1fa16f] bg-white">
            <div class="flex items-center justify-between border-b border-[#1fa16f] px-10 py-5">
                <h2 class="text-[24px] font-semibold text-[#4a4a4a]">
                    Monitoring Siklus Maggot
                </h2>

                <button
                    type="button"
                    id="openCreateModal"
                    class="rounded-xl border border-[#4a4a4a] px-5 py-3 text-[18px] font-semibold text-[#4a4a4a] transition hover:bg-[#e8f6f1]"
                >
                    + Tambah Siklus
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[980px] text-left text-[#4a4a4a]">
                    <thead>
                        <tr class="text-[18px] font-semibold">
                            <th class="px-10 py-5 text-center font-semibold">Nama Batch</th>
                            <th class="px-10 py-5 text-center font-semibold">Jumlah Awal</th>
                            <th class="px-10 py-5 text-center font-semibold">Tanggal Masuk</th>
                            <th class="px-10 py-5 text-center font-semibold">Status</th>
                            <th class="px-10 py-5 text-center font-semibold">Hari ke-</th>
                        </tr>
                    </thead>
                    <tbody class="text-[18px]">
                        @forelse ($siklusMaggot as $siklus)
                            <tr
                                class="cursor-pointer odd:bg-[#93d1bd] even:bg-white hover:bg-[#bfe6da]"
                                onclick="window.location='{{ route('admin.siklus-maggot.show', $siklus->id) }}'"
                            >
                                <td class="px-10 py-7 text-center">{{ $siklus->nama_batch }}</td>
                                <td class="px-10 py-7 text-center">
                                    {{ rtrim(rtrim(number_format((float) $siklus->jumlah_masuk, 2, ',', '.'), '0'), ',') }} kg
                                </td>
                                <td class="px-10 py-7 text-center">
                                    {{ \Carbon\Carbon::parse($siklus->tanggal_masuk)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-10 py-7 text-center">
                                    @include('partials.status-badge', ['status' => $siklus->status_label])
                                </td>
                                <td class="px-10 py-7 text-center">{{ $siklus->hari_ke }}</td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-10 py-12 text-center text-[18px] text-[#4a4a4a]">
                                        @if (request()->filled('search'))
                                            Batch dengan kata kunci "{{ request('search') }}" tidak ditemukan.
                                        @else
                                            Data siklus maggot belum tersedia.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div id="createModal" class="fixed inset-0 z-[9998] hidden items-center justify-center bg-black/10">
        <div class="relative w-[675px] rounded-md border border-[#252525] bg-white px-32 py-14 shadow-xl">
            <button type="button" id="closeCreateModal" class="absolute right-5 top-4 text-[#4a4a4a]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="text-center">
                <h3 class="text-[28px] font-bold text-[#4a4a4a]">Tambah Siklus Maggot</h3>
                <p class="mt-3 text-[18px] text-[#66708a]">Isi form berikut untuk menambah siklus baru</p>
            </div>

            <form method="POST" action="{{ route('admin.siklus-maggot.store') }}" class="mt-10 space-y-7">
                @csrf
                <input type="hidden" name="_form" value="create">

                <div>
                    <label for="nama_batch" class="mb-2 block text-[18px] font-medium text-[#66708a]">Nama Batch</label>
                    <input
                        id="nama_batch"
                        name="nama_batch"
                        type="text"
                        value="{{ old('_form') === 'create' ? old('nama_batch') : '' }}"
                        maxlength="30"
                        placeholder="Contoh : Batch 1"
                        class="h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#4a4a4a] outline-none placeholder:text-[#8a8a8a] focus:border-[#1fa16f]"
                    >
                    @if (old('_form') === 'create')
                        @error('nama_batch')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label for="jumlah_awal" class="mb-2 block text-[18px] font-medium text-[#66708a]">Jumlah Awal</label>
                    <input
                        id="jumlah_awal"
                        name="jumlah_awal"
                        type="number"
                        step="0.1"
                        min="0.1"
                        value="{{ old('_form') === 'create' ? old('jumlah_awal') : '' }}"
                        placeholder="Contoh : 3 kg"
                        class="h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#4a4a4a] outline-none placeholder:text-[#8a8a8a] focus:border-[#1fa16f]"
                    >
                    @if (old('_form') === 'create')
                        @error('jumlah_awal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="pt-4 text-center">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-3 rounded bg-[#1fa16f] px-8 py-4 text-[16px] font-bold text-white transition hover:bg-[#178a5d]"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3h11l3 3v15H5V3Zm4 0v6h7V3M8 15h8" />
                        </svg>
                        SIMPAN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('createModal');
            const openButton = document.getElementById('openCreateModal');
            const closeButton = document.getElementById('closeCreateModal');

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

            @if (session('open_create_modal') || old('_form') === 'create')
                openModal();
            @endif
        });
    </script>
@endsection
