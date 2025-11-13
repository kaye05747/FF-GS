document.addEventListener('DOMContentLoaded', () => {
  const ctx = document.getElementById('feedbackChart');
  const labels = feedbackData.map(item => item.month);
  const counts = feedbackData.map(item => item.count);

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Feedback Count',
        data: counts,
        borderWidth: 1,
        backgroundColor: '#66bb6a',
        borderColor: '#2e7d32'
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  });
});
