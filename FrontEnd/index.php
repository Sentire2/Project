<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Monitoring System - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="login-page">

    <div class="login-card">

        <!-- Left info panel -->
        <aside class="login-aside" aria-hidden="true">
            <div>
                <h2>Secure, fair and simple exam monitoring.</h2>
                <p>Manage exams, track sessions and review results in one place.</p>
            </div>

            <ul class="feature-list">
                <li><span class="dot">&#10003;</span> Real-time exam supervision</li>
                <li><span class="dot">&#10003;</span> Secure student authentication</li>
                <li><span class="dot">&#10003;</span> Instant reports and results</li>
            </ul>
        </aside>

        <!-- Login form -->
        <main class="login-container">

            <div class="login-brand">
                <div class="brand-icon">EM</div>
                <span>EXAM MONITOR</span>
            </div>

            <div class="login-header">
                <h1>Welcome back</h1>
                <p>Sign in to continue to your account.</p>
            </div>

            <div class="form-error" id="formError" role="alert">
                Invalid username or password.
            </div>

            <form action="login.php" method="POST" id="loginForm">

                <div class="form-group">
                    <label for="username">Email or Student ID</label>

                    <div class="input-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Enter your email or student ID"
                            autocomplete="username"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <div class="input-wrap">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button type="button" class="toggle-password" id="togglePassword"
                            aria-label="Show password">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1">
                        Remember me
                    </label>

                    <a href="forgot-password.php" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-primary" id="submitBtn">Sign in</button>

            </form>

            <div class="login-footer">
                <p>&copy; Exam Monitoring System</p>
            </div>

        </main>

    </div>

    <script>
        // Show / hide password
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');

        toggleBtn.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            toggleBtn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });

        // Prevent double submit
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = 'Signing in...';
        });

        // Show error if login.php redirects back with ?error=1
        if (new URLSearchParams(window.location.search).get('error')) {
            document.getElementById('formError').classList.add('show');
        }
    </script>

</body>
</html>
