<?php
/**
 * Registration Form - HF Markets Assessment
 * Handles user registration with server-side validation
 */

session_start();
require_once 'database.php';
require_once 'validator.php';

$errors = [];
$success = false;
$formData = [
    'first_name' => '',
    'full_name' => '',
    'email' => '',
    'country' => '',
    'country_code' => '',
    'phone' => ''
];

// Countries with phone codes (sample of 3 as required)
$countries = [
    'CY' => ['name' => 'Cyprus', 'code' => '+357'],
    'GR' => ['name' => 'Greece', 'code' => '+30'],
    'UK' => ['name' => 'United Kingdom', 'code' => '+44']
];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator();
    $db = Database::getInstance();

    // Sanitize and store form data
    $formData['first_name'] = $_POST['first_name'] ?? '';
    $formData['full_name'] = $_POST['full_name'] ?? '';
    $formData['email'] = $_POST['email'] ?? '';
    $formData['country'] = $_POST['country'] ?? '';
    $formData['country_code'] = $_POST['country_code'] ?? '';
    $formData['phone'] = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    // Validate all fields
    $validator->validateFirstName($formData['first_name']);
    $validator->validateFullName($formData['full_name']);
    $validator->validateEmail($formData['email']);
    $validator->validateCountryCode($formData['country_code']);
    $validator->validatePhone($formData['phone']);
    $validator->validatePassword($password);
    $validator->validatePasswordConfirm($password, $passwordConfirm);

    // Check if email already exists
    if (!$validator->hasErrors() && $db->emailExists($formData['email'])) {
        $validator->addError('email', 'This email address is already registered.');
    }

    if ($validator->hasErrors()) {
        $errors = $validator->getErrors();
    } else {
        // Register user
        try {
            $db->registerUser(
                $formData['first_name'],
                $formData['full_name'],
                $formData['email'],
                $formData['country_code'],
                $formData['phone'],
                $password
            );
            $success = true;
            // Clear form data on success
            $formData = [
                'first_name' => '',
                'full_name' => '',
                'email' => '',
                'country' => '',
                'country_code' => '',
                'phone' => ''
            ];
        } catch (Exception $e) {
            $errors['general'] = 'An error occurred during registration. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - HF Markets</title>
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
                <a href="login.php" class="btn btn-login">Login</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content register-page">
        <div class="page-title">
            <h1>REGISTER</h1>
        </div>

        <div class="form-container">
            <?php if ($success): ?>
                <div class="success-message">
                    <h2>Thank you for signing up!</h2>
                    <p>Your account has been created successfully.</p>
                    <p>You can now <a href="login.php">login</a> to your account.</p>
                </div>
            <?php else: ?>
                <h2>Lorem ipsum dolor sit amet</h2>

                <?php if (isset($errors['general'])): ?>
                    <div class="error-message general-error">
                        <?php echo htmlspecialchars($errors['general']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="register.php" id="registerForm" novalidate>
                    <!-- First Name -->
                    <div class="form-group <?php echo isset($errors['first_name']) ? 'has-error' : ''; ?>">
                        <input
                            type="text"
                            name="first_name"
                            id="first_name"
                            placeholder="First Name"
                            value="<?php echo htmlspecialchars($formData['first_name']); ?>"
                            required
                        >
                        <?php if (isset($errors['first_name'])): ?>
                            <span class="error-text"><?php echo htmlspecialchars($errors['first_name']); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Full Name -->
                    <div class="form-group <?php echo isset($errors['full_name']) ? 'has-error' : ''; ?>">
                        <input
                            type="text"
                            name="full_name"
                            id="full_name"
                            placeholder="Full Name"
                            value="<?php echo htmlspecialchars($formData['full_name']); ?>"
                            required
                        >
                        <?php if (isset($errors['full_name'])): ?>
                            <span class="error-text"><?php echo htmlspecialchars($errors['full_name']); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="form-group <?php echo isset($errors['email']) ? 'has-error' : ''; ?>">
                        <input
                            type="email"
                            name="email"
                            id="email"
                            placeholder="Email"
                            value="<?php echo htmlspecialchars($formData['email']); ?>"
                            required
                        >
                        <?php if (isset($errors['email'])): ?>
                            <span class="error-text"><?php echo htmlspecialchars($errors['email']); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Country -->
                    <div class="form-group <?php echo isset($errors['country']) ? 'has-error' : ''; ?>">
                        <select name="country" id="country" required>
                            <option value="">Select Country</option>
                            <?php foreach ($countries as $code => $country): ?>
                                <option
                                    value="<?php echo $code; ?>"
                                    data-code="<?php echo $country['code']; ?>"
                                    <?php echo $formData['country'] === $code ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($country['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Phone with Country Code -->
                    <div class="form-group phone-group <?php echo isset($errors['phone']) || isset($errors['country_code']) ? 'has-error' : ''; ?>">
                        <div class="phone-input-wrapper">
                            <input
                                type="text"
                                name="country_code"
                                id="country_code"
                                class="country-code-input"
                                placeholder="+00"
                                value="<?php echo htmlspecialchars($formData['country_code']); ?>"
                                readonly
                            >
                            <input
                                type="tel"
                                name="phone"
                                id="phone"
                                class="phone-input"
                                placeholder="Phone Number"
                                value="<?php echo htmlspecialchars($formData['phone']); ?>"
                                required
                            >
                        </div>
                        <?php if (isset($errors['country_code'])): ?>
                            <span class="error-text"><?php echo htmlspecialchars($errors['country_code']); ?></span>
                        <?php endif; ?>
                        <?php if (isset($errors['phone'])): ?>
                            <span class="error-text"><?php echo htmlspecialchars($errors['phone']); ?></span>
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
                        <div class="password-requirements">
                            <small>Password must contain: 8+ characters, 1 uppercase, 1 number, 1 symbol</small>
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <span class="error-text"><?php echo htmlspecialchars($errors['password']); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group <?php echo isset($errors['password_confirm']) ? 'has-error' : ''; ?>">
                        <div class="password-wrapper">
                            <input
                                type="password"
                                name="password_confirm"
                                id="password_confirm"
                                placeholder="Confirm Password"
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
                        <?php if (isset($errors['password_confirm'])): ?>
                            <span class="error-text"><?php echo htmlspecialchars($errors['password_confirm']); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-submit">REGISTER</button>
                </form>

                <p class="form-footer">
                    Already have an account? <a href="login.php">Login here</a>
                </p>
            <?php endif; ?>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>
