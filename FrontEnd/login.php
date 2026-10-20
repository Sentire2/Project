<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$stmt = $pdo->prepare(
    "SELECT id, name, email, student_id, password_hash, role
    From users
    WHERE email = ? OR student_id = ?
    LIMIT 1"
);

$stmt->execute([$username, $username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    exit('Invalid username or password. <a href="index.php">Try again</a>');
}

session_regenerate_id(true);

$_SESSION['user_id'] = $user['id'];
$_SESSION['name'] = $user['name'];
$_SESSION['role'] = $user['role'];

if ($user['role'] === 'teacher') {
    header('Location: teacher_dashboard.php');
} else {
    header('Location: student_dashboard.php');
}

exit;