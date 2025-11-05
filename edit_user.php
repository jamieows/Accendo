<?php
session_start();

// ✅ Restrict access to admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: index.php");
  exit();
}

include('includes/db.php');

// ✅ Get user ID
if (!isset($_GET['id'])) {
  header("Location: manage_users.php");
  exit();
}

$user_id = (int)$_GET['id'];

// ✅ Fetch user data
$result = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
if (mysqli_num_rows($result) == 0) {
  echo "<p style='color:red; text-align:center;'>User not found.</p>";
  exit();
}
$user = mysqli_fetch_assoc($result);

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $role = mysqli_real_escape_string($conn, $_POST['role']);

  $update = "UPDATE users SET 
              full_name = '$full_name',
              username = '$username',
              password = '$password',
              role = '$role'
            WHERE user_id = $user_id";

  if (mysqli_query($conn, $update)) {
    header("Location: manage_users.php");
    exit();
  } else {
    echo "<p style='color:red; text-align:center;'>Error updating user: " . mysqli_error($conn) . "</p>";
  }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User - Admin Panel</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* --- Confirmation Modal Styles --- */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background: #fff;
      color: #000;
      padding: 25px;
      border-radius: 12px;
      width: 350px;
      text-align: center;
      box-shadow: 0 6px 15px rgba(0,0,0,0.3);
    }
    .modal-content h3 {
      margin-bottom: 15px;
      color: #00a7c6;
    }
    .modal-buttons {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 20px;
    }
    .modal-buttons button {
      padding: 8px 16px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }
    .btn-confirm {
      background-color: #00a7c6;
      color: #fff;
    }
    .btn-cancel {
      background-color: #ccc;
      color: #000;
    }
    .btn-confirm:hover {
      background-color: #4cc2e4;
    }
    .btn-cancel:hover {
      background-color: #aaa;
    }
  </style>
</head>
<body class="admin-panel">

  <?php include('admin_sidebar.php'); ?>
  <?php include('admin_header.php'); ?>

  <main class="admin-dashboard-content">
    <div class="card" style="max-width:600px; margin:auto;">
      <h2>✏️ Edit User</h2>

      <form id="editUserForm" action="" method="POST">
        <div class="form-group">
          <label>Full Name:</label>
          <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
        </div>

        <div class="form-group">
          <label>Username:</label>
          <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
          <label>Password:</label>
          <input type="text" name="password" value="<?php echo htmlspecialchars($user['password']); ?>" required>
        </div>

        <div class="form-group">
          <label>Role:</label>
          <select name="role" required>
            <option value="student" <?php if($user['role']=='student') echo 'selected'; ?>>Student</option>
            <option value="teacher" <?php if($user['role']=='teacher') echo 'selected'; ?>>Teacher</option>
            <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
          </select>
        </div>

        <div style="display:flex; justify-content:space-between; margin-top:20px;">
          <a href="manage_users.php" class="assign-btn" style="background:#555;">⬅ Back</a>
          <button type="button" class="assign-btn" id="saveChangesBtn">💾 Save Changes</button>
        </div>
      </form>
    </div>
  </main>

  <!-- Confirmation Modal -->
  <div id="confirmModal" class="modal">
    <div class="modal-content">
      <h3>Confirm Save</h3>
      <p>Are you sure you want to save these changes?</p>
      <div class="modal-buttons">
        <button class="btn-confirm" id="confirmYes">Yes, Save</button>
        <button class="btn-cancel" id="confirmNo">Cancel</button>
      </div>
    </div>
  </div>

  <script>
    // Sidebar active link
    document.querySelectorAll('.admin-sidebar a').forEach(link => {
      if (link.getAttribute('href') === '<?php echo $current_page; ?>') {
        link.classList.add('active');
      }
    });

    // Modal logic
    const saveBtn = document.getElementById('saveChangesBtn');
    const modal = document.getElementById('confirmModal');
    const confirmYes = document.getElementById('confirmYes');
    const confirmNo = document.getElementById('confirmNo');
    const form = document.getElementById('editUserForm');

    saveBtn.addEventListener('click', () => {
      modal.style.display = 'flex';
    });

    confirmNo.addEventListener('click', () => {
      modal.style.display = 'none';
    });

    confirmYes.addEventListener('click', () => {
      modal.style.display = 'none';
      form.submit();
    });

    window.onclick = function(event) {
      if (event.target === modal) modal.style.display = 'none';
    };
  </script>
</body>
</html>
