<?php
// public/admin/edit-user.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$user_id = (int)($_GET['id'] ?? 0);

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    redirect(url('public/admin/manage-users.php'), 'User not found.', 'error');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name'] ?? '');
    $role = sanitize($_POST['role'] ?? '');

    if (!in_array($role, ['admin', 'farmer', 'customer'])) {
        $error = "Invalid role selected.";
    } else {
        $updateStmt = $pdo->prepare("UPDATE users SET full_name = ?, role = ? WHERE id = ?");
        if ($updateStmt->execute([$full_name, $role, $user_id])) {
            redirect(url('public/admin/manage-users.php'), 'User updated successfully!', 'success');
            exit();
        }
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div style="max-width: 800px; margin: 0 auto; padding: 2rem;" class="animate-fade-up">
    <div style="margin-bottom: 4rem;">
        <h1 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 0.5rem;">Edit <span style="color: var(--primary);">User</span></h1>
        <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Modify profile and access levels for @<?php echo htmlspecialchars($user['email']); ?></p>
    </div>

    <div class="premium-card" style="padding: 3.5rem; border-radius: 40px;">
        <form method="POST" style="display: flex; flex-direction: column; gap: 2.5rem;">
            <div style="display: grid; gap: 12px;">
                <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted);">Full Name</label>
                <input type="text" name="full_name" required value="<?php echo htmlspecialchars($user['full_name']); ?>" 
                       style="width: 100%; padding: 1.2rem; border-radius: 16px; border: 2px solid var(--bg-main); background: var(--bg-main); font-family: inherit; font-weight: 700; outline: none; transition: var(--transition);"
                       onfocus="this.style.borderColor='var(--primary-glow)'; this.style.background='white'">
            </div>

            <div style="display: grid; gap: 12px;">
                <label style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted);">Platform Role</label>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
                    <?php foreach (['customer', 'farmer', 'admin'] as $r): ?>
                        <label class="glass" style="padding: 1.5rem; cursor: pointer; border-radius: 20px; border: 2px solid <?php echo $user['role'] == $r ? 'var(--primary)' : 'var(--border-light)'; ?>; transition: var(--transition); text-align: center; position: relative;">
                            <input type="radio" name="role" value="<?php echo $r; ?>" <?php echo $user['role'] == $r ? 'checked' : ''; ?> style="position: absolute; opacity: 0;">
                            <div style="font-size: 1.5rem; margin-bottom: 10px; color: <?php echo $user['role'] == $r ? 'var(--primary)' : 'var(--text-muted)'; ?>">
                                <i class="fas <?php 
                                    echo match($r) {
                                        'admin' => 'fa-user-shield',
                                        'farmer' => 'fa-tractor',
                                        'customer' => 'fa-shopping-bag'
                                    };
                                ?>"></i>
                            </div>
                            <div style="font-weight: 800; text-transform: capitalize; color: <?php echo $user['role'] == $r ? 'var(--text-main)' : 'var(--text-muted)'; ?>;"><?php echo $r; ?></div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="margin-top: 1rem; display: flex; gap: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 2; padding: 1.2rem; font-size: 1.1rem; border-radius: 20px; box-shadow: 0 15px 30px -10px rgba(27, 94, 32, 0.4);">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="manage-users.php" class="btn glass" style="flex: 1; padding: 1.2rem; border-radius: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1.5px solid var(--border-light);">
                    Cancel
                </a>
            </div>
        </form>
    </div>
    
    <div style="margin-top: 3rem; padding: 2rem; background: var(--bg-main); border-radius: 25px; border: 1px dashed var(--border-light); display: flex; align-items: center; gap: 20px;">
        <div style="width: 50px; height: 50px; background: white; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #ef4444;">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600; line-height: 1.5;">Changing a user's role will immediately grant them the associated permissions and dashboard access.</p>
    </div>
</div>

<script>
    document.querySelectorAll('input[name="role"]').forEach(input => {
        input.addEventListener('change', function() {
            document.querySelectorAll('input[name="role"]').forEach(i => {
                i.parentElement.style.borderColor = 'var(--border-light)';
                i.parentElement.querySelector('div').style.color = 'var(--text-muted)';
                i.parentElement.querySelector('div:last-child').style.color = 'var(--text-muted)';
            });
            this.parentElement.style.borderColor = 'var(--primary)';
            this.parentElement.querySelector('div').style.color = 'var(--primary)';
            this.parentElement.querySelector('div:last-child').style.color = 'var(--text-main)';
        });
    });
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
