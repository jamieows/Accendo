<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<main class="dashboard-content">
  <div class="welcome-card">
    <span class="wave">📝</span>
    <div class="welcome-text">Assignments</div>
  </div>

  <section class="assignments-section">
    <div class="assignment-tabs">
      <button class="tab-btn active" onclick="showTab('assigned')">ASSIGNED</button>
      <button class="tab-btn" onclick="showTab('missing')">MISSING</button>
      <button class="tab-btn" onclick="showTab('done')">DONE</button>
    </div>

    <div class="tab-content">
      <!-- ASSIGNED TAB -->
      <div id="assigned" class="tab-panel active">
        <div class="assignment-card">
          <h4>Essay on Environment</h4>
          <p><strong>Subject:</strong> English</p>
          <p><strong>Due Date:</strong> Oct 22, 2025</p>
          <button>View Assignment</button>
        </div>

        <div class="assignment-card">
          <h4>Quiz on Fractions</h4>
          <p><strong>Subject:</strong> Math</p>
          <p><strong>Due Date:</strong> Oct 24, 2025</p>
          <button>Start Quiz</button>
        </div>
      </div>

      <!-- MISSING TAB -->
      <div id="missing" class="tab-panel">
        <div class="assignment-card missing">
          <h4>Science Lab Report</h4>
          <p><strong>Subject:</strong> Science</p>
          <p><strong>Due Date:</strong> Oct 10, 2025</p>
          <button disabled>Missed</button>
        </div>
      </div>

      <!-- DONE TAB -->
      <div id="done" class="tab-panel">
        <div class="assignment-card done">
          <h4>Filipino Poem Project</h4>
          <p><strong>Subject:</strong> Filipino</p>
          <p><strong>Submitted:</strong> Oct 15, 2025</p>
          <button disabled>Completed</button>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include('includes/footer.php'); ?>

<script>
  // JavaScript for tab switching
  function showTab(tabId) {
    const tabs = document.querySelectorAll('.tab-panel');
    const buttons = document.querySelectorAll('.tab-btn');
    
    tabs.forEach(tab => tab.classList.remove('active'));
    buttons.forEach(btn => btn.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
  }
</script>
