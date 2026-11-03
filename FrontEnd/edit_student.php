<?php
session_start();

if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] ?? '') !== 'teacher'
) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/includes/db.php';

$studentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$studentId || $studentId < 1) {
    header('Location: teacher_dashboard.php');
    exit;
}

$error = '';
$success = '';

// Generate a CSRF token if one doesn't exist.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Process the submitted form.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verify CSRF token.
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (
        !is_string($submittedToken) ||
        !hash_equals($_SESSION['csrf_token'], $submittedToken)
    ) {
        $error = 'Invalid request. Please try again.';
    } else {

        $newStudentId = trim($_POST['student_id'] ?? '');
        $newName = trim($_POST['name'] ?? '');

        // Continue with your existing validation and UPDATE logic here.

    // Check empty fields.
        if ($newStudentId === '' || $newName === '') {
            $error = 'Please fill in all fields.';
        } else {

            try {
                // Check whether another student already uses this ID.
                $stmt = $pdo->prepare(
                    "SELECT id FROM users
                     WHERE student_id = ?
                     AND id != ?
                     AND role = 'student'"
                );

                $stmt->execute([$newStudentId, $studentId]);

                if ($stmt->fetch()) {
                    $error = 'This student ID is already in use.';
                } else {

                    // Update student information.
                    $stmt = $pdo->prepare(
                        "UPDATE users
                         SET student_id = ?, name = ?
                         WHERE id = ? AND role = 'student'"
                    );

                    $stmt->execute([
                        $newStudentId,
                        $newName,
                        $studentId
                    ]);

                    $success = 'Student information updated successfully.';

                    // Update the displayed information.
                    $student['student_id'] = $newStudentId;
                    $student['name'] = $newName;
                }

            } catch (PDOException $e) {
                error_log($e->getMessage());
                $error = 'Could not update student information.';
            }
        }
    }
}


try {
    $stmt = $pdo->prepare(
        "SELECT id, student_id, name
         FROM users
         WHERE id = ? AND role = 'student'"
    );

    $stmt->execute([$studentId]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        header('Location: teacher_dashboard.php');
        exit;
    }

} catch (PDOException $e) {
    error_log($e->getMessage());
    die('Could not load student information.');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
</head>

<body>

    <h1>Edit Student Account</h1>

    <?php if ($error !== ''): ?>
    <p style="color: red;">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </p>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
    <p style="color: green;">
        <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
    </p>
    <?php endif; ?>

    <form method="POST">

        <input type="hidden" name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

        <label for="student_id">Student ID:</label>

        <input type="text" id="student_id" name="student_id"
            value="<?= htmlspecialchars($student['student_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

        <br><br>

        <label for="name">Student Name:</label>

        <input type="text" id="name" name="name" value="<?= htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8') ?>"
            required>

        <br><br>

        <button type="submit">Save Changes</button>

    </form>

    <p>
        <a href="teacher_dashboard.php">Back to Dashboard</a>
    </p>

</body>

</html>