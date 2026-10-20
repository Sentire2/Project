<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if(
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] ?? '') !== 'teacher'
) {
    header('Location: index.php');
    exit;
}

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $studentId = trim($_POST['student_id'] ?? '');
    $password = $_POST['password'] ?? '';

    if(
        $name === '' ||
        $studentId === '' ||
        strlen($password) < 12
    ) {
        $message = 'Enter a name, student ID, ans password of at least 12 characters.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO users
                (student_id, name, password_hash, role)
            VALUES (?, ?, ?, 'student')"
        );

        try {
            $stmt->execute([
                $studentId,
                $name,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            $message = 'Student account created successfully,';
            $success = true;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $message = 'This student ID may already exist.';
            } else {
                error_log($e->getMessage());
                $message = 'Could not create the student account. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student Account</title>
    <link rel =  "stylesheet" href = "css/style.css">
</head>
<body>
    <main class = "login-container">
        
        <h1>Create Student Account</h1>

        <p>
            <?=  htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>

        <form method = "POST" action = "create_student.php">
            <label for = "name">Student Name</label>
            <input
                id = "name"
                name = "name"
                type = "text"
                required
            >

            <label for = "password">Student ID</label>
            <input 
                id = "student_id"
                name = "student_id"
                type = "text"
                required
            >
            
            <label for = "password">Initial Password</label>
            <input
                id = "password"
                name = "password"
                type = "password"
                minlength = "12"
            >

            <button type = "submit">Create Student</button>
        </form>

        <p>
            <a href = "teacher_dashboard.php">Back to Dashboard</a>
        </p>
    </main>
    
</body>
</html>