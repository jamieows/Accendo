<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<main class="dashboard-content">
  <div class="welcome-card">
    <span class="wave">👤</span>
    <div class="welcome-text">Profile</div>
  </div>

  <section class="profile-section">
    <div class="profile-card">
      <div class="profile-header">
        <img src="assets/images/profile-placeholder.png" alt="Profile Picture" class="profile-pic">
        <div class="profile-basic">
          <h3>John Doe</h3>
          <p>BS Computer Science</p>
        </div>
      </div>

      <div class="profile-details">
        <p><strong>Email:</strong> johndoe@email.com</p>
        <p><strong>Student ID:</strong> 2025-1234</p>
        <p><strong>Year Level:</strong> 3rd Year</p>
        <p><strong>Address:</strong> Manila, Philippines</p>
      </div>

      <div class="profile-actions">
        <button>Edit Profile</button>
        <button class="logout-btn" onclick="window.location.href='index.php'">Logout</button>
      </div>
    </div>
  </section>
</main>

<?php include('includes/footer.php'); ?>
