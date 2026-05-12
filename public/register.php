<?php
// public/register.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

if (isLoggedIn()) {
    header("Location: " . url('public/index.php'));
    exit();
}

$error = '';
$success = false;
$form_data = []; // retain input on error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_data = [
        'username' => $_POST['username'] ?? '',
        'email' => $_POST['email'] ?? '',
        'full_name' => $_POST['full_name'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'address' => $_POST['address'] ?? '',
        'role' => $_POST['role'] ?? 'customer'
    ];

    $username = sanitize($form_data['username']);
    $email = sanitize($form_data['email']);
    $full_name = sanitize($form_data['full_name']);
    $phone = sanitize($form_data['phone']);
    $address = sanitize($form_data['address']);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = sanitize($form_data['role']);

    // Server-side validation
    if (empty($username) || empty($email) || empty($full_name) || empty($password)) {
        $error = "All fields marked with * are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!in_array($role, ['customer', 'farmer'])) {
        $error = "Invalid role selected.";
    } else {
        // Attempt registration
        $user_id = registerUser($pdo, $username, $email, $password, $full_name, $phone, $address, $role);
        if ($user_id) {
            redirect(url('public/login.php'), "Account created successfully! Please login.", "success");
        } else {
            $error = "Error creating account. Email or username might already exist.";
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div style="max-width: 800px; margin: 4rem auto;" class="animate-fade-up">
    <div class="premium-card">
        <div style="text-align: center; margin-bottom: 3rem;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: var(--primary-glow); color: var(--primary); border-radius: 24px; font-size: 2.5rem; margin-bottom: 1.5rem;">
                <i class="fas fa-seedling"></i>
            </div>
            <h2 style="font-size: 2.5rem; letter-spacing: -1px; margin-bottom: 0.5rem;">Join the <span style="color: var(--primary);">Marketplace</span></h2>
            <p style="color: var(--text-muted); font-size: 1.1rem;">Support local agriculture and get the freshest produce.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="registerForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="username">Username <span style="color: var(--accent);">*</span></label>
                    <input type="text" name="username" id="username" class="form-control"
                        value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>" placeholder="e.g. abebe_88" required>
                </div>
                <div class="form-group">
                    <label for="full_name">Full Name <span style="color: var(--accent);">*</span></label>
                    <input type="text" name="full_name" id="full_name" class="form-control"
                        value="<?php echo htmlspecialchars($form_data['full_name'] ?? ''); ?>" placeholder="Abebe Kebede" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="email">Email Address <span style="color: var(--accent);">*</span></label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" placeholder="abebe@example.com" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" class="form-control"
                        value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>" placeholder="0911223344">
                </div>
            </div>

            <div class="form-group">
                <label for="address">Delivery Address</label>
                <textarea name="address" id="address" class="form-control" rows="2" placeholder="City, Sub-city, Woreda, House number"><?php echo htmlspecialchars($form_data['address'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="role">I want to...</label>
                <select name="role" id="role" class="form-control" required style="cursor: pointer;">
                    <option value="customer" <?php echo (($form_data['role'] ?? '') == 'customer') ? 'selected' : ''; ?>>Buy fresh produce (Customer)</option>
                    <option value="farmer" <?php echo (($form_data['role'] ?? '') == 'farmer') ? 'selected' : ''; ?>>Sell my harvest (Farmer)</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="password">Password <span style="color: var(--accent);">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                    <div id="password-strength" style="font-size: 0.8rem; margin-top: 0.5rem; font-weight: 600;"></div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password <span style="color: var(--accent);">*</span></label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.1rem; margin-top: 1rem;">
                <i class="fas fa-user-plus"></i> Create My Account
            </button>
        </form>

        <div style="text-align: center; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
            <p style="color: var(--text-muted);">
                Already have an account? <a href="<?php echo url('public/login.php'); ?>" style="color: var(--primary); font-weight: 700; border-bottom: 2px solid transparent; transition: var(--transition);" onmouseover="this.style.borderBottomColor='var(--primary)'" onmouseout="this.style.borderBottomColor='transparent'">Login here</a>
            </p>
        </div>
    </div>
</div>

<!-- Client-side JavaScript Validation (meets course requirement) -->
<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        let password = document.getElementById('password').value;
        let confirm = document.getElementById('confirm_password').value;
        let username = document.getElementById('username').value;
        let email = document.getElementById('email').value;
        let fullname = document.getElementById('full_name').value;
        let errorMsg = '';

        if (username.trim().length < 3) {
            errorMsg = 'Username must be at least 3 characters.\n';
        }
        if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            errorMsg += 'Please enter a valid email address.\n';
        }
        if (fullname.trim().length < 2) {
            errorMsg += 'Please enter your full name.\n';
        }
        if (password.length < 6) {
            errorMsg += 'Password must be at least 6 characters.\n';
        }
        if (password !== confirm) {
            errorMsg += 'Passwords do not match.\n';
        }

        if (errorMsg) {
            e.preventDefault();
            alert(errorMsg);
        }
    });

    // Optional: Real-time password strength indicator
    document.getElementById('password').addEventListener('input', function() {
        let strengthDiv = document.getElementById('password-strength');
        let val = this.value;
        if (val.length === 0) {
            strengthDiv.innerHTML = '';
        } else if (val.length < 4) {
            strengthDiv.innerHTML = 'Weak';
            strengthDiv.style.color = 'red';
        } else if (val.length < 7) {
            strengthDiv.innerHTML = 'Medium';
            strengthDiv.style.color = 'orange';
        } else {
            strengthDiv.innerHTML = 'Strong';
            strengthDiv.style.color = 'green';
        }
    });

</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>