<div id="confirmSavePopup"
     class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/20 px-4">
    <div class="w-full max-w-[520px] rounded-[4px] bg-white px-10 py-8 text-center shadow-xl">
        <h2 class="mb-8 text-[30px] md:text-[34px] font-extrabold leading-tight text-[#667089]">
            Apakah anda yakin dengan<br>perubahan yang dilakukan?
        </h2>

        <div class="flex items-center justify-center gap-7">
            <button type="button"
                    id="confirmSaveYes"
                    class="inline-flex h-[62px] w-[180px] items-center justify-center rounded-[6px] bg-[#1ea36f] text-[22px] font-bold text-white hover:bg-[#19885d] transition">
                YA
            </button>

            <button type="button"
                    id="confirmSaveNo"
                    class="inline-flex h-[62px] w-[180px] items-center justify-center rounded-[6px] bg-[#ff1208] text-[22px] font-bold text-white hover:bg-[#db1008] transition">
                TIDAK
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const popup = document.getElementById('confirmSavePopup');
        const btnYes = document.getElementById('confirmSaveYes');
        const btnNo = document.getElementById('confirmSaveNo');

        if (!popup || !btnYes || !btnNo) return;

        let activeForm = null;

        document.querySelectorAll('.needs-confirm-save').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (form.dataset.confirmed === 'true') {
                    return;
                }

                e.preventDefault();
                activeForm = form;
                popup.classList.remove('hidden');
                popup.classList.add('flex');
            });
        });

        btnYes.addEventListener('click', function () {
            if (activeForm) {
                activeForm.dataset.confirmed = 'true';
                activeForm.submit();
            }
        });

        btnNo.addEventListener('click', function () {
            popup.classList.remove('flex');
            popup.classList.add('hidden');
            activeForm = null;
        });

        popup.addEventListener('click', function (e) {
            if (e.target === popup) {
                popup.classList.remove('flex');
                popup.classList.add('hidden');
                activeForm = null;
            }
        });
    });
</script>
