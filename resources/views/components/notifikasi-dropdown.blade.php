@php
    use App\Models\Notifikasi;
    use Illuminate\Support\Facades\Session;

    $notifUserId = Session::get('user_id');
    $notifRole   = Session::get('role'); // 'admin' or 'mitra'

    $notifBelumDibaca = collect();
    $notifSudahDibaca = collect();
    $jumlahBelumDibaca = 0;

    if ($notifUserId && $notifRole) {
        $notifBelumDibaca = Notifikasi::where('recipient_id', $notifUserId)
            ->where('recipient_role', $notifRole)
            ->where('is_read', false)
            ->latest()
            ->get();

        $notifSudahDibaca = Notifikasi::where('recipient_id', $notifUserId)
            ->where('recipient_role', $notifRole)
            ->where('is_read', true)
            ->latest()
            ->get();

        $jumlahBelumDibaca = $notifBelumDibaca->count();
    }
@endphp

<div class="notif-wrapper">
    <button type="button" class="notif-button" id="notifButton">
        <span class="notif-bell">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                <path d="M18 8A6 6 0 0 0 6 8C6 15 3 17 3 17H21C21 17 18 15 18 8Z" stroke="#111" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.73 21A2 2 0 0 1 10.27 21" stroke="#111" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>

        @if ($jumlahBelumDibaca > 0)
            <span class="notif-red-dot"></span>
        @endif
    </button>

    <div class="notif-panel" id="notifPanel">
        <div class="notif-title">NOTIFIKASI</div>

        <div class="notif-section-title">Belum Dibaca</div>

        <div class="notif-scroll-area">
            @forelse ($notifBelumDibaca as $notif)
                <div class="notif-item unread" data-id="{{ $notif->id }}" data-url="{{ $notif->url }}">
                    @if ($notifRole === 'admin')
                        <div class="notif-avatar"></div>
                    @endif

                    <div class="notif-content">
                        <div class="notif-item-title">{{ $notif->judul }}</div>
                        <div class="notif-message">{{ $notif->pesan }}</div>
                        <div class="notif-meta">
                            {{ $notif->created_at->diffForHumans() }} · {{ $notif->kategori }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="notif-empty">Tidak ada notifikasi baru</div>
            @endforelse
        </div>

        <div class="notif-section-title read-title">Sudah Dibaca</div>

        <div class="notif-scroll-area">
            @forelse ($notifSudahDibaca as $notif)
                <div class="notif-item read" data-id="{{ $notif->id }}" data-url="{{ $notif->url }}">
                    @if ($notifRole === 'admin')
                        <div class="notif-avatar"></div>
                    @endif

                    <div class="notif-content">
                        <div class="notif-item-title">{{ $notif->judul }}</div>
                        <div class="notif-message">{{ $notif->pesan }}</div>
                        <div class="notif-meta">
                            {{ $notif->created_at->diffForHumans() }} · {{ $notif->kategori }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="notif-empty">Belum ada notifikasi yang dibaca</div>
            @endforelse
        </div>
    </div>
</div>
