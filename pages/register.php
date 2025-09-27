<?php
session_start();

// Redirect if already logged in (but not if guest session)
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    // Check if it's not a guest session
    if (strpos($_SESSION['user_id'], 'guest_') !== 0) {
        header('Location: ../index.php');
        exit();
    }
}

$error = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'passwords':
            $error = 'Passwords do not match';
            break;
        case 'email_exists':
            $error = 'An account with this email already exists';
            break;
        case 'required':
            $error = 'Please fill in all required fields';
            break;
        case 'invalid_email':
            $error = 'Please enter a valid email address';
            break;
        case 'weak_password':
            $error = 'Password must be at least 8 characters long and contain uppercase, lowercase, number, and special character';
            break;
        default:
            $error = 'Registration failed. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - YPT Study</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 20px;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .auth-logo {
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }

        .auth-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .auth-subtitle {
            color: var(--text-light);
            margin-bottom: 30px;
        }

        .auth-form {
            text-align: left;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 20px;
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .input-group {
            position: relative;
        }

        .input-group .form-control {
            padding-left: 48px;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-light);
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .password-strength {
            margin-top: 8px;
            display: none;
        }

        .password-strength-bar {
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .password-strength-fill {
            height: 100%;
            background: #dc2626;
            transition: all 0.3s ease;
            width: 0%;
        }

        .password-strength-fill.weak { background: #dc2626; }
        .password-strength-fill.fair { background: #f59e0b; }
        .password-strength-fill.good { background: #10b981; }
        .password-strength-fill.strong { background: #059669; }

        .password-requirements {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .password-requirements div {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 4px 0;
        }

        .password-requirements .check {
            color: #10b981;
        }

        .password-requirements .cross {
            color: #dc2626;
        }

        .terms-agreement {
            margin: 20px 0;
        }

        .terms-agreement label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-weight: normal;
            cursor: pointer;
        }

        .terms-agreement input[type="checkbox"] {
            width: auto;
            margin-top: 4px;
        }

        .terms-agreement a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .terms-agreement a:hover {
            text-decoration: underline;
        }

        .auth-btn {
            width: 100%;
            padding: 14px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .auth-btn:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .auth-btn:disabled {
            background: var(--border-color);
            cursor: not-allowed;
            transform: none;
        }

        .auth-divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: var(--text-light);
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        .auth-divider span {
            padding: 0 15px;
            font-size: 0.9rem;
        }

        .social-login {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .social-btn {
            flex: 1;
            padding: 12px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            color: var(--text-dark);
        }

        .social-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .auth-switch {
            color: var(--text-light);
        }

        .auth-switch a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-switch a:hover {
            color: var(--primary-dark);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .benefits-preview {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            opacity: 0.8;
            text-align: left;
        }

        .benefits-preview h3 {
            margin-bottom: 15px;
            font-weight: 600;
        }

        .benefits-preview ul {
            list-style: none;
            padding: 0;
        }

        .benefits-preview li {
            padding: 5px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .benefits-preview li i {
            width: 16px;
        }

        @media (max-width: 768px) {
            .auth-container {
                padding: 10px;
            }

            .auth-card {
                padding: 30px 20px;
            }

            .form-row {
                flex-direction: column;
            }

            .benefits-preview {
                display: none;
            }

            .social-login {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="benefits-preview">
            <h3>🌟 Join thousands of students</h3>
            <ul>
                <li><i class="fas fa-check"></i> Track your progress</li>
                <li><i class="fas fa-check"></i> Join study groups</li>
                <li><i class="fas fa-check"></i> Earn achievements</li>
                <li><i class="fas fa-check"></i> Stay focused</li>
                <li><i class="fas fa-check"></i> Review daily</li>
                <li><i class="fas fa-check"></i> Free forever</li>
            </ul>
        </div>

        <div class="auth-card">
            <div class="auth-logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            
            <h1 class="auth-title">Join YPT Study</h1>
            <p class="auth-subtitle">Create your account and start learning smarter</p>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form class="auth-form" id="register-form" action="../api/auth/register.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <div class="input-group">
                            <input type="text" id="first_name" name="first_name" class="form-control" required>
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <div class="input-group">
                            <input type="text" id="last_name" name="last_name" class="form-control" required>
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <div class="input-group">
                        <input type="email" id="email" name="email" class="form-control" required>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" required>
                        <i class="fas fa-lock input-icon"></i>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                    </div>
                    <div class="password-strength" id="password-strength">
                        <div class="password-strength-bar">
                            <div class="password-strength-fill" id="strength-fill"></div>
                        </div>
                        <div class="password-requirements" id="password-requirements">
                            <div><i class="fas fa-times cross" id="length-check"></i> At least 8 characters</div>
                            <div><i class="fas fa-times cross" id="uppercase-check"></i> One uppercase letter</div>
                            <div><i class="fas fa-times cross" id="lowercase-check"></i> One lowercase letter</div>
                            <div><i class="fas fa-times cross" id="number-check"></i> One number</div>
                            <div><i class="fas fa-times cross" id="special-check"></i> One special character</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <div class="input-group">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        <i class="fas fa-lock input-icon"></i>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('confirm_password')"></i>
                    </div>
                    <div id="password-match" style="margin-top: 8px; font-size: 0.8rem; display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="grade_level">Grade Level (Optional)</label>
                    <select id="grade_level" name="grade_level" class="form-control">
                        <option value="">Select your grade level</option>
                        <option value="6">6th Grade</option>
                        <option value="7">7th Grade</option>
                        <option value="8">8th Grade</option>
                        <option value="9">9th Grade</option>
                        <option value="10">10th Grade</option>
                        <option value="11">11th Grade</option>
                        <option value="12">12th Grade</option>
                        <option value="college">College</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="terms-agreement">
                    <label>
                        <input type="checkbox" name="terms" id="terms" required>
                        I agree to the <a href="terms.php" target="_blank">Terms of Service</a> and 
                        <a href="privacy.php" target="_blank">Privacy Policy</a>
                    </label>
                </div>

                <div class="terms-agreement">
                    <label>
                        <input type="checkbox" name="newsletter" id="newsletter">
                        Send me study tips and updates (optional)
                    </label>
                </div>

                <button type="submit" class="auth-btn" id="register-btn" disabled>
                    <span class="btn-text">Create Account</span>
                    <div class="loading-spinner"></div>
                </button>
            </form>

            <div class="auth-divider">
                <span>or sign up with</span>
            </div>

            <div class="social-login">
                <a href="api/auth/google-register.php" class="social-btn">
                    <i class="fab fa-google"></i>
                    Google
                </a>
                <a href="api/auth/github-register.php" class="social-btn">
                    <i class="fab fa-github"></i>
                    GitHub
                </a>
            </div>

            <p class="auth-switch">
                Already have an account? 
                <a href="login.php">Sign in here</a>
            </p>
        </div>
    </div>

    <script>
        // Password toggle functionality
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = passwordInput.parentElement.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };

            const passed = Object.values(requirements).filter(Boolean).length;
            let strength = 'weak';
            
            if (passed >= 5) strength = 'strong';
            else if (passed >= 4) strength = 'good';
            else if (passed >= 3) strength = 'fair';

            return { requirements, strength, score: passed * 20 };
        }

        // Update password strength display
        function updatePasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthIndicator = document.getElementById('password-strength');
            const strengthFill = document.getElementById('strength-fill');
            
            if (password.length === 0) {
                strengthIndicator.style.display = 'none';
                return;
            }
            
            strengthIndicator.style.display = 'block';
            
            const { requirements, strength, score } = checkPasswordStrength(password);
            
            strengthFill.style.width = score + '%';
            strengthFill.className = `password-strength-fill ${strength}`;
            
            // Update requirement checks
            document.getElementById('length-check').className = requirements.length ? 'fas fa-check check' : 'fas fa-times cross';
            document.getElementById('uppercase-check').className = requirements.uppercase ? 'fas fa-check check' : 'fas fa-times cross';
            document.getElementById('lowercase-check').className = requirements.lowercase ? 'fas fa-check check' : 'fas fa-times cross';
            document.getElementById('number-check').className = requirements.number ? 'fas fa-check check' : 'fas fa-times cross';
            document.getElementById('special-check').className = requirements.special ? 'fas fa-check check' : 'fas fa-times cross';
        }

        // Check password match
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchIndicator = document.getElementById('password-match');
            
            if (confirmPassword.length === 0) {
                matchIndicator.style.display = 'none';
                return true;
            }
            
            matchIndicator.style.display = 'block';
            
            if (password === confirmPassword) {
                matchIndicator.innerHTML = '<i class="fas fa-check" style="color: #10b981;"></i> Passwords match';
                matchIndicator.style.color = '#10b981';
                return true;
            } else {
                matchIndicator.innerHTML = '<i class="fas fa-times" style="color: #dc2626;"></i> Passwords do not match';
                matchIndicator.style.color = '#dc2626';
                return false;
            }
        }

        // Validate form
        function validateForm() {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const terms = document.getElementById('terms').checked;
            
            const { requirements } = checkPasswordStrength(password);
            const allRequirementsMet = Object.values(requirements).every(Boolean);
            const passwordsMatch = password === confirmPassword;
            
            const isValid = firstName && lastName && email && password && confirmPassword && 
                           allRequirementsMet && passwordsMatch && terms;
            
            document.getElementById('register-btn').disabled = !isValid;
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            password.addEventListener('input', () => {
                updatePasswordStrength();
                validateForm();
            });
            
            confirmPassword.addEventListener('input', () => {
                checkPasswordMatch();
                validateForm();
            });
            
            // Add validation to all required fields
            document.querySelectorAll('input[required], select[required]').forEach(field => {
                field.addEventListener('input', validateForm);
                field.addEventListener('change', validateForm);
            });
            
            document.getElementById('terms').addEventListener('change', validateForm);
        });

        // Form submission handling
        document.getElementById('register-form').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('register-btn');
            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = submitBtn.querySelector('.loading-spinner');
            
            // Show loading state
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            spinner.style.display = 'inline-block';
        });
    </script>
</body>
</html>