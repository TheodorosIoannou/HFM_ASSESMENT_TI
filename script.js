/**
 * HF Markets Assessment - Frontend Validation
 * Client-side form validation and interactivity
 */

document.addEventListener('DOMContentLoaded', function() {
    // Country codes mapping (sample of 3 countries as required)
    const countryCodes = {
        'CY': '+357',
        'GR': '+30',
        'UK': '+44'
    };

    // Initialize country selector functionality
    initCountrySelector();

    // Initialize password toggle
    initPasswordToggle();

    // Initialize form validation
    initFormValidation();

    /**
     * Country selector - auto-fill phone country code
     */
    function initCountrySelector() {
        const countrySelect = document.getElementById('country');
        const countryCodeInput = document.getElementById('country_code');

        if (countrySelect && countryCodeInput) {
            countrySelect.addEventListener('change', function() {
                const selectedCountry = this.value;
                if (selectedCountry && countryCodes[selectedCountry]) {
                    countryCodeInput.value = countryCodes[selectedCountry];
                } else {
                    countryCodeInput.value = '';
                }
            });

            // Set initial value if country is pre-selected
            if (countrySelect.value && countryCodes[countrySelect.value]) {
                countryCodeInput.value = countryCodes[countrySelect.value];
            }
        }
    }

    /**
     * Password visibility toggle
     */
    function initPasswordToggle() {
        const toggleButtons = document.querySelectorAll('.toggle-password');

        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const wrapper = this.closest('.password-wrapper');
                const input = wrapper.querySelector('input');
                const eyeIcon = this.querySelector('.eye-icon');
                const eyeOffIcon = this.querySelector('.eye-off-icon');

                if (input.type === 'password') {
                    input.type = 'text';
                    eyeIcon.classList.add('hidden');
                    eyeOffIcon.classList.remove('hidden');
                } else {
                    input.type = 'password';
                    eyeIcon.classList.remove('hidden');
                    eyeOffIcon.classList.add('hidden');
                }
            });
        });
    }

    /**
     * Form validation
     */
    function initFormValidation() {
        const registerForm = document.getElementById('registerForm');
        const loginForm = document.getElementById('loginForm');

        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                if (!validateRegisterForm()) {
                    e.preventDefault();
                }
            });

            // Real-time validation on blur
            addRealTimeValidation(registerForm);
        }

        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                if (!validateLoginForm()) {
                    e.preventDefault();
                }
            });

            // Real-time validation on blur
            addRealTimeValidation(loginForm);
        }
    }

    /**
     * Add real-time validation on input blur
     */
    function addRealTimeValidation(form) {
        const inputs = form.querySelectorAll('input, select');

        inputs.forEach(function(input) {
            input.addEventListener('blur', function() {
                validateField(this);
            });

            input.addEventListener('input', function() {
                // Clear error on input
                const formGroup = this.closest('.form-group');
                if (formGroup) {
                    formGroup.classList.remove('has-error');
                    const errorText = formGroup.querySelector('.error-text');
                    if (errorText) {
                        errorText.remove();
                    }
                }
            });
        });
    }

    /**
     * Validate registration form
     */
    function validateRegisterForm() {
        let isValid = true;
        clearAllErrors();

        // First Name validation (length > 3)
        const firstName = document.getElementById('first_name');
        if (firstName) {
            if (!firstName.value.trim()) {
                showError(firstName, 'First name is required.');
                isValid = false;
            } else if (firstName.value.trim().length <= 3) {
                showError(firstName, 'First name must be more than 3 characters.');
                isValid = false;
            } else if (!/^[a-zA-Z\s\-]+$/.test(firstName.value.trim())) {
                showError(firstName, 'First name can only contain letters, spaces, and hyphens.');
                isValid = false;
            }
        }

        // Full Name validation (length > 3)
        const fullName = document.getElementById('full_name');
        if (fullName) {
            if (!fullName.value.trim()) {
                showError(fullName, 'Full name is required.');
                isValid = false;
            } else if (fullName.value.trim().length <= 3) {
                showError(fullName, 'Full name must be more than 3 characters.');
                isValid = false;
            } else if (!/^[a-zA-Z\s\-]+$/.test(fullName.value.trim())) {
                showError(fullName, 'Full name can only contain letters, spaces, and hyphens.');
                isValid = false;
            }
        }

        // Email validation
        const email = document.getElementById('email');
        if (email) {
            if (!email.value.trim()) {
                showError(email, 'Email address is required.');
                isValid = false;
            } else if (!isValidEmail(email.value.trim())) {
                showError(email, 'Please enter a valid email address (e.g., user@example.com).');
                isValid = false;
            }
        }

        // Country validation
        const country = document.getElementById('country');
        if (country && !country.value) {
            showError(country, 'Please select a country.');
            isValid = false;
        }

        // Country code validation (must be numeric)
        const countryCode = document.getElementById('country_code');
        if (countryCode) {
            if (!countryCode.value.trim()) {
                showError(countryCode, 'Country code is required. Please select a country.');
                isValid = false;
            } else {
                const codeToCheck = countryCode.value.trim().replace('+', '');
                if (!/^\d+$/.test(codeToCheck)) {
                    showError(countryCode, 'Country code must be numeric.');
                    isValid = false;
                }
            }
        }

        // Phone validation
        const phone = document.getElementById('phone');
        if (phone) {
            if (!phone.value.trim()) {
                showError(phone, 'Phone number is required.');
                isValid = false;
            } else {
                const cleanPhone = phone.value.trim().replace(/[\s\-\(\)]/g, '');
                if (!/^\d+$/.test(cleanPhone)) {
                    showError(phone, 'Phone number must contain only digits.');
                    isValid = false;
                } else if (cleanPhone.length < 6 || cleanPhone.length > 15) {
                    showError(phone, 'Phone number must be between 6 and 15 digits.');
                    isValid = false;
                }
            }
        }

        // Password validation (1 capital, 1 number, 1 symbol)
        const password = document.getElementById('password');
        if (password) {
            const passwordValue = password.value;
            if (!passwordValue) {
                showError(password, 'Password is required.');
                isValid = false;
            } else if (passwordValue.length < 8) {
                showError(password, 'Password must be at least 8 characters long.');
                isValid = false;
            } else if (!/[A-Z]/.test(passwordValue)) {
                showError(password, 'Password must contain at least 1 uppercase letter.');
                isValid = false;
            } else if (!/[0-9]/.test(passwordValue)) {
                showError(password, 'Password must contain at least 1 number.');
                isValid = false;
            } else if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/.test(passwordValue)) {
                showError(password, 'Password must contain at least 1 symbol (!@#$%^&*...).');
                isValid = false;
            }
        }

        // Password confirmation validation
        const passwordConfirm = document.getElementById('password_confirm');
        if (passwordConfirm && password) {
            if (!passwordConfirm.value) {
                showError(passwordConfirm, 'Please confirm your password.');
                isValid = false;
            } else if (passwordConfirm.value !== password.value) {
                showError(passwordConfirm, 'Passwords do not match.');
                isValid = false;
            }
        }

        return isValid;
    }

    /**
     * Validate login form
     */
    function validateLoginForm() {
        let isValid = true;
        clearAllErrors();

        // Email validation
        const email = document.getElementById('email');
        if (email) {
            if (!email.value.trim()) {
                showError(email, 'Email address is required.');
                isValid = false;
            } else if (!isValidEmail(email.value.trim())) {
                showError(email, 'Please enter a valid email address (e.g., user@example.com).');
                isValid = false;
            }
        }

        // Password validation
        const password = document.getElementById('password');
        if (password && !password.value) {
            showError(password, 'Password is required.');
            isValid = false;
        }

        return isValid;
    }

    /**
     * Validate single field on blur
     */
    function validateField(input) {
        const name = input.name;
        const value = input.value.trim();

        // Clear previous error
        const formGroup = input.closest('.form-group');
        if (formGroup) {
            formGroup.classList.remove('has-error');
            const existingError = formGroup.querySelector('.error-text');
            if (existingError) {
                existingError.remove();
            }
        }

        switch (name) {
            case 'first_name':
                if (value && value.length <= 3) {
                    showError(input, 'First name must be more than 3 characters.');
                }
                break;

            case 'full_name':
                if (value && value.length <= 3) {
                    showError(input, 'Full name must be more than 3 characters.');
                }
                break;

            case 'email':
                if (value && !isValidEmail(value)) {
                    showError(input, 'Please enter a valid email address.');
                }
                break;

            case 'password':
                if (value) {
                    if (value.length < 8) {
                        showError(input, 'Password must be at least 8 characters.');
                    } else if (!/[A-Z]/.test(value)) {
                        showError(input, 'Password needs at least 1 uppercase letter.');
                    } else if (!/[0-9]/.test(value)) {
                        showError(input, 'Password needs at least 1 number.');
                    } else if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/.test(value)) {
                        showError(input, 'Password needs at least 1 symbol.');
                    }
                }
                break;

            case 'password_confirm':
                const password = document.getElementById('password');
                if (value && password && value !== password.value) {
                    showError(input, 'Passwords do not match.');
                }
                break;
        }
    }

    /**
     * Show error message for a field
     */
    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        if (formGroup) {
            formGroup.classList.add('has-error');

            // Check if error message already exists
            let errorText = formGroup.querySelector('.error-text');
            if (!errorText) {
                errorText = document.createElement('span');
                errorText.className = 'error-text';
                formGroup.appendChild(errorText);
            }
            errorText.textContent = message;
        }
    }

    /**
     * Clear all error messages
     */
    function clearAllErrors() {
        const errorGroups = document.querySelectorAll('.form-group.has-error');
        errorGroups.forEach(function(group) {
            group.classList.remove('has-error');
        });

        const errorTexts = document.querySelectorAll('.error-text');
        errorTexts.forEach(function(error) {
            error.remove();
        });
    }

    /**
     * Validate email format
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});
