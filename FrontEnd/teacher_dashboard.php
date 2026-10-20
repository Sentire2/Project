<?php
session_start();

if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] ?? '') !== 'teacher'
) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
</head>
<body>
    <h1>Teacher Dashboard</h1>

    <p>
        Welcome,
        <?= htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8') ?>!     
    </p>

    <form action = "logout.php" method = "POST">
        <button type = "submit">Log out</button>
    </form>
    
    <p>
        <a href = "create_student.php">Create Student Account</a>
    </p>
    
    <p>You are logged in successfully.</p>
</body>
</html>