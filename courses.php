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
    <span class="wave">📚</span>
    <div class="welcome-text">My Courses</div>
  </div>

  <section class="dashboard-grid single-column">
    <div class="card courses-card">
      <h3>Enrolled Subjects</h3>
      <div class="course-grid">
        <?php
        // ✅ Fetch courses dynamically from the database
        $sql = "SELECT * FROM courses ORDER BY course_name ASC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            // Lowercase first word for color class (e.g. math, science)
            $courseClass = strtolower(explode(' ', $row['course_name'])[0]);

            echo '
            <div class="course-box ' . htmlspecialchars($courseClass) . '">
              <h4>' . htmlspecialchars($row['course_name']) . '</h4>
              <p>' . htmlspecialchars($row['description']) . '</p>
              <form action="course_view.php" method="GET">
                <input type="hidden" name="course_id" value="' . $row['course_id'] . '">
                <button type="submit">Continue</button>
              </form>
            </div>';
          }
        } else {
          echo "<p>No courses available.</p>";
        }
        ?>
      </div>
    </div>
  </section>
</main>

<?php include('includes/footer.php'); ?>
