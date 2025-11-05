<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

include('includes/db.php');
include('includes/header.php');
include('includes/sidebar.php');

$user_id = $_SESSION['user_id'];

// Fetch user info
$user_query = mysqli_query($conn, "SELECT full_name FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($user_query);

// Fetch course progress (for donut charts)
$progress_data = [];
$progress_query = mysqli_query($conn, "
  SELECT c.course_name, p.progress_percent
  FROM progress p
  JOIN courses c ON p.course_id = c.course_id
  WHERE p.user_id = $user_id
");
while ($row = mysqli_fetch_assoc($progress_query)) {
  $progress_data[] = $row;
}

// Fetch weekly progress (for bar chart)
$weekly_labels = [];
$weekly_values = [];
$weekly_query = mysqli_query($conn, "
  SELECT week_start, week_end, progress_percent 
  FROM weekly_progress 
  WHERE user_id = $user_id
  ORDER BY week_start ASC
");
while ($row = mysqli_fetch_assoc($weekly_query)) {
  $weekly_labels[] = date('M d', strtotime($row['week_start'])) . ' - ' . date('M d', strtotime($row['week_end']));
  $weekly_values[] = $row['progress_percent'];
}

// Fetch assignments
$assignments = mysqli_query($conn, "
  SELECT a.title, a.description, a.due_date, c.course_name
  FROM assignments a
  JOIN courses c ON a.course_id = c.course_id
  WHERE a.user_id = $user_id
  ORDER BY a.due_date ASC
  LIMIT 3
");

// Fetch quizzes/exams
$quizzes = mysqli_query($conn, "
  SELECT q.quiz_title, q.due_date, q.status, c.course_name
  FROM quizzes q
  JOIN courses c ON q.course_id = c.course_id
  WHERE q.status IN ('due','upcoming')
  ORDER BY q.due_date ASC
  LIMIT 3
");

// Combine all events for the calendar
$calendar_events = [];

// Assignments
$a_query = mysqli_query($conn, "
  SELECT title AS event_title, due_date AS event_date, 'Assignment' AS type 
  FROM assignments WHERE user_id = $user_id
");
while ($a = mysqli_fetch_assoc($a_query)) {
  $calendar_events[] = [
    'title' => "📝 " . $a['event_title'],
    'start' => $a['event_date'],
    'color' => '#4cc2e4'
  ];
}

// Quizzes
$q_query = mysqli_query($conn, "
  SELECT quiz_title AS event_title, due_date AS event_date, 'Quiz' AS type 
  FROM quizzes q
  JOIN courses c ON q.course_id = c.course_id
");
while ($q = mysqli_fetch_assoc($q_query)) {
  $calendar_events[] = [
    'title' => "🧮 " . $q['event_title'],
    'start' => $q['event_date'],
    'color' => '#00a7c6'
  ];
}

// Optional general events
$e_query = mysqli_query($conn, "
  SELECT title AS event_title, event_date, category
  FROM calendar_events
");
while ($e = mysqli_fetch_assoc($e_query)) {
  $calendar_events[] = [
    'title' => "📅 " . $e['event_title'],
    'start' => $e['event_date'],
    'color' => '#6c63ff'
  ];
}
?>

<!-- Include FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<main class="dashboard-content">
  <div class="welcome-card">
    <span class="wave">👋</span>
    <div class="welcome-text">
      Welcome, <?php echo htmlspecialchars($user['full_name'] ?? 'Student'); ?>!
    </div>
  </div>

  <div class="dashboard-grid">
    <!-- LEFT COLUMN: My Visit -->
    <section class="donut-column">
      <div class="card visit-card">
        <div class="card-title">
          <span>My Visit</span>
          <span class="month"><?php echo date('F'); ?></span>
        </div>

        <div class="donut-grid">
          <?php
          if (!empty($progress_data)) {
            foreach ($progress_data as $index => $p) {
              $chartId = "chart" . ($index + 1);
              echo '
              <div class="donut-item">
                <canvas id="' . $chartId . '"></canvas>
                <p><strong>' . htmlspecialchars($p['course_name']) . '</strong><br>
                <span>' . htmlspecialchars($p['progress_percent']) . '%</span></p>
              </div>';
            }
          } else {
            echo "<p>No progress data found.</p>";
          }
          ?>
        </div>
      </div>
    </section>

    <!-- RIGHT COLUMN: Calendar, Weekly Progress, Assignments, Quizzes -->
    <section class="right-stack">

      <!-- ✅ Interactive Calendar -->
      <div class="card calendar-card">
        <h3>Calendar</h3>
        <div id="calendar" style="background:#fff;color:#000;border-radius:10px;padding:10px;"></div>
      </div>

      <!-- ✅ Weekly Progress -->
      <div class="card progress-card">
        <h3>Weekly Progress</h3>
        <canvas id="progressChart"></canvas>
      </div>

      <!-- ✅ Assignments -->
      <div class="card announcement-card">
        <h3 class="ann-title">UPCOMING ASSIGNMENTS</h3>
        <table class="ann-table">
          <thead><tr><th>Due Date</th><th>Task</th></tr></thead>
          <tbody>
            <?php
            if ($assignments && mysqli_num_rows($assignments) > 0) {
              while ($row = mysqli_fetch_assoc($assignments)) {
                echo "<tr>
                        <td>" . date('M d, Y', strtotime($row['due_date'])) . "</td>
                        <td>
                          <strong>" . htmlspecialchars($row['title']) . "</strong><br>
                          <small>" . htmlspecialchars($row['description']) . "</small><br>
                          <em>Subject: " . htmlspecialchars($row['course_name']) . "</em>
                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='2'>No upcoming assignments.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>

      <!-- ✅ Quizzes & Exams -->
      <div class="card announcement-card">
        <h3 class="ann-title">UPCOMING QUIZZES & EXAMS</h3>
        <table class="ann-table">
          <thead><tr><th>Date</th><th>Quiz / Exam</th></tr></thead>
          <tbody>
            <?php
            if ($quizzes && mysqli_num_rows($quizzes) > 0) {
              while ($row = mysqli_fetch_assoc($quizzes)) {
                echo "<tr>
                        <td>" . date('M d, Y', strtotime($row['due_date'])) . "</td>
                        <td>
                          <strong>" . htmlspecialchars($row['quiz_title']) . "</strong><br>
                          <em>Subject: " . htmlspecialchars($row['course_name']) . "</em><br>
                          <small>Status: " . htmlspecialchars(ucfirst($row['status'])) . "</small>
                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='2'>No upcoming quizzes or exams.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>

    </section>
  </div>
</main>

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Donut Charts
  <?php
  foreach ($progress_data as $index => $p) {
    $chartId = "chart" . ($index + 1);
    $percent = $p['progress_percent'];
    echo "
      new Chart(document.getElementById('$chartId'), {
        type: 'doughnut',
        data: { datasets: [{ data: [$percent, " . (100 - $percent) . "],
        backgroundColor: ['#00a7c6', '#1a2235'] }] },
        options: { cutout: '75%', plugins: { legend: { display: false } } }
      });
    ";
  }
  ?>

  // Weekly Progress Chart
  const weekLabels = <?php echo json_encode($weekly_labels); ?>;
  const weekData = <?php echo json_encode($weekly_values); ?>;

  new Chart(document.getElementById("progressChart"), {
    type: "bar",
    data: {
      labels: weekLabels,
      datasets: [{
        label: "Weekly Progress (%)",
        data: weekData,
        backgroundColor: "#00a7c6",
        borderRadius: 8
      }]
    },
    options: {
      scales: { y: { beginAtZero: true, max: 100 } },
      plugins: { legend: { display: false } }
    }
  });

  // ✅ FullCalendar Setup
  document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      height: 420,
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: ''
      },
      events: <?php echo json_encode($calendar_events); ?>,
      eventDisplay: 'block',
      eventTextColor: '#fff',
      eventClick: function(info) {
        alert("Event: " + info.event.title + "\nDate: " + info.event.start.toDateString());
      }
    });
    calendar.render();
  });
</script>
  