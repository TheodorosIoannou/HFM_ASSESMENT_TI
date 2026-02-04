# HF Markets Assessment - Registration & Login Forms

## Overview
This project implements registration and login forms with both frontend and backend validation using PHP and SQLite.

## Requirements Met

### Backend (PHP)
- Form validation using PHP
- SQLite database for user storage
- Validation rules:
  - First Name and Full Name: Length > 3
  - Country code: numeric
  - Email format validation
  - Password: at least 1 capital, 1 number, 1 symbol
- Clear and specific error messages for each validation failure
- Success message on registration
- Login page with email validation

### Frontend (JavaScript)
- Form creation with all required fields
- Country selection auto-fills phone country code (3 countries: Cyprus, Greece, UK)
- Client-side validation with same rules as backend
- Responsive design for all screen resolutions

## Files Structure

```
├── database.php      # Database connection and queries (SQLite)
├── validator.php     # Server-side validation class
├── register.php      # Registration form and handling
├── login.php         # Login form and handling
├── logout.php        # Session destruction
├── style.css         # Responsive CSS styles
├── script.js         # Frontend validation and interactivity
└── README.md         # This file
```

## How to Run

1. Make sure you have PHP installed (PHP 7.4+ recommended)
2. Navigate to the project directory
3. Start the PHP built-in server:
   ```bash
   php -S localhost:8000
   ```
4. Open your browser and go to:
   - Registration: `http://localhost:8000/register.php`
   - Login: `http://localhost:8000/login.php`

## Security Features

- Password hashing using PHP's `password_hash()` function
- Input sanitization with `htmlspecialchars()` and `filter_var()`
- Prepared statements to prevent SQL injection
- XSS protection through proper output encoding

## Countries Available (Sample)
- Cyprus (+357)
- Greece (+30)
- United Kingdom (+44)
