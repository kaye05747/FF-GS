document.addEventListener('DOMContentLoaded', function () {
    const notificationCount = document.getElementById('notificationCount');
    const notificationDropdown = document.querySelector('.notification-dropdown');

    window.fetchNotifications = function() {
        fetch('get_notifications.php')
            .then(response => response.json())
            .then(data => {
                console.log('Fetched notifications:', data);
                let unreadCount = 0;
                if (notificationDropdown) { // Check if notificationDropdown element exists
                    if (data.length > 0) {
                        let notificationsHtml = '';
                        data.forEach(notification => {
                            if (notification.is_read == 0) {
                                unreadCount++;
                            }
                            notificationsHtml += `
                                <li>
                                    <a class="dropdown-item notification-item ${notification.is_read == 0 ? 'fw-bold' : ''}" href="#" data-id="${notification.id}">
                                        ${notification.message}
                                    </a>
                                </li>
                            `;
                        });
                        notificationDropdown.innerHTML = notificationsHtml;
                    } else {
                        notificationDropdown.innerHTML = '<li><a class="dropdown-item" href="#">No new notifications</a></li>';
                    }
                }

                console.log('Unread count:', unreadCount);
                if (notificationCount) { // Check if notificationCount element exists
                    if (unreadCount > 0) {
                        notificationCount.textContent = unreadCount;
                        notificationCount.style.display = 'block';
                    } else {
                        notificationCount.style.display = 'none';
                    }
                }
            });
    };

    window.fetchNotifications();
    setInterval(window.fetchNotifications, 5000); // Fetch every 5 seconds

    if (notificationDropdown) {
        notificationDropdown.addEventListener('click', (e) => {
            const target = e.target.closest('.notification-item');
            if (target) {
                const notificationId = target.dataset.id;
                fetch('mark_notification_read.php?id=' + notificationId)
                    .then(() => window.fetchNotifications()); // Refresh notifications after marking as read
            }
        });
    }
});