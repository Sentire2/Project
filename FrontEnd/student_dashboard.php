<?php
session_start();

if(
    !isset($_SESSION['user_id'])||
    ($_SESSION['role'] ?? '') !== 'student'
) {
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel = "stylesheet" href = "css/style.css">
</head>
<body>
    <main class = "login-container">
        <h1>Student Dashboard</h1>

        <p>
            Welcome,
            <?=  htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8') ?>!
        </p>

        <p> You are logged in successfully.</p>

        <p>Your exams will appear here when they are assigned.</p>

        <form action = "logout.php" method = "POST">
            <button type = "submit">Log out</button>
        </form>
    </main>
    
</body>
</html>