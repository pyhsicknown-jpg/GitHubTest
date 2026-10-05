```php
<?php
require_once "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];
    $role = $_POST["role"];

    if ($username == "" || $password == "" || $confirm == "" || $role == "") {
        $message = "Please fill in all fields.";
    } elseif ($password != $confirm) {
        $message = "Passwords do not match.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Username already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (username, password, role) VALUES (?, ?, ?)"
            );
            $stmt->bind_param("sss", $username, $hashed, $role);

            if ($stmt->execute()) {
                $message = "Registration successful. You can now login.";
            } else {
                $message = "Registration failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register - PCRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container mt-4">

    <nav class="navbar navbar-dark bg-dark px-3">
        <span class="navbar-brand">
            Polytechnic Course Registration System (PCRS)
        </span>
    </nav>

    <div class="form-box mt-4">

        <h2>Register</h2>

        <?php if ($message != ""): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="post" onsubmit="return checkForm()">

            <input
                type="text"
                name="username"
                class="form-control mb-2"
                placeholder="Username"
                required
            >

            <input
                type="password"
                name="password"
                class="form-control mb-2"
                placeholder="Password"
                required
            >

            <input
                type="password"
                name="confirm"
                class="form-control mb-2"
                placeholder="Confirm Password"
                required
            >

            <!-- Role Selection -->
            <select name="role" class="form-control mb-3" required>
                <option value="">Select Role</option>
                <option value="Student">Student</option>
                <option value="Admin">Admin</option>
            </select>

            <button class="btn btn-primary">
                Register
            </button>

            <a href="login.php" class="btn btn-link">
                Login
            </a>

        </form>

    </div>
</div>

<script src="script.js"></script>

</body>
</html>
```

