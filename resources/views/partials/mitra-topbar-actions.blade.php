<div class="flex items-center gap-5 relative" id="mitraTopbarActionsWrapper">
    <button type="button" class="relative text-[#4a4a4a]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V5a2 2 0 1 0-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 1 1-6 0m6 0H9"/>
        </svg>

        @if(($notificationCount ?? 0) > 0)
            <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">
                {{ $notificationCount }}
            </span>
        @endif
    </button>

    <div class="relative">
        <button
            type="button"
            id="mitraProfileMenuButton"
            class="flex h-12 w-12 items-center justify-center rounded-lg bg-transparent text-[#4a4a4a] transition hover:bg-[#bfe6da] active:bg-[#bfe6da]"
            >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
            </svg>
        </button>

        <div
            id="mitraProfileMenuDropdown"
            class="absolute right-0 mt-2 hidden w-[170px] overflow-hidden rounded-md border border-[#4a4a4a] bg-white shadow-md z-50"
        >
            <a
                href="{{ route('mitra.profile') }}"
                class="flex items-center gap-2 border-b border-[#4a4a4a] px-3 py-2 text-[16px] text-[#2f2f2f] hover:bg-gray-50"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0"/>
                </svg>
                <span>Profil</span>
            </a>

                <button
                    type="button"
                    data-open-logout-popup
                    class="flex w-full items-center gap-2 px-3 py-2 text-left text-[16px] font-semibold text-red-500 hover:bg-red-50"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('mitraProfileMenuButton');
        const dropdown = document.getElementById('mitraProfileMenuDropdown');
        const wrapper = document.getElementById('mitraTopbarActionsWrapper');

        if (!button || !dropdown || !wrapper) return;

    function openProfileMenu() {
        dropdown.classList.remove('hidden');
        button.classList.remove('bg-transparent');
        button.classList.add('bg-[#bfe6da]');
    }

    function closeProfileMenu() {
        dropdown.classList.add('hidden');
        button.classList.add('bg-transparent');
        button.classList.remove('bg-[#bfe6da]');
    }

    button.addEventListener('click', function (e) {
        e.stopPropagation();

        if (dropdown.classList.contains('hidden')) {
            openProfileMenu();
        } else {
            closeProfileMenu();
        }
    });

    document.addEventListener('click', function (e) {
        if (!wrapper.contains(e.target)) {
            closeProfileMenu();
        }
    });
    });
</script>
@include('partials.logout-confirm-popup')
