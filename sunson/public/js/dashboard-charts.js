(function () {
  if (typeof window.Chart === 'undefined') {
    console.error('Dashboard charts could not load because Chart.js is unavailable.');
    return;
  }

  var colors = ['#1d5947', '#efbb53', '#4f8d73', '#d77e53', '#668ac0', '#8d6a9f'];

  document.querySelectorAll('canvas[data-chart]').forEach(function (canvas) {
    var labels;
    var values;

    try {
      labels = JSON.parse(canvas.dataset.labels || '[]');
      values = JSON.parse(canvas.dataset.values || '[]');
    } catch (error) {
      console.error('Unable to read dashboard chart data.', error);
      return;
    }

    if (!Array.isArray(labels) || !Array.isArray(values) || labels.length !== values.length) {
      console.error('Dashboard chart labels and values must be arrays of equal length.');
      return;
    }

    var chartType = canvas.dataset.chart;
    var isDoughnut = chartType === 'doughnut';
    var isHorizontal = canvas.dataset.axis === 'y';
    if (isHorizontal) {
      canvas.parentElement.style.height = Math.min(600, Math.max(260, labels.length * 38)) + 'px';
    }

    var dataset = {
      label: canvas.getAttribute('aria-label') || 'Dashboard data',
      data: values.map(Number),
      backgroundColor: isDoughnut ? labels.map(function (_, index) {
        return colors[index % colors.length];
      }) : (chartType === 'line' ? 'rgba(29, 89, 71, 0.14)' : '#1d5947'),
      borderColor: isDoughnut ? '#fff' : '#1d5947',
      borderWidth: chartType === 'line' ? 2 : 1
    };

    if (chartType === 'line') {
      dataset.fill = true;
      dataset.tension = 0.3;
      dataset.pointBackgroundColor = '#1d5947';
    }

    new window.Chart(canvas, {
      type: chartType,
      data: { labels: labels, datasets: [dataset] },
      options: {
        indexAxis: isHorizontal ? 'y' : 'x',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: isDoughnut, position: 'bottom' },
          tooltip: { enabled: true }
        },
        scales: isDoughnut ? {} : {
          x: { beginAtZero: true, grid: { display: isHorizontal } },
          y: { beginAtZero: true, grid: { display: !isHorizontal } }
        }
      }
    });
  });
}());
