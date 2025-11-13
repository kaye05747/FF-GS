document.addEventListener('DOMContentLoaded', function() {
    const unreadFeedbacks = document.querySelectorAll('div.feedback-item[data-feedback-id]');
    unreadFeedbacks.forEach(feedback => {
        const feedbackId = feedback.dataset.feedbackId;
        fetch('mark_notification_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `feedback_id=${feedbackId}`
        });
        
    });
});