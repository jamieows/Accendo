<?php
session_start();

// ✅ Only allow admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: index.php");
  exit();
}

include('includes/db.php');

// ✅ Handle delete request
if (isset($_GET['delete_id'])) {
  $user_id = intval($_GET['delete_id']);
  $delete = mysqli_query($conn, "DELETE FROM users WHERE user_id = $user_id");
  if ($delete) {
    $message = "🗑 User deleted successfully.";
  } else {
    $message = "❌ Error deleting user: " . mysqli_error($conn);
  }
}

// ✅ Handle add user
if (isset($_POST['add_user'])) {
  $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $role = mysqli_real_escape_string($conn, $_POST['role']);

  $insert = mysqli_query($conn, "INSERT INTO users (full_name, username, password, role)
                                 VALUES ('$full_name', '$username', '$password', '$role')");

  if ($insert) {
    $message = "✅ New user added successfully!";
  } else {
    $message = "❌ Error adding user: " . mysqli_error($conn);
  }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users - Admin Panel</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); justify-content: center; align-items: center; }
    .modal-content { background: #fff; color: #000; padding: 25px; border-radius: 12px; width: 350px; text-align: center; box-shadow: 0 6px 15px rgba(0,0,0,0.3); }
    .modal-buttons { display: flex; justify-content: center; gap: 15px; margin-top: 20px; }
    .modal-buttons button { padding: 8px 16px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
    .btn-confirm { background-color: #ff4f4f; color: #fff; }
    .btn-cancel { background-color: #ccc; color: #000; }
    .btn-confirm:hover { background-color: #d43d3d; }
    .btn-cancel:hover { background-color: #aaa; }
    .alert-box { background-color: #00a7c6; color: #fff; padding: 10px 15px; border-radius: 6px; margin-bottom: 15px; text-align: center; font-weight: 600; }
    .add-user-form { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
    .add-user-form input, .add-user-form select { padding: 8px; border-radius: 6px; border: 1px solid #ccc; }
  </style>
</head>
<body class="admin-panel">

  <?php include('admin_sidebar.php'); ?>
  <?php include('admin_header.php'); ?>

  <main class="admin-dashboard-content">
    <div class="card">
      <h2>👥 Manage Users</h2>

      <?php if (!empty($message)) echo "<div class='alert-box'>$message</div>"; ?>

      <!-- Add User Form -->
      <form action="" method="POST" class="add-user-form">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="password" placeholder="Password" required>
        <select name="role" required>
          <option value="">Select Role</option>
          <option value="student">Student</option>
          <option value="teacher">Teacher</option>
          <option value="admin">Admin</option>
        </select>
        <button type="submit" name="add_user" class="assign-btn">➕ Add User</button>
      </form>

      <table class="admin-table">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT * FROM users ORDER BY role ASC, full_name ASC";
          $result = mysqli_query($conn, $sql);

          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>
                      <td>{$row['user_id']}</td>
                      <td>" . htmlspecialchars($row['full_name']) . "</td>
                      <td>" . htmlspecialchars($row['username']) . "</td>
                      <td style='text-transform:capitalize;'>" . htmlspecialchars($row['role']) . "</td>
                      <td>
                        <a href='edit_user.php?id={$row['user_id']}' class='assign-btn' style='background:#00a7c6;'>Edit</a>
                        <button type='button' class='assign-btn delete-btn' data-id='{$row['user_id']}' style='background:#ff4f4f;'>Delete</button>
                      </td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='5'>No users found.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Delete Confirmation Modal -->
  <div id="confirmDeleteModal" class="modal">
    <div class="modal-content">
      <h3>Confirm Deletion</h3>
      <p>Are you sure you want to delete this user?</p>
      <div class="modal-buttons">
        <button class="btn-confirm" id="deleteConfirmYes">Yes, Delete</button>
        <button class="btn-cancel" id="deleteConfirmNo">Cancel</button>
      </div>
    </div>
  </div>

  <script>
    // Sidebar highlight
    document.querySelectorAll('.admin-sidebar a').forEach(link => {
      if (link.getAttribute('href') === '<?php echo $current_page; ?>') link.classList.add('active');
    });

    // Delete confirmation logic
    let selectedUserId = null;
    const modal = document.getElementById('confirmDeleteModal');
    const confirmYes = document.getElementById('deleteConfirmYes');
    const confirmNo = document.getElementById('deleteConfirmNo');

    document.querySelectorAll('.delete-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        selectedUserId = btn.getAttribute('data-id');
        modal.style.display = 'flex';
      });
    });

    confirmNo.onclick = () => modal.style.display = 'none';
    confirmYes.onclick = () => {
      modal.style.display = 'none';
      if (selectedUserId) window.location.href = 'manage_users.php?delete_id=' + selectedUserId;
    };
    window.onclick = e => { if (e.target === modal) modal.style.display = 'none'; };
  </script>

</body>
</html>
