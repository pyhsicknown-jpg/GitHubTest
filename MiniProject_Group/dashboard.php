<?php
require_once "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require "header.php";
?>

<div class="form-box">
    <h2>Dashboard</h2>

    <p>Welcome, <b><?= htmlspecialchars($_SESSION["username"]) ?></b>.</p>
    <p>Role: <b><?= htmlspecialchars($_SESSION["role"]) ?></b></p>

    <a href="courses.php" class="btn btn-primary">View Courses</a>

    <?php if ($_SESSION["role"] == "Admin"): ?>
        <a href="courses.php" class="btn btn-success">Manage Courses</a>
    <?php endif; ?>
</div>


<?php if ($_SESSION["role"] == "Student"): ?>

<hr>

<div class="form-box">
    <h2>My Registered Courses</h2>

    <?php
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare(
        "SELECT courses.course_code, courses.course_name
         FROM registrations
         INNER JOIN courses
         ON registrations.course_id = courses.id
         WHERE registrations.user_id = ?"
    );

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0):
    ?>

        <?php while ($course = $result->fetch_assoc()): ?>

            <div class="course-box">
                <b><?= htmlspecialchars($course["course_code"]) ?></b><br>
                <?= htmlspecialchars($course["course_name"]) ?>
            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <p>You have not registered for any courses yet.</p>

    <?php endif; ?>
</div>

<?php endif; ?>


<?php require "footer.php"; ?>