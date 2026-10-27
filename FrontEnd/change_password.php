<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if (($_SESSION['role'] ?? '') !== 'student') {
    header('Location: teacher_dashboard.php');
    exit;
}

require_once __DIR__ . '/includes/db.php';

$message = '';

$stmt = $pdo->prepare(
    "SELECT must_change_password
     FROM users
     WHERE id = ? AND role = 'student'"
);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

if (!(bool) $user['must_change_password']) {
    header('Location: student_dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 12) {
        $message = 'Password must be at least 12 characters.';
    } elseif ($password !== $confirmPassword) {
        $message = 'Passwords do not match.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $update = $pdo->prepare(
            "UPDATE users
             SET password_hash = ?, must_change_password = FALSE
             WHERE id = ? AND role = 'student'"
        );

        $update->execute([$hash, $_SESSION['user_id']]);

        header('Location: student_dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Initial Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="login-container">
        <h1>Change Your Password</h1>

        <p>You must change your initial password before continuing.</p>

        <?php if ($message !== ''): ?>
        <p>
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
        <?php endif; ?>

        <form method="POST" action="change_password.php">
            <label for="password">New Password</label>
            <input id="password" name="password" type="password" minlength="12" required autocomplete="new-password">

            <label for="confirm_password">Confirm New Password</label>
            <input id="confirm_password" name="confirm_password" type="password" minlength="12" required
                autocomplete="new-password">

            <button type="submit">Change Password</button>
        </form>
    </main>
</body>

</html>