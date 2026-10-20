<?php
require_once __DIR__ . '/includes/db.php';

$message = '';
$teacherExists = (bool) $pdo
    ->query("SELECT 1 FROM users WHERE role = 'teacher' LIMIT 1")
    ->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$teacherExists) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (
        $name === '' || 
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($password) < 12
    ) {
        $message = 'Enter a name, valid email, and password of at least 12 characters.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO users (name, email, password_hash, role)
            VALUES (?, ?, ?, 'teacher')"
        );

        try {
            $stmt->execute([
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            $message = 'Teacher account created! You can now log in';
            $teacherExists = true;
        } catch (PDOException $e) {
            $message = 'Could not create account. Check whether the email is already used.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create First Teacher</title>
    <link rel = "stylesheet" href="css/style.css">
</head>
<body>
    <h1>Create First Teacher Account</h1>

    <p>
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>

    <?php if (!$teacherExists): ?>
        <form method = "POST">
            <label for = "name">Teacher name</label>
            <input id = "name" name = "name" required>

            <br><br>

            <label for = "email">Email</label>
            <input id = "email" name = "email" type = "email" required>

            <br><br>

            <label for = "password">Password(at least 12 characters)</label>
            <input 
                id = "password"
                name = "password"
                type = "password"
                minlength = "12"
                required
            >

            <br><br>

            <button type = "submit">Create Teacher Account</button>
        </form>
    <?php else: ?>
        <p> A teacher account already exists. Setup is disabled.</p>
    <?php endif; ?>
</body>
</html>