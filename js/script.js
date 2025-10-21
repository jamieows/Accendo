// Weekly Progress Chart
document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById("progressChart");
  if (ctx) {
    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Science", "English", "Math", "Fil"],
        datasets: [
          {
            label: "Week 1",
            data: [15, 20, 25, 30],
            backgroundColor: "#94e0ff",
          },
          {
            label: "Week 2",
            data: [10, 18, 20, 25],
            backgroundColor: "#00a7c6",
          },
          {
            label: "Week 3",
            data: [20, 28, 40, 50],
            backgroundColor: "#006a8e",
          },
        ],
      },
      options: {
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { position: "top" } },
      },
    });
  }

  // Donut charts
  const donutCharts = [
    { id: "chart1", value: 92 },
    { id: "chart2", value: 83 },
    { id: "chart3", value: 78 },
    { id: "chart4", value: 97 },
    { id: "chart5", value: 96 },
    { id: "chart6", value: 89 },
  ];

  donutCharts.forEach((chart) => {
    const c = document.getElementById(chart.id);
    if (c) {
      new Chart(c, {
        type: "doughnut",
        data: {
          datasets: [
            {
              data: [chart.value, 100 - chart.value],
              backgroundColor: ["#00a7c6", "#e3eefe"],
              borderWidth: 0,
            },
          ],
        },
        options: {
          cutout: "75%",
          plugins: { legend: { display: false }, tooltip: { enabled: false } },
        },
      });
    }
  });
});
