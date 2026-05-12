<?php
// public/login.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

if (isLoggedIn()) {
    header("Location: " . url('public/index.php'));
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    if (loginUser($pdo, $email, $password)) {
        redirect(url('public/index.php'), "Welcome back, " . $_SESSION['full_name'] . "!");
    } else {
        $error = "Invalid email or password.";
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div style="max-width: 500px; margin: 6rem auto;" class="animate-fade-up">
    <div class="premium-card">
        <div style="text-align: center; margin-bottom: 3rem;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: var(--primary-glow); color: var(--primary); border-radius: 24px; font-size: 2.5rem; margin-bottom: 1.5rem;">
                <i class="fas fa-lock"></i>
            </div>
            <h2 style="font-size: 2.5rem; letter-spacing: -1px; margin-bottom: 0.5rem;">Welcome <span style="color: var(--primary);">Back</span></h2>
            <p style="color: var(--text-muted); font-size: 1.1rem;">Login to access your marketplace.</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="abebe@example.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div style="margin: 1.5rem 0 2rem; display: flex; justify-content: space-between; align-items: center;">
                <label style="display: flex; align-items: center; gap: 10px; font-size: 0.95rem; cursor: pointer; color: var(--text-muted);">
                    <input type="checkbox" name="remember" style="width: 18px; height: 18px; accent-color: var(--primary);"> Remember me
                </label>
                <a href="#" style="font-size: 0.95rem; color: var(--primary); font-weight: 600;">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.1rem;">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div style="text-align: center; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
            <p style="color: var(--text-muted);">
                Don't have an account? <a href="<?php echo url('public/register.php'); ?>" style="color: var(--primary); font-weight: 700; border-bottom: 2px solid transparent; transition: var(--transition);" onmouseover="this.style.borderBottomColor='var(--primary)'" onmouseout="this.style.borderBottomColor='transparent'">Create one</a>
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
