// ✅ Sidebar toggle logic
function showSection(id, link) {
  document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('active'));
  document.getElementById(id).classList.add('active');
  document.querySelectorAll('.sidebar .nav-link').forEach(a => a.classList.remove('active'));
  link.classList.add('active');
}

// ✅ Default active section
document.addEventListener("DOMContentLoaded", function() {
  const urlParams = new URLSearchParams(window.location.search);
  const section = urlParams.get('section');

  if (section) {
    showSection(section, document.querySelector(`[onclick="showSection('${section}', this)"]`));
  } else {
    const defaultLink = document.querySelector('.sidebar .nav-link');
    if (defaultLink) defaultLink.classList.add('active');
  }

  // ✅ ChartJS sample
  const ctx = document.getElementById('feedbackChart');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: feedbackChartLabels,
      datasets: [{
        label: 'Feedbacks per Month',
        data: feedbackChartData,
        backgroundColor: 'rgba(46, 204, 113, 0.6)',
        borderColor: '#145A32',
        borderWidth: 1
      }]
    },
    options: { scales: { y: { beginAtZero: true } } }
  });
});
function printFeedback() {
  // Only include the feedback cards (Pending + Done)
  const pendingCards = Array.from(document.querySelectorAll('#pending .feedback-card'));
  const doneCards = Array.from(document.querySelectorAll('#done .feedback-card'));

  const allCardsHTML = [...pendingCards, ...doneCards].map(card => card.outerHTML).join('<br>');

  const printWindow = window.open('', '', 'width=900,height=600');

  printWindow.document.write(`
    <html>
      <head>
        <title>User Feedback Report</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
          body { padding: 20px; font-family: Arial, sans-serif; }
          h2 { color: #28a745; margin-bottom: 20px; }
          .card { margin-bottom: 15px; }
          .card-header { font-weight: bold; }
          .feedback-card .alert { margin-top: 10px; }
        </style>
      </head>
      <body>
        <h2>User Feedback Report</h2>
        ${allCardsHTML}
      </body>
    </html>
  `);

  printWindow.document.close();
  printWindow.focus();
  printWindow.print();
  printWindow.close();
}
