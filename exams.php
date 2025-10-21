<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<main class="dashboard-content">
  <div class="welcome-card">
    <span class="wave">🧮</span>
    <div class="welcome-text">Exams & Quizzes</div>
  </div>

  <section class="exams-section">
    <div class="exam-tabs">
      <button class="tab-btn active" onclick="showExamTab('assigned')">ASSIGNED</button>
      <button class="tab-btn" onclick="showExamTab('due')">DUE TODAY</button>
      <button class="tab-btn" onclick="showExamTab('done')">DONE</button>
    </div>

    <div class="tab-content">
      <!-- ASSIGNED TAB -->
      <div id="assigned" class="exam-panel active">
        <div class="exam-card">
          <h4>History</h4>
          <p><strong>Status:</strong> Assigned</p>
          <p><strong>Due Date:</strong> Oct 25, 2025</p>
          <button>START QUIZ</button>
        </div>

        <div class="exam-card">
          <h4>Science</h4>
          <p><strong>Status:</strong> Assigned</p>
          <p><strong>Due Date:</strong> Oct 26, 2025</p>
          <button>START QUIZ</button>
        </div>
      </div>

      <!-- DUE TODAY TAB -->
      <div id="due" class="exam-panel">
        <div class="exam-card due">
          <h4>Mathematics</h4>
          <p><strong>Status:</strong> Due Today</p>
          <p><strong>Time:</strong> 2:00 PM - 3:00 PM</p>
          <button>START QUIZ</button>
        </div>
      </div>

      <!-- DONE TAB -->
      <div id="done" class="exam-panel">
        <div class="exam-card done">
          <h4>Filipino</h4>
          <p><strong>Status:</strong> Completed</p>
          <p><strong>Date:</strong> Oct 18, 2025</p>
          <button>VIEW SCORES</button>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include('includes/footer.php'); ?>

<script>
  // Simple tab switcher for exams
  function showExamTab(tabId) {
    const panels = document.querySelectorAll('.exam-panel');
    const buttons = document.querySelectorAll('.exam-tabs .tab-btn');

    panels.forEach(p => p.classList.remove('active'));
    buttons.forEach(b => b.classList.remove('active'));

    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
  }
</script>
