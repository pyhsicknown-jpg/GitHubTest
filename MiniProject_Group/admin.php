<?php
require_once "config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "Admin") {
    header("Location: dashboard.php");
    exit;
}

require "header.php";
?>

<div class="container mt-4">
    <h2>Admin Page</h2>

    <p>Welcome, Admin!</p>

    <hr>

    <h4>Admin Functions</h4>

    <a href="courses.php" class="btn btn-primary">
        Manage Courses
    </a>

    <a href="dashboard.php" class="btn btn-secondary">
        Dashboard
    </a>
</div>

<?php require "footer.php"; ?>