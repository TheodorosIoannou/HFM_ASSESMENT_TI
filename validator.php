<?php
/**
 * Validator class for form validation
 * Provides server-side validation with specific error messages
 */

class Validator {
    private $errors = [];

    /**
     * Validate first name (length > 3)
     */
    public function validateFirstName($firstName) {
        $firstName = trim($firstName);

        if (empty($firstName)) {
            $this->errors['first_name'] = 'First name is required.';
            return false;
        }

        if (strlen($firstName) <= 3) {
            $this->errors['first_name'] = 'First name must be more than 3 characters.';
            return false;
        }

        if (!preg_match('/^[a-zA-Z\s\-]+$/', $firstName)) {
            $this->errors['first_name'] = 'First name can only contain letters, spaces, and hyphens.';
            return false;
        }

        return true;
    }

    /**
     * Validate full name (length > 3)
     */
    public function validateFullName($fullName) {
        $fullName = trim($fullName);

        if (empty($fullName)) {
            $this->errors['full_name'] = 'Full name is required.';
            return false;
        }

        if (strlen($fullName) <= 3) {
            $this->errors['full_name'] = 'Full name must be more than 3 characters.';
            return false;
        }

        if (!preg_match('/^[a-zA-Z\s\-]+$/', $fullName)) {
            $this->errors['full_name'] = 'Full name can only contain letters, spaces, and hyphens.';
            return false;
        }

        return true;
    }

    /**
     * Validate email format
     */
    public function validateEmail($email) {
        $email = trim($email);

        if (empty($email)) {
            $this->errors['email'] = 'Email address is required.';
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Please enter a valid email address (e.g., user@example.com).';
            return false;
        }

        return true;
    }

    /**
     * Validate country code (must be numeric)
     */
    public function validateCountryCode($countryCode) {
        $countryCode = trim($countryCode);

        if (empty($countryCode)) {
            $this->errors['country_code'] = 'Country code is required.';
            return false;
        }

        // Remove + sign if present for validation
        $codeToCheck = ltrim($countryCode, '+');

        if (!ctype_digit($codeToCheck)) {
            $this->errors['country_code'] = 'Country code must be numeric.';
            return false;
        }

        return true;
    }

    /**
     * Validate phone number
     */
    public function validatePhone($phone) {
        $phone = trim($phone);

        if (empty($phone)) {
            $this->errors['phone'] = 'Phone number is required.';
            return false;
        }

        // Remove common phone formatting characters for validation
        $cleanPhone = preg_replace('/[\s\-\(\)]/', '', $phone);

        if (!ctype_digit($cleanPhone)) {
            $this->errors['phone'] = 'Phone number must contain only digits.';
            return false;
        }

        if (strlen($cleanPhone) < 6 || strlen($cleanPhone) > 15) {
            $this->errors['phone'] = 'Phone number must be between 6 and 15 digits.';
            return false;
        }

        return true;
    }

    /**
     * Validate password (at least 1 capital, 1 number, 1 symbol)
     */
    public function validatePassword($password) {
        if (empty($password)) {
            $this->errors['password'] = 'Password is required.';
            return false;
        }

        if (strlen($password) < 8) {
            $this->errors['password'] = 'Password must be at least 8 characters long.';
            return false;
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $this->errors['password'] = 'Password must contain at least 1 uppercase letter.';
            return false;
        }

        if (!preg_match('/[0-9]/', $password)) {
            $this->errors['password'] = 'Password must contain at least 1 number.';
            return false;
        }

        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?~`]/', $password)) {
            $this->errors['password'] = 'Password must contain at least 1 symbol (!@#$%^&*...).';
            return false;
        }

        return true;
    }

    /**
     * Validate password confirmation
     */
    public function validatePasswordConfirm($password, $confirmPassword) {
        if ($password !== $confirmPassword) {
            $this->errors['password_confirm'] = 'Passwords do not match.';
            return false;
        }
        return true;
    }

    /**
     * Get all errors
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Check if there are any errors
     */
    public function hasErrors() {
        return !empty($this->errors);
    }

    /**
     * Clear all errors
     */
    public function clearErrors() {
        $this->errors = [];
    }

    /**
     * Add custom error
     */
    public function addError($field, $message) {
        $this->errors[$field] = $message;
    }
}
