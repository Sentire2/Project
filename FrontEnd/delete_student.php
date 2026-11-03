
<?php
session_start();

// Only teachers can access this page.
if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] ?? '') !== 'teacher'
) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/includes/db.php';

// Verify CSRF token.
$submittedToken = $_POST['csrf_token'] ?? '';

if (
    !is_string($submittedToken) ||
    !isset($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $submittedToken)
) {
    die('Invalid request. Please try again.');
}

// Get student database ID.
$studentId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$studentId || $studentId < 1) {
    header('Location: teacher_dashboard.php');
    exit;
}

try {
    // Delete only student accounts.
    $stmt = $pdo->prepare(
        "DELETE FROM users
         WHERE id = ? AND role = 'student'"
    );

    $stmt->execute([$studentId]);

    if ($stmt->rowCount() === 0) {
        die('Student account not found.');
    }

    header('Location: teacher_dashboard.php');
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());
    die('Could not delete student account.');
}
