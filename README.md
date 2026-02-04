# HF Markets Assessment - Registration & Login Forms

## Overview
This project implements registration and login forms with both frontend and backend validation using PHP and SQLite.

---

## Prerequisites

| Software | Download Link | Required |
|----------|---------------|----------|
| **Git** | https://git-scm.com/download/win | Yes |
| **XAMPP** | https://www.apachefriends.org/download.html | Yes (includes PHP) |

> **Note:** We use XAMPP because it's the easiest way to get PHP on Windows. You don't need to start Apache or MySQL from XAMPP - we only use its PHP executable.

---

## Quick Start Guide (Windows + VS Code + PowerShell)

### Step 1: Install Git

1. Download from: https://git-scm.com/download/win
2. Run installer with default settings
3. Restart VS Code after installation

### Step 2: Install XAMPP

1. Download from: https://www.apachefriends.org/download.html
2. Run the installer
3. **Important:** Note where you install it (default is `C:\xampp`)
4. You do NOT need to open XAMPP Control Panel - we only need the PHP files

### Step 3: Verify Installation

Open PowerShell in VS Code (`Ctrl + `` `) and run:

```powershell
# Check Git
git --version

# Check PHP (via XAMPP) - use YOUR xampp path
C:\xampp\php\php.exe --version
```

If XAMPP is on your Desktop, use:
```powershell
C:\Users\YourUsername\Desktop\xampp\php\php.exe --version
```

You should see version numbers for both.

### Step 4: Clone the Repository

```powershell
cd ~\Desktop
git clone https://github.com/TheodorosIoannou/HFM_ASSESMENT_TI.git
cd HFM_ASSESMENT_TI
```

### Step 5: Run the PHP Server (using XAMPP's PHP)

**If XAMPP is in `C:\xampp`:**
```powershell
C:\xampp\php\php.exe -S localhost:8000
```

**If XAMPP is on Desktop:**
```powershell
C:\Users\YourUsername\Desktop\xampp\php\php.exe -S localhost:8000
```

You should see:
```
PHP 8.x.x Development Server (http://localhost:8000) started
```

### Step 6: Open in Browser

- **Registration:** http://localhost:8000/register.php
- **Login:** http://localhost:8000/login.php

### Step 7: Stop the Server

Press `Ctrl + C` in the PowerShell terminal.

---

## Quick Start Guide (Mac/Linux)

```bash
# Install PHP (if needed)
# Mac: brew install php
# Ubuntu: sudo apt install php php-sqlite3

# Clone and run
cd ~/Desktop
git clone https://github.com/TheodorosIoannou/HFM_ASSESMENT_TI.git
cd HFM_ASSESMENT_TI
php -S localhost:8000
```

---

## Test the Forms

**Registration - use these test values:**
| Field | Value |
|-------|-------|
| First Name | `John` |
| Full Name | `John Smith` |
| Email | `john@example.com` |
| Country | `Cyprus` (auto-fills +357) |
| Phone | `99123456` |
| Password | `Test123!` |

**Login:**
- Email: `john@example.com`
- Password: `Test123!`

---

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

---

## Files Structure

```
HFM_ASSESMENT_TI/
├── database.php      # SQLite database connection and queries
├── validator.php     # Server-side validation class
├── register.php      # Registration form and handling
├── login.php         # Login form and handling
├── logout.php        # Session destruction
├── style.css         # Responsive CSS styles
├── script.js         # Frontend validation and interactivity
├── hfm_users.db      # SQLite database (auto-created on first run)
└── README.md         # This file
```

---

## Security Features

- Password hashing using PHP's `password_hash()` function
- Input sanitization with `htmlspecialchars()` and `filter_var()`
- Prepared statements to prevent SQL injection
- XSS protection through proper output encoding

---

## Countries Available (Sample)
| Country | Code |
|---------|------|
| Cyprus | +357 |
| Greece | +30 |
| United Kingdom | +44 |

---

## Troubleshooting

**"php is not recognized" error:**

This means you need to use the full path to XAMPP's PHP:
```powershell
# Find your XAMPP installation and use the full path:
C:\xampp\php\php.exe -S localhost:8000

# If XAMPP is on Desktop:
C:\Users\YourUsername\Desktop\xampp\php\php.exe -S localhost:8000
```

**Check if XAMPP's PHP is installed correctly:**
```powershell
# Test if file exists (should return True)
Test-Path C:\xampp\php\php.exe

# Or for Desktop installation:
Test-Path C:\Users\YourUsername\Desktop\xampp\php\php.exe
```

**Port 8000 already in use:**
```powershell
# Use a different port
C:\xampp\php\php.exe -S localhost:8080
```

**XAMPP not installed:**

Download and install from: https://www.apachefriends.org/download.html
