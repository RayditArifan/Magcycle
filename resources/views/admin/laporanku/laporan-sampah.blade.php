@extends('layouts.admin')

@section('page_title', 'LAPORANKU')

@section('content')
<div class="px-8 md:px-10 py-8 overflow-x-hidden">
    @if(session('success'))
        <div class="mb-5 rounded-lg border border-emerald-300 bg-emerald-50 px-5 py-3 text-center text-emerald-700 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.laporanku.laporan-sampah') }}" class="mb-6 flex items-center gap-3">
        <div class="flex h-[56px] flex-1 items-center rounded-xl border border-[#333] bg-white px-4">
            <span class="mr-3 text-2xl text-[#333]">⌕</span>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari username"
                class="w-full bg-transparent text-[17px] outline-none"
            >
        </div>

        <input
            type="date"
            name="tanggal"
            value="{{ request('tanggal') }}"
            class="h-[56px] rounded-lg border-0 bg-[#1fa16f] px-4 text-white outline-none"
        >

        <button type="submit" class="h-[56px] rounded-lg bg-[#1fa16f] px-5 font-semibold text-white hover:bg-[#168864]">
            Filter
        </button>
    </form>

    <div class="w-full rounded-2xl border border-[#159b78] overflow-hidden bg-white">
        <table class="w-full table-fixed text-[17px] text-[#3f3f3f]">
            <thead>
                <tr class="bg-white">
                    <th class="w-[80px] px-4 py-5"></th>
                    <th class="w-[24%] px-4 py-5 text-left font-semibold">Username</th>
                    <th class="w-[26%] px-4 py-5 text-left font-semibold">Tanggal Pengambilan</th>
                    <th class="w-[20%] px-4 py-5 text-left font-semibold">Berat Pengajuan</th>
                    <th class="w-[20%] px-4 py-5 text-left font-semibold">Berat Valid</th>
                </tr>
            </thead>

            <tbody>
                @forelse($laporanSampah as $item)
                    <tr class="{{ $loop->odd ? 'bg-[#8ccfbd]' : 'bg-white' }}">
                        <td class="px-4 py-4">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center overflow-hidden rounded-full bg-[#D9E1E4]">
                                <svg
                                    viewBox="0 0 40 40"
                                    class="h-12 w-12 text-black"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <circle cx="20" cy="14" r="7"></circle>
                                    <path d="M7 38c1.6-9.2 7-14.5 13-14.5S31.4 28.8 33 38H7z"></path>
                                </svg>
                            </div>
                        </td>

                        <td class="px-4 py-4 truncate">{{ $item->username }}</td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->tanggal_pengambilan)->translatedFormat('d F Y') }}
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            {{ rtrim(rtrim(number_format($item->berat_pengajuan, 2, '.', ''), '0'), '.') }} kg
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            {{ rtrim(rtrim(number_format($item->berat_valid, 2, '.', ''), '0'), '.') }} kg
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-500">
                            Belum ada laporan sampah untuk lokasi admin ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-5 py-4">
            {{ $laporanSampah->links() }}
        </div>
    </div>
</div>
@endsection
