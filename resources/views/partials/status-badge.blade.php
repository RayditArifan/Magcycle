@php
    $statusText = $status ?? '-';
    $statusLower = strtolower($statusText);

    $class = match ($statusLower) {
        'telur' => 'bg-[#087250]',
        'bayi larva' => 'bg-[#d38b00]',
        'larva' => 'bg-[#a64a00]',
        'selesai' => 'bg-[#3b0d0d]',
        default => 'bg-[#4a4a4a]',
    };
@endphp

<span class="inline-flex items-center rounded {{ $class }} px-2 py-1 text-[12px] font-medium text-white">
    &bull; {{ $statusText ?: '-' }}
</span>
