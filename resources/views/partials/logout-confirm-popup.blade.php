<div
    id="logoutConfirmPopup"
    class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/45"
>
    <div class="w-[510px] rounded bg-white px-12 py-9 text-center shadow-xl">
        <p class="mb-9 text-[30px] font-extrabold leading-tight text-[#66708a]">
            Apakah anda yakin ingin<br>
            keluar dari sistem
        </p>

        <div class="flex justify-center gap-8">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="h-[62px] w-[180px] rounded bg-[#21a078] text-[22px] font-extrabold text-white hover:bg-[#188d69]"
                >
                    YA
                </button>
            </form>

            <button
                type="button"
                id="cancelLogoutButton"
                class="h-[62px] w-[180px] rounded bg-[#ff1010] text-[22px] font-extrabold text-white hover:bg-[#dc0b0b]"
            >
                TIDAK
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const popup = document.getElementById('logoutConfirmPopup');
        const cancelButton = document.getElementById('cancelLogoutButton');
        const logoutButtons = document.querySelectorAll('[data-open-logout-popup]');

        if (!popup) return;

        logoutButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                popup.classList.remove('hidden');
                popup.classList.add('flex');
            });
        });

        if (cancelButton) {
            cancelButton.addEventListener('click', function () {
                popup.classList.add('hidden');
                popup.classList.remove('flex');
            });
        }

        popup.addEventListener('click', function (event) {
            if (event.target === popup) {
                popup.classList.add('hidden');
                popup.classList.remove('flex');
            }
        });
    });
</script>
