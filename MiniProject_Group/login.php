<?php
require_once "config.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if ($username == "" || $password == "") {

        $message = "Please fill in all fields.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, username, password, role
             FROM users
             WHERE username = ?"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];

                header("Location: dashboard.php");
                exit;

            } else {
                $message = "Invalid username or password.";
            }

        } else {
            $message = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - PCRS</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <nav class="navbar navbar-dark bg-dark px-3">

        <span class="navbar-brand">
            Polytechnic Course Registration System
        </span>

    </nav>


    <div class="form-box">

        <h2>Student Login</h2>

        <p class="text-center text-muted">
            Login to access the registration system
        </p>

        <?php if ($message != ""): ?>

            <div class="alert alert-danger">
                <?= htmlspecialchars($message) ?>
            </div>

        <?php endif; ?>


        <form method="post"
              onsubmit="return checkForm()">

            <label class="form-label">
                Username
            </label>

            <input
                type="text"
                name="username"
                class="form-control mb-3"
                placeholder="Enter username"
                required>


            <label class="form-label">
                Password
            </label>

            <input
                type="password"
                name="password"
                class="form-control mb-3"
                placeholder="Enter password"
                required>


            <button
                type="submit"
                class="btn btn-primary">

                Login

            </button>

        </form>


        <p class="text-center mt-3 mb-0">

            Don't have an account?

            <a href="register.php">
                Register here
            </a>

        </p>

    </div>

</div>

<script src="script.js"></script>

</body>

</html>