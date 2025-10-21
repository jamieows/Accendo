<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<main class="dashboard-content">
  <div class="welcome-card">
    <span class="wave">📚</span>
    <div class="welcome-text">My Courses</div>
  </div>

  <section class="dashboard-grid single-column">
    <div class="card courses-card">
      <h3>Enrolled Subjects</h3>
      <div class="course-grid">
        <div class="course-box filipino">
          <h4>Filipino</h4>
          <p>Language and Culture Studies</p>
          <button>Continue</button>
        </div>

        <div class="course-box math">
          <h4>Mathematics</h4>
          <p>Numbers, Logic & Problem Solving</p>
          <button>Continue</button>
        </div>

        <div class="course-box science">
          <h4>Science</h4>
          <p>Explore the Physical & Natural World</p>
          <button>Continue</button>
        </div>

        <div class="course-box history">
          <h4>History</h4>
          <p>Understanding the Past to Shape the Future</p>
          <button>Continue</button>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include('includes/footer.php'); ?>
