<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Monitoring System - Login</title>
    <link rel = "stylesheet" href="css/style.css">
</head>
<body>
    <main class="login-container">
        <h1>Exam Monitoring System</h1>
        <p>Sign in to continue</p>

        <form action="login.php" method="POST">
            <input
                type = "text"
                id = "email"
                name = "username"
                placeholder = "Enter your email or student ID"
                required
            >

            <label for="password">Password</label>
            <input 
                type = "password"
                id = "password"
                name = "password"
                placeholder = "Enter your password"
                required
            >

            <button type = "submit">Log in</button>
        </form>
    </main>
</body>
</html>