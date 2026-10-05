<?php
require_once "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_course"])) {
    if ($_SESSION["role"] != "Admin") {
        die("Access denied.");
    }

    $code = trim($_POST["course_code"]);
    $name = trim($_POST["course_name"]);

    if ($code == "" || $name == "") {
        $message = "Please fill in both course fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO courses (course_code, course_name) VALUES (?, ?)");
        $stmt->bind_param("ss", $code, $name);

        if ($stmt->execute()) {
            $message = "Course added successfully.";
        } else {
            $message = "Course code already exists.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register_course"])) {

    $course_id = intval($_POST["course_id"]);
    $user_id = $_SESSION["user_id"];

    // Check if student already registered
    $check = $conn->prepare(
        "SELECT id FROM registrations WHERE user_id = ? AND course_id = ?"
    );
    $check->bind_param("ii", $user_id, $course_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {

        $message = "You already registered for this course.";

    } else {

        // Register the course
        $stmt = $conn->prepare(
            "INSERT INTO registrations (user_id, course_id) VALUES (?, ?)"
        );
        $stmt->bind_param("ii", $user_id, $course_id);

        if ($stmt->execute()) {
            $message = "Course registered successfully.";
        } else {
            $message = "Registration failed.";
        }
    }
}

require "header.php";
?>

<?php if ($message != ""): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if ($_SESSION["role"] == "Admin"): ?>
    <div class="form-box">
        <h2>Add Course</h2>

        <form method="post" onsubmit="return checkForm()">
            <input type="text" name="course_code" class="form-control mb-2" placeholder="Course Code" required>
            <input type="text" name="course_name" class="form-control mb-2" placeholder="Course Name" required>
            <button name="add_course" class="btn btn-success">Add</button>
        </form>
    </div>

    <hr>
<?php endif; ?>

<h2>Course List</h2>

<input type="text" id="search" class="form-control mb-3"
       placeholder="Search course..." onkeyup="searchCourses()">

<div id="courseList">
    <?php
    $result = $conn->query("SELECT * FROM courses ORDER BY id DESC");

    while ($course = $result->fetch_assoc()):
    ?>
        <div class="course-box">
            <b><?= htmlspecialchars($course["course_code"]) ?></b><br>
            <?= htmlspecialchars($course["course_name"]) ?>

            <?php if ($_SESSION["role"] == "Admin"): ?>
                <div class="mt-2">
                    <a href="edit_course.php?id=<?= $course["id"] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_course.php?id=<?= $course["id"] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this course?')">Delete</a>
                </div>
            <?php else: ?>
                <form method="post" class="mt-2">
                    <input type="hidden" name="course_id" value="<?= $course["id"] ?>">
                    <button name="register_course" class="btn btn-primary btn-sm">Register</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>

<?php require "footer.php"; ?>
