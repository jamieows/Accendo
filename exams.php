<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include('includes/db.php');
include('includes/header.php');
include('includes/sidebar.php');
?>

<main class="dashboard-content">
  <div class="welcome-card">
    <span class="wave">🧩</span>
    <div class="welcome-text">Exams & Quizzes</div>
  </div>

  <section class="exams-section">
    <div class="exam-tabs">
      <button class="tab-btn active" onclick="showTab('due')">DUE</button>
      <button class="tab-btn" onclick="showTab('upcoming')">UPCOMING</button>
      <button class="tab-btn" onclick="showTab('completed')">COMPLETED</button>
    </div>

    <div class="tab-content">
      <!-- DUE TAB -->
      <div id="due" class="tab-panel active">
        <?php
        $sql = "
          SELECT q.quiz_title, q.due_date, q.status, c.course_name
          FROM quizzes q
          JOIN courses c ON q.course_id = c.course_id
          WHERE q.status = 'due'
          ORDER BY q.due_date ASC
        ";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo '
              <div class="exam-card due">
                <h3>' . htmlspecialchars($row['quiz_title']) . '</h3>
                <p><strong>Course:</strong> ' . htmlspecialchars($row['course_name']) . '</p>
                <p><strong>Due Date:</strong> ' . htmlspecialchars($row['due_date']) . '</p>
                <button>Start Exam</button>
              </div>';
          }
        } else {
          echo "<p>No due exams.</p>";
        }
        ?>
      </div>

      <!-- UPCOMING TAB -->
      <div id="upcoming" class="tab-panel">
        <?php
        $sql = "
          SELECT q.quiz_title, q.due_date, q.status, c.course_name
          FROM quizzes q
          JOIN courses c ON q.course_id = c.course_id
          WHERE q.status = 'upcoming'
          ORDER BY q.due_date ASC
        ";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo '
              <div class="exam-card upcoming">
                <h3>' . htmlspecialchars($row['quiz_title']) . '</h3>
                <p><strong>Course:</strong> ' . htmlspecialchars($row['course_name']) . '</p>
                <p><strong>Exam Date:</strong> ' . htmlspecialchars($row['due_date']) . '</p>
                <button disabled>Upcoming</button>
              </div>';
          }
        } else {
          echo "<p>No upcoming exams.</p>";
        }
        ?>
      </div>

      <!-- COMPLETED TAB -->
      <div id="completed" class="tab-panel">
        <?php
        $sql = "
          SELECT q.quiz_title, q.due_date, q.status, c.course_name
          FROM quizzes q
          JOIN courses c ON q.course_id = c.course_id
          WHERE q.status = 'completed'
          ORDER BY q.due_date DESC
        ";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo '
              <div class="exam-card completed">
                <h3>' . htmlspecialchars($row['quiz_title']) . '</h3>
                <p><strong>Course:</strong> ' . htmlspecialchars($row['course_name']) . '</p>
                <p><strong>Completed On:</strong> ' . htmlspecialchars($row['due_date']) . '</p>
                <button disabled>View Score</button>
              </div>';
          }
        } else {
          echo "<p>No completed exams.</p>";
        }
        ?>
      </div>
    </div>
  </section>
</main>

<?php include('includes/footer.php'); ?>

<script>
  // Tab switching
  function showTab(tabId) {
    const tabs = document.querySelectorAll('.tab-panel');
    const buttons = document.querySelectorAll('.tab-btn');
    
    tabs.forEach(tab => tab.classList.remove('active'));
    buttons.forEach(btn => btn.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
  }
</script>
