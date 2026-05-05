<div id="confirmChangePopup" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/45">
    <div class="w-[510px] rounded bg-white px-12 py-9 text-center shadow-xl">
        <p class="mb-9 text-[30px] font-extrabold leading-tight text-[#66708a]">
            Apakah anda yakin dengan<br>
            perubahan yang dilakukan?
        </p>

        <div class="flex justify-center gap-8">
            <button
                type="button"
                id="confirmChangeYes"
                class="h-[62px] w-[180px] rounded bg-[#21a078] text-[22px] font-extrabold text-white hover:bg-[#188d69]"
            >
                YA
            </button>

            <button
                type="button"
                id="confirmChangeNo"
                class="h-[62px] w-[180px] rounded bg-[#ff1010] text-[22px] font-extrabold text-white hover:bg-[#dc0b0b]"
            >
                TIDAK
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const popup = document.getElementById('confirmChangePopup');
        const yesButton = document.getElementById('confirmChangeYes');
        const noButton = document.getElementById('confirmChangeNo');

        let targetForm = null;

        function showConfirmPopup(form) {
            targetForm = form;
            popup.classList.remove('hidden');
            popup.classList.add('flex');
        }

        function hideConfirmPopup() {
            targetForm = null;
            popup.classList.add('hidden');
            popup.classList.remove('flex');
        }

        document.querySelectorAll('[data-confirm-submit]').forEach(function (button) {
            button.addEventListener('click', function () {
                const formId = button.getAttribute('data-form');
                const form = document.getElementById(formId);

                if (!form) return;

                showConfirmPopup(form);
            });
        });

        yesButton.addEventListener('click', function () {
            if (targetForm) {
                targetForm.submit();
            }
        });

        noButton.addEventListener('click', function () {
            hideConfirmPopup();
        });

        popup.addEventListener('click', function (event) {
            if (event.target === popup) {
                hideConfirmPopup();
            }
        });
    });
</script>
