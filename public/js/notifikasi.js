document.addEventListener('DOMContentLoaded', function () {
    const notifButton = document.getElementById('notifButton');
    const notifPanel = document.getElementById('notifPanel');

    if (!notifButton || !notifPanel) return;

    notifButton.addEventListener('click', function (event) {
        event.stopPropagation();
        const isOpen = notifPanel.classList.toggle('show');
        notifButton.classList.toggle('active', isOpen);
    });

    document.addEventListener('click', function (event) {
        if (!notifPanel.contains(event.target) && !notifButton.contains(event.target)) {
            notifPanel.classList.remove('show');
            notifButton.classList.remove('active');
        }
    });

    const notifItems = document.querySelectorAll('.notif-item');

    notifItems.forEach(function (item) {
        item.addEventListener('click', function () {
            const notifId = this.dataset.id;
            const notifUrl = this.dataset.url;

            fetch(`/notifikasi/${notifId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (notifUrl && notifUrl !== 'null' && notifUrl !== '') {
                    window.location.href = notifUrl;
                } else {
                    location.reload();
                }
            })
            .catch(() => {
                if (notifUrl && notifUrl !== 'null' && notifUrl !== '') {
                    window.location.href = notifUrl;
                }
            });
        });
    });
});
