@extends('layouts.admin')

@section('page_title', 'Panduan Setor Sampah')

@section('content')
    <section class="px-8 py-8 md:px-10">
        <div class="mb-7 flex justify-end">
            <button
                type="button"
                id="openCreatePanduanModal"
                class="rounded-xl border border-[#4a4a4a] px-5 py-3 text-[18px] font-semibold text-[#4a4a4a] transition hover:bg-[#e8f6f1]"
            >
                + Buat Panduan
            </button>
        </div>

        <div class="space-y-9">
            @forelse ($panduanSetor as $panduan)
                @php
                    $videoPath = $panduan->video_panduan;
                    $videoName = $videoPath ? basename($videoPath) : 'Video belum tersedia';
                    $videoUrl = null;

                    if ($videoPath) {
                        $videoUrl = \Illuminate\Support\Str::startsWith($videoPath, ['http://', 'https://'])
                            ? $videoPath
                            : asset('storage/' . $videoPath);
                    }
                @endphp

                <article class="rounded-[22px] bg-[#a8dccb] px-8 py-7 text-[#2f705d]">
                    <div class="flex gap-6">
                        <div class="min-w-0 flex-1">
                            <h2 class="text-[25px] font-bold leading-tight">
                                {{ $panduan->judul }}
                            </h2>

                            <p class="mt-4 text-[18px] font-semibold leading-relaxed">
                                {{ $panduan->deskripsi }}
                            </p>

                            @if ($videoUrl)
                                <a
                                    href="{{ $videoUrl }}"
                                    target="_blank"
                                    class="mt-4 inline-flex w-full max-w-[520px] items-center gap-3 rounded-xl bg-[#d9f2e7] px-4 py-3 text-[16px] font-semibold text-[#8a8a8a] transition hover:bg-[#cef0e2]"
                                >
                                    <span class="flex h-6 w-6 items-center justify-center rounded bg-[#b6b6b6] text-white">
                                        ▶
                                    </span>
                                    <span class="truncate">{{ $videoName }}</span>
                                </a>
                            @else
                                <div class="mt-4 inline-flex w-full max-w-[520px] items-center gap-3 rounded-xl bg-[#d9f2e7] px-4 py-3 text-[16px] font-semibold text-[#8a8a8a]">
                                    <span class="flex h-6 w-6 items-center justify-center rounded bg-[#b6b6b6] text-white">
                                        ▶
                                    </span>
                                    <span>Video belum tersedia</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex min-w-[260px] items-end justify-end gap-4 pb-2">
                            <button
                                type="button"
                                data-open-edit-panduan
                                data-id="{{ $panduan->id_panduan }}"
                                data-action="{{ route('admin.panduan-setor.update', $panduan->id_panduan) }}"
                                data-judul="{{ e($panduan->judul) }}"
                                data-deskripsi="{{ e($panduan->deskripsi) }}"
                                class="w-[115px] rounded-xl border border-[#4a4a4a] px-5 py-2 text-[18px] font-semibold text-[#4a4a4a] transition hover:bg-white/30"
                            >
                                Edit
                            </button>

                            <button
                                type="button"
                                data-open-delete-panduan
                                data-title="{{ e($panduan->judul) }}"
                                data-action="{{ route('admin.panduan-setor.destroy', $panduan->id_panduan) }}"
                                class="w-[115px] rounded-xl border border-[#ff1010] px-5 py-2 text-[18px] font-semibold text-[#ff1010] transition hover:bg-white/30"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-[22px] border border-dashed border-[#1fa16f] bg-white px-8 py-12 text-center text-[20px] font-semibold text-[#4a4a4a]">
                    Panduan setor sampah belum tersedia.
                </div>
            @endforelse
        </div>
    </section>

    {{-- Modal Buat Panduan --}}
    <div id="createPanduanModal" class="fixed inset-0 z-[9998] hidden items-center justify-center bg-black/10">
        <div class="relative w-[680px] rounded-md border border-[#252525] bg-white px-32 py-12 shadow-xl">
            <button type="button" id="closeCreatePanduanModal" class="absolute right-5 top-4 text-[#4a4a4a]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="text-center">
                <h3 class="text-[28px] font-bold text-[#4a4a4a]">Buat Panduan Setor</h3>
                <p class="mt-3 text-[18px] text-[#66708a]">
                    Isi form berikut untuk menambah panduan setor
                </p>
            </div>

            <form method="POST" action="{{ route('admin.panduan-setor.store') }}" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf

                <div>
                    <label for="create_judul" class="mb-2 block text-[18px] font-medium text-[#66708a]">Judul</label>
                    <input
                        id="create_judul"
                        name="judul"
                        type="text"
                        maxlength="120"
                        value="{{ old('judul') }}"
                        placeholder="Judul panduan"
                        class="h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#4a4a4a] outline-none focus:border-[#1fa16f]"
                    >
                    @if (session('open_create_panduan'))
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label for="create_deskripsi" class="mb-2 block text-[18px] font-medium text-[#66708a]">Deskripsi</label>
                    <textarea
                        id="create_deskripsi"
                        name="deskripsi"
                        rows="5"
                        maxlength="540"
                        placeholder="Isi deskripsi panduan"
                        class="w-full resize-none rounded border border-[#cfcfcf] px-5 py-4 text-[16px] text-[#4a4a4a] outline-none focus:border-[#1fa16f]"
                    >{{ old('deskripsi') }}</textarea>
                    @if (session('open_create_panduan'))
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-[18px] font-medium text-[#66708a]">Upload video</label>
                    <label for="create_video" class="flex h-[50px] cursor-pointer items-center justify-center rounded border border-[#bdbdbd] bg-[#d9d9d9] px-5 text-[17px] font-semibold text-[#66708a] transition hover:bg-[#cecece]">
                        <span id="createVideoLabel">Klik untuk upload video</span>
                    </label>
                    <input id="create_video" name="video_panduan" type="file" accept="video/mp4,video/webm,.mp4,.webm" class="hidden">
                    @if (session('open_create_panduan'))
                        @error('video_panduan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="pt-2 text-center">
                    <button type="submit" class="inline-flex items-center gap-3 rounded bg-[#1fa16f] px-6 py-3 text-[16px] font-bold text-white transition hover:bg-[#178a5d]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3h11l3 3v15H5V3Zm4 0v6h7V3M8 15h8" />
                        </svg>
                        UNGGAH
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Ubah Panduan --}}
    <div id="editPanduanModal" class="fixed inset-0 z-[9998] hidden items-center justify-center bg-black/10">
        <div class="relative w-[680px] rounded-md border border-[#252525] bg-white px-32 py-12 shadow-xl">
            <button type="button" id="closeEditPanduanModal" class="absolute right-5 top-4 text-[#4a4a4a]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="text-center">
                <h3 class="text-[28px] font-bold text-[#4a4a4a]">Ubah Panduan Setor</h3>
                <p class="mt-3 text-[18px] text-[#66708a]">
                    Isi form berikut untuk mengubah panduan setor
                </p>
            </div>

            <form id="editPanduanForm" method="POST" action="" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit_judul" class="mb-2 block text-[18px] font-medium text-[#66708a]">Judul</label>
                    <input
                        id="edit_judul"
                        name="judul"
                        type="text"
                        maxlength="120"
                        value="{{ session('open_edit_panduan') ? old('judul') : '' }}"
                        placeholder="Judul panduan"
                        class="h-[50px] w-full rounded border border-[#cfcfcf] px-5 text-[16px] text-[#4a4a4a] outline-none focus:border-[#1fa16f]"
                    >
                    @if (session('open_edit_panduan'))
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label for="edit_deskripsi" class="mb-2 block text-[18px] font-medium text-[#66708a]">Deskripsi</label>
                    <textarea
                        id="edit_deskripsi"
                        name="deskripsi"
                        rows="5"
                        maxlength="540"
                        placeholder="Isi deskripsi panduan"
                        class="w-full resize-none rounded border border-[#cfcfcf] px-5 py-4 text-[16px] text-[#4a4a4a] outline-none focus:border-[#1fa16f]"
                    >{{ session('open_edit_panduan') ? old('deskripsi') : '' }}</textarea>
                    @if (session('open_edit_panduan'))
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="mb-2 block text-[18px] font-medium text-[#66708a]">Ubah Video (Opsional)</label>
                    <label for="edit_video" class="flex h-[50px] cursor-pointer items-center justify-center rounded border border-[#bdbdbd] bg-[#d9d9d9] px-5 text-[17px] font-semibold text-[#66708a] transition hover:bg-[#cecece]">
                        <span id="editVideoLabel">Klik untuk upload video</span>
                    </label>
                    <input id="edit_video" name="video_panduan" type="file" accept="video/mp4,video/webm,.mp4,.webm" class="hidden">
                    @if (session('open_edit_panduan'))
                        @error('video_panduan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="pt-2 text-center">
                    <button
                        type="button"
                        data-confirm-submit
                        data-form="editPanduanForm"
                        class="inline-flex items-center gap-3 rounded bg-[#1fa16f] px-6 py-3 text-[16px] font-bold text-white transition hover:bg-[#178a5d]"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3h11l3 3v15H5V3Zm4 0v6h7V3M8 15h8" />
                        </svg>
                        UNGGAH
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Hapus Panduan --}}
    <div id="deletePanduanModal" class="fixed inset-0 z-[9998] hidden items-center justify-center bg-black/20">
        <div class="w-[430px] bg-white px-10 py-8 text-center shadow-xl">
            <h3 class="mb-3 text-[24px] font-extrabold leading-tight text-[#66708a]">
                Apakah anda yakin ingin menghapus panduan setor sampah?
            </h3>
            <p id="deletePanduanTitle" class="mb-7 text-[16px] font-semibold text-[#4a4a4a]"></p>

            <form id="deletePanduanForm" method="POST" action="">
                @csrf
                @method('DELETE')

                <div class="grid grid-cols-2 gap-6">
                    <button
                        type="submit"
                        class="rounded bg-[#1fa16f] px-8 py-3 text-[18px] font-bold text-white transition hover:bg-[#17895d]"
                    >
                        YA
                    </button>
                    <button
                        type="button"
                        id="closeDeletePanduanModal"
                        class="rounded bg-[#ff1010] px-8 py-3 text-[18px] font-bold text-white transition hover:bg-[#dd0000]"
                    >
                        TIDAK
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if (session('panduan_success_popup'))
        <div id="panduanSuccessPopup" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/20 px-4">
            <div class="w-full max-w-[390px] bg-white px-8 py-9 text-center shadow-xl">
                <div class="mx-auto mb-6 flex h-[86px] w-[86px] items-center justify-center rounded-full bg-[#1ea36f] text-[58px] font-bold leading-none text-white">
                    ✓
                </div>
                <h2 class="text-[28px] font-extrabold leading-tight text-[#667089]">
                    {{ session('panduan_success_popup') }}
                </h2>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const createModal = document.getElementById('createPanduanModal');
            const editModal = document.getElementById('editPanduanModal');
            const deleteModal = document.getElementById('deletePanduanModal');
            const openCreateButton = document.getElementById('openCreatePanduanModal');
            const closeCreateButton = document.getElementById('closeCreatePanduanModal');
            const closeEditButton = document.getElementById('closeEditPanduanModal');
            const closeDeleteButton = document.getElementById('closeDeletePanduanModal');
            const editForm = document.getElementById('editPanduanForm');
            const deleteForm = document.getElementById('deletePanduanForm');
            const deleteTitle = document.getElementById('deletePanduanTitle');
            const editJudul = document.getElementById('edit_judul');
            const editDeskripsi = document.getElementById('edit_deskripsi');
            const createVideoInput = document.getElementById('create_video');
            const editVideoInput = document.getElementById('edit_video');
            const createVideoLabel = document.getElementById('createVideoLabel');
            const editVideoLabel = document.getElementById('editVideoLabel');
            const successPopup = document.getElementById('panduanSuccessPopup');

            function openModal(modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal(modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            openCreateButton?.addEventListener('click', function () {
                openModal(createModal);
            });

            closeCreateButton?.addEventListener('click', function () {
                closeModal(createModal);
            });

            closeEditButton?.addEventListener('click', function () {
                closeModal(editModal);
            });

            closeDeleteButton?.addEventListener('click', function () {
                closeModal(deleteModal);
                deleteForm.action = '';
            });

            createModal?.addEventListener('click', function (event) {
                if (event.target === createModal) closeModal(createModal);
            });

            editModal?.addEventListener('click', function (event) {
                if (event.target === editModal) closeModal(editModal);
            });

            deleteModal?.addEventListener('click', function (event) {
                if (event.target === deleteModal) {
                    closeModal(deleteModal);
                    deleteForm.action = '';
                }
            });

            document.querySelectorAll('[data-open-edit-panduan]').forEach(function (button) {
                button.addEventListener('click', function () {
                    editForm.action = button.dataset.action;
                    editJudul.value = button.dataset.judul || '';
                    editDeskripsi.value = button.dataset.deskripsi || '';
                    editVideoLabel.textContent = 'Klik untuk upload video';
                    if (editVideoInput) editVideoInput.value = '';
                    openModal(editModal);
                });
            });

            document.querySelectorAll('[data-open-delete-panduan]').forEach(function (button) {
                button.addEventListener('click', function () {
                    deleteForm.action = button.dataset.action;
                    deleteTitle.textContent = button.dataset.title ? '"' + button.dataset.title + '"' : '';
                    openModal(deleteModal);
                });
            });

            createVideoInput?.addEventListener('change', function () {
                createVideoLabel.textContent = createVideoInput.files.length
                    ? createVideoInput.files[0].name
                    : 'Klik untuk upload video';
            });

            editVideoInput?.addEventListener('change', function () {
                editVideoLabel.textContent = editVideoInput.files.length
                    ? editVideoInput.files[0].name
                    : 'Klik untuk upload video';
            });

            if (successPopup) {
                const closeSuccess = function () { successPopup.remove(); };
                successPopup.addEventListener('click', closeSuccess);
                setTimeout(closeSuccess, 1800);
            }

            @if (session('open_create_panduan'))
                openModal(createModal);
            @endif

            @if (session('open_edit_panduan'))
                const failedEditButton = document.querySelector('[data-open-edit-panduan][data-id="{{ session('open_edit_panduan') }}"]');
                if (failedEditButton) {
                    editForm.action = failedEditButton.dataset.action;
                    editJudul.value = @json(old('judul'));
                    editDeskripsi.value = @json(old('deskripsi'));
                    openModal(editModal);
                }
            @endif
        });
    </script>
@endsection
