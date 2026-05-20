@php
    $statusText = strtolower($status ?? '-');

    $label = match ($statusText) {
        'telur' => 'Telur',
        'bayi larva' => 'Bayi Larva',
        'larva' => 'Larva',
        'selesai' => 'Selesai',
        default => ucfirst($statusText),
    };

    $badgeClass = match ($statusText) {
        'telur' => 'bg-[#087250]',
        'bayi larva' => 'bg-[#d38b00]',
        'larva' => 'bg-[#9a3f00]',
        'selesai' => 'bg-[#3b0d0d]',
        default => 'bg-[#4a4a4a]',
    };
@endphp

<span class="inline-flex items-center rounded px-2 py-1 text-[12px] font-medium text-white {{ $badgeClass }}">
    &bull; {{ $label }}
</span>
