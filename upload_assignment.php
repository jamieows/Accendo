<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
  header("Location: index.php");
  exit();
}

$title = mysqli_real_escape_string($conn, $_POST['title']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$course_id = intval($_POST['course_id']);
$due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
$teacher_id = $_SESSION['user_id'];

// Insert the new assignment
$sql = "INSERT INTO assignments (title, description, due_date, status, course_id, user_id)
        VALUES ('$title', '$description', '$due_date', 'assigned', $course_id, $teacher_id)";

if (mysqli_query($conn, $sql)) {
  $_SESSION['message'] = "✅ Assignment uploaded successfully!";
} else {
  $_SESSION['message'] = "❌ Error uploading assignment: " . mysqli_error($conn);
}

header("Location: assignments.php");
exit();
?>
