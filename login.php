<?php
/**
 * Login Form - HF Markets Assessment
 * Handles user authentication with email validation
 */

session_start();
require_once 'database.php';
require_once 'validator.php';

$errors = [];
$success = false;
$email = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator();
    $db = Database::getInstance();

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate email format
    $validator->validateEmail($email);

    if (empty($password)) {
        $validator->addError('password', 'Password is required.');
    }

    if ($validator->hasErrors()) {
        $errors = $validator->getErrors();
    } else {
        // Attempt authentication
        $user = $db->authenticateUser($email, $password);

        if ($user) {
            $success = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['first_name'];
        } else {
            $errors['general'] = 'Invalid email or password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HF Markets</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <span class="logo-text">Member of HF Markets Group</span>
                <div class="logo-main">
                    <span class="hf">HF</span><span class="m">M</span>
                </div>
                <span class="logo-subtitle">HF MARKETS</span>
            </div>
            <nav class="nav">
                <a href="register.php" class="btn btn-register">Register</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content login-page">
        <div class="page-title">
            <h1>LOGIN</h1>
        </div>

        <div class="form-container">
            <?php if ($success): ?>
                <div class="success-message">
                    <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
                    <p>You have successfully logged in.</p>
                    <a href="logout.php" class="btn btn-submit">Logout</a>
                </div>
            <?php else: ?>
                <h2>Lorem ipsum dolor sit amet</h2>

                <?php if (isset($errors['general'])): ?>
                    <div class="error-message general-error">
                        <?php echo htmlspecialchars($errors['general']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php" id="loginForm" novalidate>
                    <!-- Email -->
                    <div class="form-group <?php echo isset($errors['email']) ? 'has-error' : ''; ?>">
                        <input
                            type="email"
                            name="email"
                            id="email"
                            placeholder="Email"
                            value="<?php echo htmlspecialchars($email); ?>"
                            required
                        >
                        <?php if (isset($errors['email'])): ?>
                            <span class="error-text"><?php echo htmlspecialchars($errors['email']); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div class="form-group <?php echo isset($errors['password']) ? 'has-error' : ''; ?>">
                        <div class="password-wrapper">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Password"
                                required
                            >
                            <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                                <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="eye-off-icon hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <span class="error-text"><?php echo htmlspecialchars($errors['password']); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-submit">LOGIN</button>
                </form>

                <p class="form-footer">
                    Don't have an account? <a href="register.php">Register here</a>
                </p>
            <?php endif; ?>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>
