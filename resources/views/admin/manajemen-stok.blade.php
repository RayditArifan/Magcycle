<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Stok - MagCycle</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f6f6f6] text-[#4a4a4a]">
    <div class="flex min-h-screen">
        @include('partials.admin-sidebar')

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="border-b border-[#c8c8c8] bg-white">
                <div class="flex items-center justify-between" style="height: 110px; padding-left: 30px; padding-right: 50px;">
                    <h1 class="font-medium text-[#4a4a4a]" style="font-size: 42px;">
                        Manajemen Stok
                    </h1>

                    @include('partials.topbar-actions', [
                        'notificationCount' => $notificationCount ?? 0,
                        'profileRoute' => route('admin.profile'),
                        'mitraProfilesRoute' => route('admin.mitra.profiles'),
                    ])
                </div>
            </header>

            <main class="flex-1 px-10 py-8">
                <section class="mx-auto max-w-[1180px] overflow-hidden rounded-[18px] border border-[#37c793] bg-white">
                    <div class="flex items-center justify-between border-b border-[#37c793] px-7 py-4">
                        <h2 class="text-[22px] font-bold text-[#4a4a4a]">
                            Manajemen Stok Produk
                        </h2>

                        <button
                            type="button"
                            id="openAddModal"
                            class="rounded-[12px] border border-[#6b6b6b] px-6 py-2 text-[18px] font-bold text-[#4a4a4a] hover:bg-gray-50"
                        >
                            + Tambah Stok
                        </button>
                    </div>

                    <div class="grid grid-cols-[1.4fr_1fr_1fr_140px] items-center px-14 py-5 text-[19px] font-medium text-[#4a4a4a]">
                        <div>Jenis Produk</div>
                        <div>Jumlah Stok</div>
                        <div>Tanggal Input</div>
                        <div></div>
                    </div>

                    @forelse ($stocks as $stock)
                        <div class="grid grid-cols-[1.4fr_1fr_1fr_140px] items-center px-14 py-5 text-[18px] text-[#4a4a4a] {{ $loop->odd ? 'bg-[#a9dece]' : 'bg-white' }}">
                            <div>{{ $stock->jenis_produk }}</div>
                            <div>{{ $stock->jumlah_stok }} {{ $stock->satuan }}</div>
                            <div>{{ \Carbon\Carbon::parse($stock->tanggal_input)->translatedFormat('d F Y') }}</div>
                            <div class="flex justify-end">
                                <button
                                    type="button"
                                    class="openEditModal h-[38px] w-[104px] rounded-lg border border-[#6b6b6b] text-[16px] font-bold text-[#4a4a4a] hover:bg-gray-50"
                                    data-id="{{ $stock->id_stok }}"
                                    data-jenis="{{ $stock->jenis_produk }}"
                                    data-jumlah="{{ $stock->jumlah_stok }}"
                                    data-tanggal="{{ $stock->tanggal_input }}"
                                >
                                    Edit
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="px-16 py-12 text-center text-[18px] font-medium text-[#6b7280]">
                            Belum ada data stok produk.
                        </div>
                    @endforelse

                    <div class="h-10"></div>
                </section>
            </main>
        </div>
    </div>

    <div id="pageOverlay" class="fixed inset-0 z-[90] hidden bg-black/25"></div>

    <div id="addModal" class="fixed inset-0 z-[100] hidden items-center justify-center">
        <div class="relative w-full max-w-[680px] rounded-[18px] border border-[#3d3d3d] bg-white px-12 pb-10 pt-16 shadow-xl">
            <button type="button" class="closeModal absolute right-6 top-5 text-[52px] leading-none text-[#4a4a4a]">
                ×
            </button>

            <h3 class="text-center text-[28px] font-bold text-[#4a4a4a]">
                Tambah Stok Produk
            </h3>
            <p class="mt-2 text-center text-[18px] text-[#66708a]">
                Isi form berikut untuk menambah data stok baru
            </p>

            <form id="addStockForm" action="{{ route('admin.stok.store') }}" method="POST" class="mt-10">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-[18px] font-medium text-[#66708a]">Jenis Produk</label>
                        <input
                            type="text"
                            name="jenis_produk"
                            id="add_jenis_produk"
                            value="{{ old('jenis_produk') }}"
                            placeholder="Contoh : Maggot"
                            class="h-[50px] w-full rounded-[6px] border border-[#d1d1d1] px-5 text-[18px] outline-none"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-[18px] font-medium text-[#66708a]">Jumlah Stok</label>
                        <input
                            type="text"
                            inputmode="decimal"
                            name="jumlah_stok"
                            id="add_jumlah_stok"
                            value="{{ old('jumlah_stok') }}"
                            placeholder="Contoh : 50.5"
                            class="h-[50px] w-full rounded-[6px] border border-[#d1d1d1] px-5 text-[18px] outline-none"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-[18px] font-medium text-[#66708a]"></label>
                        <input
                            type="hidden"
                            name="tanggal_input"
                            id="add_tanggal_input"
                            value="{{ old('tanggal_input', date('Y-m-d')) }}"
                            class="h-[50px] w-full rounded-[6px] border border-[#d1d1d1] px-5 text-[18px] outline-none"
                        >
                    </div>

                </div>

                <div class="mt-12 flex justify-center">
                    <button
                        type="button"
                        id="submitAddButton"
                        class="h-[52px] w-[134px] rounded-[6px] bg-[#21a078] text-[18px] font-bold text-white hover:bg-[#1a8b68]"
                    >
                        SIMPAN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center">
        <div class="relative w-full max-w-[680px] rounded-[18px] border border-[#3d3d3d] bg-white px-12 pb-10 pt-16 shadow-xl">
            <button type="button" class="closeModal absolute right-6 top-5 text-[52px] leading-none text-[#4a4a4a]">
                ×
            </button>

            <h3 class="text-center text-[28px] font-bold text-[#4a4a4a]">
                Edit Stok Produk
            </h3>
            <p class="mt-2 text-center text-[18px] text-[#66708a]">
                Ubah data stok produk sesuai kebutuhan
            </p>

            <form id="editStockForm" method="POST" class="mt-10">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-[18px] font-medium text-[#66708a]">Jenis Produk</label>
                        <input
                            type="text"
                            name="jenis_produk"
                            id="edit_jenis_produk"
                            placeholder="Contoh : Maggot"
                            class="h-[50px] w-full rounded-[6px] border border-[#d1d1d1] px-5 text-[18px] outline-none"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-[18px] font-medium text-[#66708a]">Jumlah Stok</label>
                            <input
                                type="text"
                                inputmode="decimal"
                                name="jumlah_stok"
                                id="edit_jumlah_stok"
                                placeholder="Contoh : 50.5"
                                class="h-[50px] w-full rounded-[6px] border border-[#d1d1d1] px-5 text-[18px] outline-none"
                            >
                    </div>
                    <div>
                        <label class="mb-2 block text-[18px] font-medium text-[#66708a]"></label>
                        <input
                            type="hidden"
                            name="tanggal_input"
                            id="edit_tanggal_input"
                            value="{{ date('Y-m-d') }}"
                        >
                    </div>
                </div>

                <div class="mt-12 flex justify-center">
                    <button
                        type="button"
                        id="submitEditButton"
                        class="h-[52px] w-[134px] rounded-[6px] bg-[#21a078] text-[18px] font-bold text-white hover:bg-[#1a8b68]"
                    >
                        SIMPAN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="confirmEditPopup" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/25">
        <div class="w-[390px] rounded bg-white px-8 py-8 text-center shadow-xl">
            <p class="mb-7 text-[26px] font-extrabold leading-tight text-[#66708a]">
                Apakah anda yakin dengan
                perubahan yang dilakukan?
            </p>

            <div class="flex justify-center gap-5">
                <button
                    type="button"
                    id="confirmEditYes"
                    class="h-[48px] w-[138px] rounded bg-[#21a078] text-[18px] font-bold text-white hover:bg-[#1a8b68]"
                >
                    YA
                </button>

                <button
                    type="button"
                    id="confirmEditNo"
                    class="h-[48px] w-[138px] rounded bg-[#ff1010] text-[18px] font-bold text-white hover:bg-[#dc0b0b]"
                >
                    TIDAK
                </button>
            </div>
        </div>
    </div>

    <div id="requiredPopup" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/25">
        <div class="w-[430px] rounded bg-white p-8 text-center shadow-xl">
            <p class="text-[30px] font-extrabold leading-tight text-[#66708a]">
                Semua field<br>wajib diisi
            </p>
        </div>
    </div>

    <div id="invalidPopup" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/25">
        <div class="w-[430px] rounded bg-white p-8 text-center shadow-xl">
            <div class="mb-4 text-[58px] text-red-500">⚠</div>
            <p class="text-[30px] font-extrabold leading-tight text-[#66708a]">
                Data tidak valid.<br>Silahkan isi kembali
            </p>
        </div>
    </div>

    <div id="successAddPopup" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/25">
        <div class="w-[430px] rounded bg-white p-8 text-center shadow-xl">
            <div class="mx-auto mb-4 flex h-[76px] w-[76px] items-center justify-center rounded-full bg-[#21a078] text-[52px] font-bold text-white">✓</div>
            <p class="text-[30px] font-extrabold leading-tight text-[#66708a]">
                Data stok berhasil<br>ditambahkan
            </p>
        </div>
    </div>

    <div id="successEditPopup" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/25">
        <div class="w-[430px] rounded bg-white p-8 text-center shadow-xl">
            <div class="mx-auto mb-4 flex h-[76px] w-[76px] items-center justify-center rounded-full bg-[#21a078] text-[52px] font-bold text-white">✓</div>
            <p class="text-[30px] font-extrabold leading-tight text-[#66708a]">
                Data stok berhasil<br>diperbarui
            </p>
        </div>
    </div>

    <script>
        const overlay = document.getElementById('pageOverlay');

        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        const confirmEditPopup = document.getElementById('confirmEditPopup');

        const addStockForm = document.getElementById('addStockForm');
        const editStockForm = document.getElementById('editStockForm');

        const openAddModalButton = document.getElementById('openAddModal');
        const submitAddButton = document.getElementById('submitAddButton');
        const submitEditButton = document.getElementById('submitEditButton');

        const confirmEditYes = document.getElementById('confirmEditYes');
        const confirmEditNo = document.getElementById('confirmEditNo');

        function showFlex(el) {
            el.classList.remove('hidden');
            el.classList.add('flex');
        }

        function hideFlex(el) {
            el.classList.add('hidden');
            el.classList.remove('flex');
        }

        function openModal(modal) {
            showFlex(overlay);
            showFlex(modal);
        }

        function closeAllModal() {
            hideFlex(overlay);
            hideFlex(addModal);
            hideFlex(editModal);
            hideFlex(confirmEditPopup);
        }

        function showPopup(id) {
            const popup = document.getElementById(id);

            if (!popup) return;

            showFlex(popup);

            function closePopup() {
                hideFlex(popup);
            }

            popup.onclick = closePopup;

            setTimeout(function () {
                closePopup();
            }, 1800);
        }

        function validateFieldSet(prefix) {
            const jenisInput = document.getElementById(prefix + '_jenis_produk');
            const jumlahInput = document.getElementById(prefix + '_jumlah_stok');
            const tanggalInput = document.getElementById(prefix + '_tanggal_input');

            const jenis = jenisInput.value.trim();
            const jumlah = jumlahInput.value.trim().replace(',', '.');
            const tanggal = tanggalInput.value.trim();

            jumlahInput.value = jumlah;

            if (jenis === '' || jumlah === '' || tanggal === '') {
                showPopup('requiredPopup');
                return false;
            }

            if (
                jenis.length > 30 ||
                isNaN(jumlah) ||
                Number(jumlah) < 0
            ) {
                showPopup('invalidPopup');
                return false;
            }

            return true;
        }

        openAddModalButton.addEventListener('click', function () {
            openModal(addModal);
        });

        document.querySelectorAll('.closeModal').forEach(function (button) {
            button.addEventListener('click', function () {
                closeAllModal();
            });
        });

        overlay.addEventListener('click', function () {
            closeAllModal();
        });

        submitAddButton.addEventListener('click', function () {
            if (!validateFieldSet('add')) {
                return;
            }

            addStockForm.submit();
        });

        document.querySelectorAll('.openEditModal').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const jenis = this.dataset.jenis;
                const jumlah = this.dataset.jumlah;
                const tanggal = this.dataset.tanggal;

                editStockForm.action = `/admin/manajemen-stok/${id}/update`;
                document.getElementById('edit_jenis_produk').value = jenis;
                document.getElementById('edit_jumlah_stok').value = jumlah;
                document.getElementById('edit_tanggal_input').value = "{{ date('Y-m-d') }}";

                openModal(editModal);
            });
        });

        submitEditButton.addEventListener('click', function () {
            if (!validateFieldSet('edit')) {
                return;
            }

            showFlex(confirmEditPopup);
        });

        confirmEditNo.addEventListener('click', function () {
            hideFlex(confirmEditPopup);
        });

        confirmEditYes.addEventListener('click', function () {
            editStockForm.submit();
        });

        @if (session('open_modal') === 'add')
            openModal(addModal);
        @endif

        @if (session('open_modal') === 'edit')
            @php
                $editStock = \Illuminate\Support\Facades\DB::table('stok_produk')
                    ->where('id_stok', session('edit_id'))
                    ->first();
            @endphp

            @if ($editStock)
                editStockForm.action = `/admin/manajemen-stok/{{ $editStock->id_stok }}/update`;
                document.getElementById('edit_jenis_produk').value = @json(old('jenis_produk', $editStock->jenis_produk));
                document.getElementById('edit_jumlah_stok').value = @json(old('jumlah_stok', $editStock->jumlah_stok));
                document.getElementById('edit_tanggal_input').value = @json(old('tanggal_input', $editStock->tanggal_input));
                openModal(editModal);
            @endif
        @endif

        @if (session('popup') === 'required')
            showPopup('requiredPopup');
        @endif

        @if (session('popup') === 'invalid')
            showPopup('invalidPopup');
        @endif

        @if (session('popup') === 'success_add')
            showPopup('successAddPopup');
        @endif

        @if (session('popup') === 'success_edit')
            showPopup('successEditPopup');
        @endif
    </script>
</body>
</html>
