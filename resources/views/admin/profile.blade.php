@extends('layouts.admin')

@section('page_title', 'Profil Saya')

@section('content')
<section class="p-6 md:p-10">
    <div class="ml-4 md:ml-8 max-w-[790px] rounded-[20px] border border-[#24b18a] bg-white px-6 py-6 md:px-12 md:py-10">
        <div class="mb-8 flex items-center justify-between">
            <h2 class="text-[24px] md:text-[26px] font-bold text-[#4a4a4a]">
                Profil Saya
            </h2>

            <a href="{{ route('admin.profile.edit') }}"
               class="inline-flex h-[52px] min-w-[100px] items-center justify-center rounded-[12px] border border-[#24b18a] px-6 text-[17px] font-bold text-[#24b18a] hover:bg-[#24b18a] hover:text-white transition">
                Edit
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-[220px_1fr] items-center gap-y-4 text-[18px]">
            <label class="text-[#333]">Username</label>
            <input value="{{ $profile->username ?? '' }}" disabled
                class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#888]">

            <label class="text-[#333]">Email</label>
            <input value="{{ $profile->email ?? '' }}" disabled
                class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#888]">

            <label class="text-[#333]">Nomor Telepon</label>
            <input value="{{ $profile->no_hp ?? '' }}" disabled
                class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#888]">

            <label class="text-[#333]">Password</label>
            <input value="********" disabled
                class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#888]">

            <label class="text-[#333]">Alamat</label>
            <input value="{{ $alamatParts['alamat'] ?? '' }}" disabled
                class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#888]">

            <label class="text-[#333]">Provinsi</label>
            <input value="{{ $alamatParts['provinsi'] ?? '' }}" disabled
                class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#888]">

            <label class="text-[#333]">Kabupaten/Kota</label>
            <input value="{{ $alamatParts['kab_kota'] ?? '' }}" disabled
                class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#888]">

            <label class="text-[#333]">Kecamatan</label>
            <input value="{{ $alamatParts['kecamatan'] ?? '' }}" disabled
                class="h-[42px] w-full max-w-[360px] rounded-[10px] border border-[#555] bg-white px-4 text-[#888]">
        </div>
    </div>
</section>
@endsection
