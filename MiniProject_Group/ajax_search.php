<?php
require_once "config.php";

if (!isset($_SESSION["user_id"])) {
    exit;
}

$search = "%" . trim($_GET["q"] ?? "") . "%";

$stmt = $conn->prepare(
    "SELECT id, course_code, course_name
     FROM courses
     WHERE course_code LIKE ? OR course_name LIKE ?
     ORDER BY course_code"
);
$stmt->bind_param("ss", $search, $search);
$stmt->execute();

$result = $stmt->get_result();

while ($course = $result->fetch_assoc()) {
    echo '<div class="course-box">';
    echo '<b>' . htmlspecialchars($course["course_code"]) . '</b><br>';
    echo htmlspecialchars($course["course_name"]);
    echo '</div>';
}
?>