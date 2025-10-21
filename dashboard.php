<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<main class="dashboard-wrap">


  <!-- content column (dark area with welcome + two-column dashboard) -->
  <div class="dashboard-column">

    <div class="welcome-card">
      <span class="wave">👋</span>
      <div class="welcome-text">Welcome, Dominica!</div>
    </div>

    <div class="dashboard-grid">

      <!-- DONUTS COLUMN -->
      <section class="donut-column">
        <div class="card visit-card">
          <div class="card-title">
            <span>My Visit</span>
            <span class="month">December</span>
          </div>

          <div class="donut-grid">
            <div class="donut-item">
              <canvas id="chart1"></canvas>
              <p><strong>Algorithms structures</strong><br><span>92%</span></p>
            </div>
            <div class="donut-item">
              <canvas id="chart2"></canvas>
              <p><strong>Object program.</strong><br><span>83%</span></p>
            </div>
            <div class="donut-item">
              <canvas id="chart3"></canvas>
              <p><strong>Database program.</strong><br><span>78%</span></p>
            </div>
            <div class="donut-item">
              <canvas id="chart4"></canvas>
              <p><strong>Web develop.</strong><br><span>97%</span></p>
            </div>
            <div class="donut-item">
              <canvas id="chart5"></canvas>
              <p><strong>Mobile application</strong><br><span>96%</span></p>
            </div>
            <div class="donut-item">
              <canvas id="chart6"></canvas>
              <p><strong>Machine learning</strong><br><span>89%</span></p>
            </div>
          </div>
        </div>
      </section>

      <!-- RIGHT STACK (calendar, progress, announcements) -->
      <section class="right-stack">
        <div class="card calendar-card">
          <h3>October 2025</h3>
          <div class="calendar-inner">
            <img src="assets/images/calendar-placeholder.png" alt="Calendar">
          </div>
        </div>

        <div class="card progress-card">
          <h3>Weekly Progress</h3>
          <canvas id="progressChart"></canvas>
        </div>

        <div class="card announcement-card">
          <h3 class="ann-title">ANNOUNCEMENTS</h3>
          <table class="ann-table">
            <thead>
              <tr><th>Date</th><th>To Do’s</th></tr>
            </thead>
            <tbody>
              <tr><td>Oct 13, 2025</td><td>Quiz on math</td></tr>
              <tr><td>Oct 15, 2025</td><td>Essay on english</td></tr>
              <tr><td>Oct 17, 2025</td><td>Poetry on History</td></tr>
            </tbody>
          </table>
        </div>
      </section>

    </div> <!-- dashboard-grid -->

  </div> <!-- dashboard-column -->

</main>

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/script.js"></script>
