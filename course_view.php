<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include('includes/db.php');
include('includes/header.php');
include('includes/sidebar.php');

if (!isset($_GET['course_id'])) {
  echo "<main class='dashboard-content'><p>No course selected.</p></main>";
  include('includes/footer.php');
  exit();
}

$course_id = intval($_GET['course_id']);

// Fetch course info
$course_query = mysqli_query($conn, "SELECT * FROM courses WHERE course_id = $course_id");
$course = mysqli_fetch_assoc($course_query);

// Fetch course materials (attachments)
$materials_query = mysqli_query($conn, "
  SELECT * FROM materials WHERE course_id = $course_id
");

// If you don’t have a materials table yet, here’s the structure:
/*
CREATE TABLE materials (
  material_id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT,
  file_name VARCHAR(255),
  file_path VARCHAR(255),
  upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (course_id) REFERENCES courses(course_id)
);
*/
?>

<main class="dashboard-content">
  <h1><?php echo htmlspecialchars($course['course_name']); ?></h1>
  <h3>Attachments:</h3>
  <div class="course-grid">
    <?php
    if ($materials_query && mysqli_num_rows($materials_query) > 0) {
      while ($mat = mysqli_fetch_assoc($materials_query)) {
        $ext = pathinfo($mat['file_name'], PATHINFO_EXTENSION);
        $icon = ($ext === 'pdf') ? '📕' : (($ext === 'docx' || $ext === 'docs') ? '📘' : '📄');
        echo "
        <div class='course-box'>
          <a href='" . htmlspecialchars($mat['file_path']) . "' target='_blank'>
            <h4>$icon " . htmlspecialchars($mat['file_name']) . "</h4>
          </a>
        </div>";
      }
    } else {
      echo "<p>No materials uploaded for this course yet.</p>";
    }
    ?>
  </div>
</main>

<?php include('includes/footer.php'); ?>
