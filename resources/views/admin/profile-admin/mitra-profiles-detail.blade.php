@extends('layouts.admin')

@section('page_title', 'Detail Mitra')

@section('content')
<section class="p-6 md:p-10">
    <div class="ml-4 md:ml-8 max-w-[980px] rounded-[20px] border border-[#24b18a] bg-[#f5f5f5] px-8 py-7 md:px-10 md:py-8 relative">
        <a href="{{ route('admin.mitra.profiles') }}"
           class="absolute right-7 top-6 text-[#4a4a4a] hover:text-red-500 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/>
            </svg>
        </a>

        <h2 class="mb-7 text-[22px] font-semibold text-[#4a4a4a]">
            {{ $mitra->username }}
        </h2>

        @if(session('success'))
            <div class="mb-5 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <form id="adminMitraDetailForm" action="{{ route('admin.mitra.profiles.update', $mitra->id_mitra) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                <div>
                    <label class="mb-2 block text-[17px] font-medium text-[#4a4a4a]">Username</label>
                    <input type="text" value="{{ $mitra->username }}" readonly
                        class="h-[40px] w-full rounded-[10px] border border-[#5b5b5b] bg-white px-4 text-[15px] text-gray-500">
                </div>

                <div>
                    <label class="mb-2 block text-[17px] font-medium text-[#4a4a4a]">Email</label>
                    <input type="text" value="{{ $mitra->email }}" readonly
                        class="h-[40px] w-full rounded-[10px] border border-[#5b5b5b] bg-white px-4 text-[15px] text-gray-500">
                </div>

                <div>
                    <label class="mb-2 block text-[17px] font-medium text-[#4a4a4a]">Nomor Telepon</label>
                    <input type="text" value="{{ $mitra->no_hp }}" readonly
                        class="h-[40px] w-full rounded-[10px] border border-[#5b5b5b] bg-white px-4 text-[15px] text-gray-500">
                </div>

                <div>
                    <label class="mb-2 block text-[17px] font-medium text-[#4a4a4a]">Password</label>
                    <input type="text" value="{{ str_repeat('*', min(strlen($mitra->password ?? ''), 8)) }}" readonly
                        class="h-[40px] w-full rounded-[10px] border border-[#5b5b5b] bg-white px-4 text-[15px] text-gray-500">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-[17px] font-medium text-[#4a4a4a]">Alamat</label>
                    <input type="text" value="{{ $mitra->alamat }}" readonly
                        class="h-[48px] w-full rounded-[10px] border border-[#5b5b5b] bg-white px-4 text-[15px] text-gray-500">
                </div>

                <div>
                    <label class="mb-2 block text-[17px] font-medium text-[#4a4a4a]">Provinsi</label>
                    <input type="text" value="-" readonly
                        class="h-[40px] w-full rounded-[10px] border border-[#5b5b5b] bg-white px-4 text-[15px] text-gray-500">
                </div>

                <div>
                    <label class="mb-2 block text-[17px] font-medium text-[#4a4a4a]">Kabupaten/Kota</label>
                    <input type="text" value="-" readonly
                        class="h-[40px] w-full rounded-[10px] border border-[#5b5b5b] bg-white px-4 text-[15px] text-gray-500">
                </div>

                <div>
                    <label class="mb-2 block text-[17px] font-medium text-[#4a4a4a]">Kecamatan</label>
                    <input type="text" value="-" readonly
                        class="h-[40px] w-full rounded-[10px] border border-[#5b5b5b] bg-white px-4 text-[15px] text-gray-500">
                </div>

                <div>
                    <label class="mb-2 block text-[17px] font-medium text-[#4a4a4a]">Status</label>
                    <select name="id_status"
                        class="h-[40px] w-full rounded-[10px] border border-[#5b5b5b] bg-white px-4 text-[15px] text-[#4a4a4a] outline-none focus:border-[#24b18a] focus:ring-2 focus:ring-[#24b18a]/20">
                        @foreach($statuses as $status)
                            <option value="{{ $status->id_status }}" {{ $mitra->id_status === $status->id_status ? 'selected' : '' }}>
                                {{ ucfirst($status->status_akun) }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button
                    type="button"
                    data-confirm-submit
                    data-form="adminMitraDetailForm"
                    class="inline-flex h-[46px] items-center justify-center gap-2 rounded-[6px] bg-[#20a172] px-6 text-[16px] font-semibold text-white hover:bg-[#17885f] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7l-4-4Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M7 3v6h8V3M7 21v-7h10v7"/>
                    </svg>
                    SIMPAN
                </button>
            </div>
        </form>
    </div>
</section>
@include('partials.confirm-change-popup')
@endsection
