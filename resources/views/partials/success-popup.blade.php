@if(session('success_popup'))
    <div class="success-popup fixed inset-0 z-[9999] flex items-center justify-center bg-black/45 px-4">
        <div class="success-popup-box w-full max-w-[520px] bg-white px-8 py-10 text-center shadow-xl">
            <div class="mx-auto mb-6 flex h-[86px] w-[86px] items-center justify-center rounded-full bg-[#1ea36f] text-[58px] font-bold leading-none text-white">
                ✓
            </div>

            <h2 class="text-[34px] font-extrabold leading-tight text-[#667089]">
                @if(session('success_popup') && session('success_popup') !== 'true')
                    {{ session('success_popup') }}
                @else
                    Perubahan berhasil<br>
                    disimpan
                @endif
            </h2>
        </div>
    </div>

    <script>
        (function () {
            function closeSuccessPopups() {
                document.querySelectorAll('.success-popup').forEach(function (popup) {
                    popup.remove();
                });
            }

            document.querySelectorAll('.success-popup').forEach(function (popup) {
                popup.addEventListener('click', closeSuccessPopups);
            });

            document.querySelectorAll('.success-popup-box').forEach(function (box) {
                box.addEventListener('click', function (event) {
                    event.stopPropagation();
                    closeSuccessPopups();
                });
            });

            setTimeout(closeSuccessPopups, 1800);
        })();
    </script>
@endif
