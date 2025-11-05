<?php
session_start();

// ✅ Restrict access to admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: index.php");
  exit();
}

include('includes/db.php');

$current_page = basename($_SERVER['PHP_SELF']);

// ✅ Handle Add Course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
  $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $teacher_id = intval($_POST['teacher_id']);

  if (!empty($course_name)) {
    $sql = "INSERT INTO courses (course_name, description, teacher_id)
            VALUES ('$course_name', '$description', '$teacher_id')";
    if (mysqli_query($conn, $sql)) {
      $message = "✅ Course added successfully!";
    } else {
      $message = "❌ Error adding course: " . mysqli_error($conn);
    }
  } else {
    $message = "⚠️ Please enter a course name.";
  }
}

// ✅ Handle Delete Course
if (isset($_GET['delete'])) {
  $course_id = intval($_GET['delete']);
  $delete_sql = "DELETE FROM courses WHERE course_id = $course_id";
  if (mysqli_query($conn, $delete_sql)) {
    $message = "🗑️ Course deleted successfully.";
  } else {
    $message = "❌ Error deleting course: " . mysqli_error($conn);
  }
}

// ✅ Handle Teacher Assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_teacher'])) {
  $course_id = intval($_POST['course_id']);
  $teacher_id = intval($_POST['teacher_id']);
  $update_sql = "UPDATE courses SET teacher_id = $teacher_id WHERE course_id = $course_id";
  if (mysqli_query($conn, $update_sql)) {
    $message = "👨‍🏫 Teacher updated successfully.";
  } else {
    $message = "❌ Error updating teacher: " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Manage Courses</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="admin-panel">

  <?php include('admin_sidebar.php'); ?>
  <?php include('admin_header.php'); ?>

  <main class="admin-dashboard-content">
    <div class="card">
      <h2>📘 Manage Courses</h2>

      <?php if (!empty($message)) echo "<p style='color:#00a7c6; font-weight:bold;'>$message</p>"; ?>

      <!-- ✅ Add New Course Form -->
      <form action="" method="POST" class="add-course-form" style="margin-bottom:25px;">
        <h3 style="color:#00a7c6;">➕ Add New Course</h3>
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr auto; gap:10px; align-items:center;">
          <input type="text" name="course_name" placeholder="Course Name" required
            style="padding:10px; border-radius:8px; border:1px solid #ccc;">
          <input type="text" name="description" placeholder="Description"
            style="padding:10px; border-radius:8px; border:1px solid #ccc;">
          <select name="teacher_id" required style="padding:10px; border-radius:8px;">
            <option value="">Assign Teacher</option>
            <?php
            $teachers = mysqli_query($conn, "SELECT user_id, full_name FROM users WHERE role='teacher' ORDER BY full_name ASC");
            while ($t = mysqli_fetch_assoc($teachers)) {
              echo "<option value='{$t['user_id']}'>" . htmlspecialchars($t['full_name']) . "</option>";
            }
            ?>
          </select>
          <button type="submit" name="add_course" class="assign-btn">Add Course</button>
        </div>
      </form>

      <!-- ✅ Course Table -->
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Course Name</th>
            <th>Description</th>
            <th>Teacher</th>
            <th>Change Teacher</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT c.course_id, c.course_name, c.description, u.full_name AS teacher_name, c.teacher_id
                  FROM courses c
                  LEFT JOIN users u ON c.teacher_id = u.user_id
                  ORDER BY c.course_name ASC";
          $result = mysqli_query($conn, $sql);

          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>
                      <td>{$row['course_id']}</td>
                      <td>" . htmlspecialchars($row['course_name']) . "</td>
                      <td>" . htmlspecialchars($row['description']) . "</td>
                      <td>" . htmlspecialchars($row['teacher_name'] ?? '— Unassigned —') . "</td>
                      <td>
                        <form method='POST' class='assign-form'>
                          <input type='hidden' name='course_id' value='{$row['course_id']}'>
                          <select name='teacher_id' required class='assign-select'>
                            <option value=''>Select Teacher</option>";
              $teacher_query = mysqli_query($conn, "SELECT user_id, full_name FROM users WHERE role='teacher' ORDER BY full_name ASC");
              while ($t = mysqli_fetch_assoc($teacher_query)) {
                $selected = ($t['user_id'] == $row['teacher_id']) ? 'selected' : '';
                echo "<option value='{$t['user_id']}' $selected>" . htmlspecialchars($t['full_name']) . "</option>";
              }
              echo "</select>
                          <button type='submit' name='update_teacher' class='assign-btn'>Update</button>
                        </form>
                      </td>
                      <td>
                        <button class='assign-btn delete-btn' data-id='{$row['course_id']}' style='background:#ff4f4f;'>Delete</button>
                      </td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='6'>No courses found.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Delete Confirmation Modal -->
  <div id="confirmDeleteModal" class="modal" style="display:none;">
    <div class="modal-content">
      <h3>Confirm Deletion</h3>
      <p>Are you sure you want to delete this course? This cannot be undone.</p>
      <div class="modal-buttons">
        <button class="btn-confirm" id="deleteConfirmYes">Yes, Delete</button>
        <button class="btn-cancel" id="deleteConfirmNo">Cancel</button>
      </div>
    </div>
  </div>

  <script>
    // Sidebar Active
    document.querySelectorAll('.admin-sidebar a').forEach(link => {
      if (link.getAttribute('href') === '<?php echo $current_page; ?>') {
        link.classList.add('active');
      }
    });

    // Delete Confirmation Modal
    let selectedCourseId = null;
    const modal = document.getElementById('confirmDeleteModal');
    const confirmYes = document.getElementById('deleteConfirmYes');
    const confirmNo = document.getElementById('deleteConfirmNo');

    document.querySelectorAll('.delete-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        selectedCourseId = btn.getAttribute('data-id');
        modal.style.display = 'flex';
      });
    });

    confirmNo.addEventListener('click', () => {
      modal.style.display = 'none';
    });

    confirmYes.addEventListener('click', () => {
      if (selectedCourseId) {
        window.location.href = 'manage_courses.php?delete=' + selectedCourseId;
      }
    });

    window.onclick = (e) => {
      if (e.target === modal) modal.style.display = 'none';
    };
  </script>
</body>
</html>
