<?php
require_once "config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polytechnic Course Registration System (PCRS)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-4">
    <nav class="navbar navbar-dark bg-dark px-3">
        
<span class="navbar-brand d-flex align-items-center gap-3">
    <img src="https://thumbs.dreamstime.com/b/hand-book-logo-illustration-art-background-43965136.jpg"
         alt="PCRS Logo"
         width="50"
         height="50"
         style="object-fit: contain;">
    Polytechnic Course Registration System (PCRS)
</span>

        <?php if (isset($_SESSION["user_id"])): ?>
            <div>
                <a href="dashboard.php">Dashboard</a>

<?php if ($_SESSION["role"] == "Admin"): ?>
    <a href="admin.php">Admin</a>
<?php endif; ?>

<a href="courses.php">Courses</a>
<a href="logout.php">Logout</a>
            </div>
        <?php endif; ?>
    </nav>
