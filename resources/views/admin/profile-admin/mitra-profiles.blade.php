@extends('layouts.admin')

@section('page_title', 'Profil Mitra')

@section('content')
<section class="px-8 py-6">
    @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-600">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-[20px] border border-[#2db88b] bg-white">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="h-[56px] text-[18px] text-[#4e4e4e]">
                        <th class="w-[70px] px-4"></th>
                        <th class="px-4 font-medium">Akun Mitra</th>
                        <th class="px-4 font-medium">Alamat</th>
                        <th class="px-4 font-medium w-[180px]">Status</th>
                        <th class="px-4 w-[80px]"></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($mitras as $mitra)
                        <tr
                            onclick="window.location='{{ route('admin.mitra.profiles.show', $mitra->id_mitra) }}'"
                            class="cursor-pointer border-t border-[#d5efe6] odd:bg-[#8fd3bd] even:bg-white text-[18px] text-[#4a4a4a] hover:bg-[#bde8d9] transition"
                        >
                            <td class="px-4 py-4">
                                <div class="h-10 w-10 rounded-full bg-[#d9d9d9] flex items-center justify-center overflow-hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#2f2f2f]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 10a4 4 0 100-8 4 4 0 000 8Zm-7 8a7 7 0 1114 0H3Z"/>
                                    </svg>
                                </div>
                            </td>

                            <td class="px-4 py-4 font-medium">
                                <a
                                    href="{{ route('admin.mitra.profiles.show', $mitra->id_mitra) }}"
                                    onclick="event.stopPropagation()"
                                    class="hover:underline"
                                >
                                    {{ $mitra->username }}
                                </a>
                            </td>

                            <td class="px-4 py-4">
                                {{ $mitra->alamat }}
                            </td>

                            <td class="px-4 py-4">
                                @php
                                    $badgeClass = match(strtolower($mitra->status_akun)) {
                                        'aktif' => 'bg-[#0f8b61] text-white',
                                        'banned' => 'bg-[#d91616] text-white',
                                        default => 'bg-gray-500 text-white',
                                    };
                                @endphp

                                <span class="inline-flex items-center rounded-[3px] px-2 py-1 text-[11px] font-semibold {{ $badgeClass }}">
                                    • {{ ucfirst($mitra->status_akun) }}
                                </span>
                            </td>

                            <td class="px-4 py-4">
                                <a
                                    href="{{ route('admin.mitra.profiles.show', $mitra->id_mitra) }}"
                                    onclick="event.stopPropagation()"
                                    class="inline-flex items-center justify-center text-[#4a4a4a] hover:text-[#168f67] transition"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M16.862 3.487a2.25 2.25 0 1 1 3.182 3.182L8.25 18.463 3 21l2.537-5.25L16.862 3.487Z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data profil mitra.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between border-t border-[#2db88b] px-5 py-4">
            <p class="text-[16px] text-[#4a4a4a]">
                Menampilkan {{ $mitras->count() }} dari {{ $mitras->total() }} mitra
            </p>

            <div class="flex items-center gap-2">
                @if ($mitras->onFirstPage())
                    <span class="flex h-8 w-8 items-center justify-center rounded border border-[#2db88b] text-[#2db88b] opacity-50">‹</span>
                @else
                    <a href="{{ $mitras->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded border border-[#2db88b] text-[#2db88b] hover:bg-[#e8faf3]">‹</a>
                @endif

                @for ($page = 1; $page <= $mitras->lastPage(); $page++)
                    <a href="{{ $mitras->url($page) }}"
                       class="flex h-8 min-w-8 items-center justify-center rounded border px-2
                       {{ $mitras->currentPage() == $page
                            ? 'border-[#2db88b] bg-[#8fd3bd] text-[#2b6d59]'
                            : 'border-[#2db88b] text-[#2db88b] hover:bg-[#e8faf3]' }}">
                        {{ $page }}
                    </a>
                @endfor

                @if ($mitras->hasMorePages())
                    <a href="{{ $mitras->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded border border-[#2db88b] text-[#2db88b] hover:bg-[#e8faf3]">›</a>
                @else
                    <span class="flex h-8 w-8 items-center justify-center rounded border border-[#2db88b] text-[#2db88b] opacity-50">›</span>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
