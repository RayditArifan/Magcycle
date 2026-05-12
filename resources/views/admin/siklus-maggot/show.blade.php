@extends('layouts.admin')

@section('page_title', 'Siklus Maggot')

@section('content')
    <section class="px-8 py-8 md:px-10">
        <div class="relative rounded-[18px] border border-[#1fa16f] bg-white px-7 py-7">
            <a href="{{ route('admin.siklus-maggot.index') }}" class="absolute right-5 top-5 text-[#4a4a4a]" aria-label="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </a>

            <h2 class="text-[24px] font-semibold text-[#4a4a4a]">
                Detail Monitoring
            </h2>

            <div class="mt-6 max-w-[680px] space-y-6 text-[18px] text-[#4a4a4a]">
                <div class="grid grid-cols-[180px_1fr] items-center gap-5">
                    <p class="font-medium">Nama Batch</p>
                    <div class="rounded-xl border border-[#4a4a4a] px-4 py-2">
                        {{ $siklus->nama_batch }}
                    </div>
                </div>

                <div class="grid grid-cols-[180px_1fr] items-center gap-5">
                    <p class="font-medium">Jumlah Awal</p>
                    <div class="rounded-xl border border-[#4a4a4a] px-4 py-2">
                        {{ rtrim(rtrim(number_format((float) $siklus->jumlah_masuk, 2, ',', '.'), '0'), ',') }} kg
                    </div>
                </div>

                <div class="grid grid-cols-[180px_1fr] items-center gap-5">
                    <p class="font-medium">Status</p>
                    <div>
                        @include('partials.status-badge', ['status' => $siklus->status_label])
                    </div>
                </div>
            </div>

            <div class="mt-14 overflow-hidden rounded-xl border border-[#1fa16f] bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left text-[#4a4a4a]">
                        <thead>
                            <tr class="text-[18px] font-semibold">
                                <th class="px-10 py-5 text-center font-semibold">Jumlah Hidup</th>
                                <th class="px-10 py-5 text-center font-semibold">Tanggal</th>
                                <th class="px-10 py-5 text-center font-semibold">Kasgot</th>
                                <th class="px-10 py-5 text-center font-semibold">Hasil Panen</th>
                            </tr>
                        </thead>
                        <tbody class="text-[18px]">
                            <tr class="bg-[#93d1bd]">
                                <td class="px-10 py-7 text-center">
                                    {{ rtrim(rtrim(number_format((float) ($siklus->jumlah_hidup ?? 0), 2, ',', '.'), '0'), ',') }} kg
                                </td>
                                <td class="px-10 py-7 text-center">
                                    {{ $siklus->waktu_update ? \Carbon\Carbon::parse($siklus->waktu_update)->translatedFormat('d F Y') : '-' }}
                                </td>
                                <td class="px-10 py-7 text-center">
                                    {{ rtrim(rtrim(number_format((float) ($siklus->jumlah_kasgot ?? 0), 2, ',', '.'), '0'), ',') }} kg
                                </td>
                                <td class="px-10 py-7 text-center">
                                    {{ rtrim(rtrim(number_format((float) ($siklus->hasil_panen ?? 0), 2, ',', '.'), '0'), ',') }} kg
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-14 flex justify-end">
                <button
                    type="button"
                    id="openEditModal"
                    class="rounded-xl border border-[#1fa16f] px-5 py-4 text-[17px] font-semibold text-[#1fa16f] transition hover:bg-[#e8f6f1]"
                >
                    Perbarui Data
                </button>
            </div>
        </div>
    </section>

    <div id="editModal" class="fixed inset-0 z-[9998] hidden items-center justify-center bg-black/10">
        <div class="relative w-[675px] rounded-md border border-[#252525] bg-white px-32 py-12 shadow-xl">
            <button type="button" id="closeEditModal" class="absolute right-5 top-4 text-[#4a4a4a]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="text-center">
                <h3 class="text-[28px] font-bold text-[#4a4a4a]">Edit Siklus Maggot</h3>
                <p class="mt-3 text-[18px] text-[#66708a]">Isi form berikut untuk mengedit data siklus maggot</p>
            </div>

            <form method="POST" action="{{ route('admin.siklus-maggot.update', $siklus->id) }}" class="mt-10 space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="_form" value="edit">

                <div>
                    <label for="status" class="mb-2 block text-[18px] font-medium text-[#66708a]">Status</label>
                    <select
                        id="status"
                        name="status"
                        class="h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#66708a] outline-none focus:border-[#1fa16f]"
                    >
                        <option value="">Pilih status</option>
                        @foreach ($statusOptions as $status)
                            <option value="{{ $status }}" @selected(old('_form') === 'edit' ? old('status') === $status : $siklus->status_label === $status)>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                    @if (old('_form') === 'edit')
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label for="jumlah_hidup" class="mb-2 block text-[18px] font-medium text-[#66708a]">Jumlah Hidup</label>
                    <input
                        id="jumlah_hidup"
                        name="jumlah_hidup"
                        type="number"
                        step="0.1"
                        min="0"
                        value="{{ old('_form') === 'edit' ? old('jumlah_hidup') : ($siklus->jumlah_hidup ?? 0) }}"
                        placeholder="Contoh : 3 kg"
                        class="h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#4a4a4a] outline-none placeholder:text-[#8a8a8a] focus:border-[#1fa16f]"
                    >
                    @if (old('_form') === 'edit')
                        @error('jumlah_hidup')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label for="jumlah_kasgot" class="mb-2 block text-[18px] font-medium text-[#66708a]">Kasgot</label>
                    <input
                        id="jumlah_kasgot"
                        name="jumlah_kasgot"
                        type="number"
                        step="0.1"
                        min="0"
                        value="{{ old('_form') === 'edit' ? old('jumlah_kasgot') : ($siklus->jumlah_kasgot ?? 0) }}"
                        placeholder="Contoh : 3 kg"
                        class="h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#4a4a4a] outline-none placeholder:text-[#8a8a8a] focus:border-[#1fa16f]"
                    >
                    @if (old('_form') === 'edit')
                        @error('jumlah_kasgot')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label for="hasil_panen" class="mb-2 block text-[18px] font-medium text-[#66708a]">Hasil Panen</label>
                    <input
                        id="hasil_panen"
                        name="hasil_panen"
                        type="number"
                        step="0.1"
                        min="0"
                        value="{{ old('_form') === 'edit' ? old('hasil_panen') : ($siklus->hasil_panen ?? 0) }}"
                        placeholder="Contoh : 3 kg"
                        class="h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#4a4a4a] outline-none placeholder:text-[#8a8a8a] focus:border-[#1fa16f]"
                    >
                    @if (old('_form') === 'edit')
                        @error('hasil_panen')
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
            const modal = document.getElementById('editModal');
            const openButton = document.getElementById('openEditModal');
            const closeButton = document.getElementById('closeEditModal');

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

            @if (session('open_edit_modal') || old('_form') === 'edit')
                openModal();
            @endif
        });
    </script>
@endsection
