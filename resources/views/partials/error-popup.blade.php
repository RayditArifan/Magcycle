@if (session('error_popup'))
    <div id="errorPopup" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/45">
        <div class="flex min-h-[210px] w-[460px] flex-col items-center justify-center rounded bg-white p-7 text-center shadow-xl">
            <svg class="mb-5 h-[70px] w-[80px]" viewBox="0 0 100 90">
                <path d="M50 8 L92 82 H8 Z" fill="none" stroke="#ff1010" stroke-width="8" stroke-linejoin="round"/>
                <line x1="50" y1="31" x2="50" y2="55" stroke="#ff1010" stroke-width="8" stroke-linecap="round"/>
                <circle cx="50" cy="68" r="5" fill="#ff1010"/>
            </svg>

            <p class="text-[28px] font-extrabold leading-tight text-[#66708a]">
                {!! nl2br(e(session('error_popup'))) !!}
            </p>
        </div>
    </div>

    <script>
        const errorPopup = document.getElementById('errorPopup');

        if (errorPopup) {
            errorPopup.addEventListener('click', function () {
                errorPopup.remove();
            });

            setTimeout(function () {
                if (errorPopup) {
                    errorPopup.remove();
                }
            }, 2200);
        }
    </script>
@endif
