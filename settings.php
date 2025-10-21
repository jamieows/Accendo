<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<main class="dashboard-content">
  <div class="welcome-card">
    <span class="wave">⚙️</span>
    <div class="welcome-text">Settings</div>
  </div>

  <section class="settings-section">
    <div class="settings-card">
      <h3>Accessibility Features</h3>

      <div class="setting-item">
        <label for="speech">Speech Recognition</label>
        <label class="switch">
          <input type="checkbox" id="speech">
          <span class="slider"></span>
        </label>
      </div>

      <div class="setting-item">
        <label for="voice">Voice Output</label>
        <label class="switch">
          <input type="checkbox" id="voice" checked>
          <span class="slider"></span>
        </label>
      </div>

      <div class="setting-item">
        <label for="haptic">Haptic Feedback</label>
        <label class="switch">
          <input type="checkbox" id="haptic">
          <span class="slider"></span>
        </label>
      </div>
    </div>

    <div class="settings-card">
      <h3>Display & Language</h3>

      <div class="setting-item">
        <label for="mode">Display Mode</label>
        <select id="mode">
          <option>Dark</option>
          <option>Light</option>
        </select>
      </div>

      <div class="setting-item">
        <label for="language">Language</label>
        <select id="language">
          <option>English</option>
          <option>Filipino</option>
        </select>
      </div>
    </div>

    <div class="settings-actions">
      <button type="submit">Save Settings</button>
    </div>
  </section>
</main>

<?php include('includes/footer.php'); ?>
