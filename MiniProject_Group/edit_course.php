<?php
require_once "config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "Admin") {
    header("Location: login.php");
    exit;
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

if (!$course) {
    die("Course not found.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = trim($_POST["course_code"]);
    $name = trim($_POST["course_name"]);

    if ($code == "" || $name == "") {
        $message = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE courses SET course_code = ?, course_name = ? WHERE id = ?"
        );
        $stmt->bind_param("ssi", $code, $name, $id);

        if ($stmt->execute()) {
            header("Location: courses.php");
            exit;
        } else {
            $message = "Update failed. Course code may already exist.";
        }
    }
}

require "header.php";
?>

<div class="form-box">
    <h2>Edit Course</h2>

    <?php if ($message != ""): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" onsubmit="return checkForm()">
        <input type="text" name="course_code" class="form-control mb-2"
               value="<?= htmlspecialchars($course["course_code"]) ?>" required>

        <input type="text" name="course_name" class="form-control mb-2"
               value="<?= htmlspecialchars($course["course_name"]) ?>" required>

        <button class="btn btn-primary">Update</button>
        <a href="courses.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require "footer.php"; ?>
