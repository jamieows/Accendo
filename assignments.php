<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include('includes/db.php');
include('includes/header.php');
include('includes/sidebar.php');

$user_id = (int)($_SESSION['user_id'] ?? 0);
$role = ($_SESSION['role'] ?? 'student');
?>

<main class="dashboard-content">
  <div class="welcome-card">
    <span class="wave">📝</span>
    <div class="welcome-text">
      <?php echo ($role === 'teacher') ? "Manage Assignments" : "Assignments"; ?>
    </div>
  </div>

  <?php if ($role === 'teacher'): ?>
  <!-- UPLOAD FORM (teacher only) -->
  <section class="assignments-section" style="margin-bottom: 25px;">
    <div class="card upload-assignment-form">
      <h3>📤 Upload New Assignment</h3>
      <form action="upload_assignment.php" method="POST">
        <div class="form-group">
          <label>Title:</label>
          <input type="text" name="title" required>
        </div>

        <div class="form-group">
          <label>Description:</label>
          <textarea name="description" rows="3" required></textarea>
        </div>

        <div class="form-group">
          <label>Select Course:</label>
          <select name="course_id" required>
            <option value="">-- Select Course --</option>
            <?php
            // Only show teacher's courses
            $course_sql = "SELECT course_id, course_name FROM courses WHERE teacher_id = $user_id ORDER BY course_name ASC";
            $course_stmt = mysqli_query($conn, $course_sql);

            if ($course_stmt && mysqli_num_rows($course_stmt) > 0) {
              while ($c = mysqli_fetch_assoc($course_stmt)) {
                echo '<option value="' . (int)$c['course_id'] . '">' . htmlspecialchars($c['course_name']) . '</option>';
              }
            } else {
              echo '<option disabled>No courses found. Assign courses to your account first.</option>';
            }
            ?>
          </select>
        </div>

        <div class="form-group">
          <label>Due Date:</label>
          <input type="date" name="due_date" required>
        </div>

        <button type="submit" class="upload-btn">Upload Assignment</button>
      </form>
    </div>
  </section>
  <?php endif; ?>

  <!-- COMMON TAB VIEW -->
  <section class="assignments-section">
    <div class="assignment-tabs">
      <button class="tab-btn active" onclick="showTab('assigned')">ASSIGNED</button>
      <button class="tab-btn" onclick="showTab('missing')">MISSING</button>
      <button class="tab-btn" onclick="showTab('done')">DONE</button>
    </div>

    <div class="tab-content">

      <?php
      // Helper function to fetch assignments
      function fetch_assignments($conn, $role, $user_id, $status) {
        $conds = [];
        $joins = "JOIN courses c ON a.course_id = c.course_id";

        if ($role === 'teacher') {
          $conds[] = "c.teacher_id = " . (int)$user_id;
        } else {
          $conds[] = "a.user_id = " . (int)$user_id;
        }

        $conds[] = "a.status = '" . mysqli_real_escape_string($conn, $status) . "'";
        $where = "WHERE " . implode(' AND ', $conds);

        $sql = "SELECT a.*, c.course_name 
                FROM assignments a 
                $joins 
                $where 
                ORDER BY a.due_date ASC";
        $res = mysqli_query($conn, $sql);
        $rows = [];
        if ($res) while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
        return $rows;
      }
      ?>

      <!-- ASSIGNED -->
      <div id="assigned" class="tab-panel active">
        <?php
        $rows = fetch_assignments($conn, $role, $user_id, 'assigned');
        if (!empty($rows)) {
          foreach ($rows as $r) {
            echo '
            <div class="assignment-card">
              <h4>' . htmlspecialchars($r['title']) . '</h4>
              <p><strong>Subject:</strong> ' . htmlspecialchars($r['course_name']) . '</p>
              <p><strong>Due Date:</strong> ' . htmlspecialchars($r['due_date']) . '</p>
              <p>' . nl2br(htmlspecialchars($r['description'])) . '</p>
              <button>' . (($role === 'teacher') ? 'Edit Assignment' : 'View Assignment') . '</button>
            </div>';
          }
        } else {
          echo "<p>No assigned tasks available.</p>";
        }
        ?>
      </div>

      <!-- MISSING -->
      <div id="missing" class="tab-panel">
        <?php
        $rows = fetch_assignments($conn, $role, $user_id, 'missing');
        if (!empty($rows)) {
          foreach ($rows as $r) {
            echo '
            <div class="assignment-card missing">
              <h4>' . htmlspecialchars($r['title']) . '</h4>
              <p><strong>Subject:</strong> ' . htmlspecialchars($r['course_name']) . '</p>
              <p><strong>Due Date:</strong> ' . htmlspecialchars($r['due_date']) . '</p>
              <p>' . nl2br(htmlspecialchars($r['description'])) . '</p>
              <button disabled>' . (($role === 'teacher') ? 'Review Missing' : 'Missed') . '</button>
            </div>';
          }
        } else {
          echo "<p>No missing assignments.</p>";
        }
        ?>
      </div>

      <!-- DONE -->
      <div id="done" class="tab-panel">
        <?php
        $rows = fetch_assignments($conn, $role, $user_id, 'done');
        if (!empty($rows)) {
          foreach ($rows as $r) {
            echo '
            <div class="assignment-card done">
              <h4>' . htmlspecialchars($r['title']) . '</h4>
              <p><strong>Subject:</strong> ' . htmlspecialchars($r['course_name']) . '</p>
              <p><strong>Completed:</strong> ' . htmlspecialchars($r['due_date']) . '</p>
              <p>' . nl2br(htmlspecialchars($r['description'])) . '</p>
              <button disabled>' . (($role === 'teacher') ? 'Reviewed' : 'Completed') . '</button>
            </div>';
          }
        } else {
          echo "<p>No completed assignments.</p>";
        }
        ?>
      </div>

    </div>
  </section>
</main>

<?php include('includes/footer.php'); ?>

<script>
  function showTab(tabId) {
    const tabs = document.querySelectorAll('.tab-panel');
    const buttons = document.querySelectorAll('.tab-btn');
    tabs.forEach(tab => tab.classList.remove('active'));
    buttons.forEach(btn => btn.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
  }
</script>
