<?php
require_once "config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "Admin") {
    header("Location: login.php");
    exit;
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: courses.php");
exit;
?>