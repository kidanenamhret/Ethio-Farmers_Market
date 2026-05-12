<?php
// public/admin/add-user.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = sanitize($_POST['role'] ?? 'customer');

    // Basic validation
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            // Create user
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            if ($insert->execute([$full_name, $email, $hashed, $role])) {
                redirect(url('public/admin/manage-users.php'), "User '$full_name' created successfully!", 'success');
                exit();
            } else {
                $error = "Database error. Please try again.";
            }
        }
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div style="max-width: 800px; margin: 0 auto; padding: 2rem;" class="animate-fade-up">
    <div style="margin-bottom: 4rem;">
        <h1 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 0.5rem;">Add New <span style="color: var(--primary);">User</span></h1>
        <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Manually register a new marketplace participant</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error" style="margin-bottom: 2rem;">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="premium-card" style="padding: 3.5rem; border-radius: 40px;">
        <form method="POST" style="display: flex; flex-direction: column; gap: 2rem;">
            <div style="display: grid; gap: 10px;">
                <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted);">Full Name</label>
                <input type="text" name="full_name" required placeholder="Abebe Bikila" 
                       style="width: 100%; padding: 1.2rem; border-radius: 16px; border: 2px solid var(--bg-main); background: var(--bg-main); font-family: inherit; font-weight: 700; outline: none; transition: var(--transition);"
                       onfocus="this.style.borderColor='var(--primary-glow)'; this.style.background='white'">
            </div>

            <div style="display: grid; gap: 10px;">
                <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted);">Email Address</label>
                <input type="email" name="email" required placeholder="abebe@example.com" 
                       style="width: 100%; padding: 1.2rem; border-radius: 16px; border: 2px solid var(--bg-main); background: var(--bg-main); font-family: inherit; font-weight: 700; outline: none; transition: var(--transition);"
                       onfocus="this.style.borderColor='var(--primary-glow)'; this.style.background='white'">
            </div>

            <div style="display: grid; gap: 10px;">
                <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted);">Initial Password</label>
                <input type="password" name="password" required placeholder="••••••••" 
                       style="width: 100%; padding: 1.2rem; border-radius: 16px; border: 2px solid var(--bg-main); background: var(--bg-main); font-family: inherit; font-weight: 700; outline: none; transition: var(--transition);"
                       onfocus="this.style.borderColor='var(--primary-glow)'; this.style.background='white'">
            </div>

            <div style="display: grid; gap: 12px;">
                <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted);">Assigned Role</label>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
                    <label class="glass role-label" style="padding: 1.5rem; cursor: pointer; border-radius: 20px; border: 2px solid var(--primary); text-align: center; position: relative;">
                        <input type="radio" name="role" value="customer" checked style="position: absolute; opacity: 0;">
                        <div style="font-size: 1.5rem; margin-bottom: 10px; color: var(--primary);"><i class="fas fa-shopping-bag"></i></div>
                        <div style="font-weight: 800; text-transform: capitalize; color: var(--text-main);">Customer</div>
                    </label>
                    <label class="glass role-label" style="padding: 1.5rem; cursor: pointer; border-radius: 20px; border: 2px solid var(--border-light); text-align: center; position: relative;">
                        <input type="radio" name="role" value="farmer" style="position: absolute; opacity: 0;">
                        <div style="font-size: 1.5rem; margin-bottom: 10px; color: var(--text-muted);"><i class="fas fa-tractor"></i></div>
                        <div style="font-weight: 800; text-transform: capitalize; color: var(--text-muted);">Farmer</div>
                    </label>
                    <label class="glass role-label" style="padding: 1.5rem; cursor: pointer; border-radius: 20px; border: 2px solid var(--border-light); text-align: center; position: relative;">
                        <input type="radio" name="role" value="admin" style="position: absolute; opacity: 0;">
                        <div style="font-size: 1.5rem; margin-bottom: 10px; color: var(--text-muted);"><i class="fas fa-user-shield"></i></div>
                        <div style="font-weight: 800; text-transform: capitalize; color: var(--text-muted);">Admin</div>
                    </label>
                </div>
            </div>

            <div style="margin-top: 1rem; display: flex; gap: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 2; padding: 1.2rem; font-size: 1.1rem; border-radius: 20px; box-shadow: 0 15px 30px -10px rgba(27, 94, 32, 0.4);">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
                <a href="manage-users.php" class="btn glass" style="flex: 1; padding: 1.2rem; border-radius: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1.5px solid var(--border-light);">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('input[name="role"]').forEach(input => {
        input.addEventListener('change', function() {
            document.querySelectorAll('.role-label').forEach(label => {
                label.style.borderColor = 'var(--border-light)';
                label.querySelector('div').style.color = 'var(--text-muted)';
                label.querySelector('div:last-child').style.color = 'var(--text-muted)';
            });
            this.parentElement.style.borderColor = 'var(--primary)';
            this.parentElement.querySelector('div').style.color = 'var(--primary)';
            this.parentElement.querySelector('div:last-child').style.color = 'var(--text-main)';
        });
    });
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
