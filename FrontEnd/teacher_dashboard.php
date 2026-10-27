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


$students = [];
$error = '';
$search = trim($_GET['search'] ?? '');

try {
    if ($search !== '') {
        $sql = "SELECT id, student_id, name, created_at
                FROM users
                WHERE role = 'student'
                  AND (student_id LIKE ? OR name LIKE ?)
                ORDER BY created_at DESC";

        $stmt = $pdo->prepare($sql);
        $term = '%' . $search . '%';
        $stmt->execute([$term, $term]);
    } else {
        $sql = "SELECT id, student_id, name, created_at
                FROM users
                WHERE role = 'student'
                ORDER BY created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $error = 'Could not load student accounts.';
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
        <?= htmlspecialchars(
            $_SESSION['name'] ?? 'Teacher',
            ENT_QUOTES,
            'UTF-8'
        ) ?>!
    </p>

    <form action="logout.php" method="POST">
        <button type="submit">Log out</button>
    </form>

    <hr>

    <h2>Student Account Management</h2>


    <form method="GET" action="teacher_dashboard.php">
        <label for="search">Search by student ID or name</label>

        <input type="search" id="search" name="search" placeholder="Enter student ID or name"
            value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">

        <button type="submit">Search</button>

        <a href="teacher_dashboard.php">Clear</a>
    </form>

    <p>
        <a href="create_student.php">Create Student Account</a>
    </p>

    <?php if ($error !== ''): ?>

    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>

    <?php else: ?>

    <p>
        <?= count($students) ?> student account(s) found.
    </p>

    <?php if (count($students) === 0): ?>

    <p>
        <?= $search !== ''
        ? 'No students matched your search.'
        : 'No student accounts have been created yet.' ?>
    </p>

    <?php else: ?>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Account Created</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td>
                    <?= htmlspecialchars(
                                    $student['student_id'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                                    $student['name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                                    $student['created_at'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>

    <?php endif; ?>
</body>

</html>