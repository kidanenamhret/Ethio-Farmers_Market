<?php
// public/admin/manage-users.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

$query = "SELECT * FROM users WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (full_name LIKE ? OR email LIKE ? OR username LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();

// Get role counts for stats
$counts = [
    'admin' => 0,
    'farmer' => 0,
    'customer' => 0
];
foreach ($users as $u) {
    if (isset($counts[$u['role']])) $counts[$u['role']]++;
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div style="max-width: 1400px; margin: 0 auto; padding: 2rem;" class="animate-fade-up">
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
        <div>
            <h1 style="font-size: 3.5rem; letter-spacing: -2px; margin-bottom: 0.5rem;">Manage <span style="color: var(--primary);">Users</span></h1>
            <p style="color: var(--text-muted); font-weight: 600; font-size: 1.1rem;">Overview of all marketplace participants</p>
        </div>
        <a href="add-user.php" class="btn btn-primary" style="padding: 12px 25px; border-radius: 14px; box-shadow: 0 10px 20px -5px rgba(27, 94, 32, 0.3); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-user-plus"></i> Add New User
        </a>
    </div>

    <!-- Role Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; margin-bottom: 4rem;">
        <div class="premium-card" style="padding: 2rem; background: white; border: 1px solid var(--border-light); display: flex; align-items: center; gap: 25px;">
            <div style="width: 60px; height: 60px; background: rgba(27, 94, 32, 0.1); color: var(--primary); border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-tractor"></i>
            </div>
            <div>
                <div style="font-size: 2rem; font-weight: 900; color: var(--text-main); line-height: 1;"><?php echo $counts['farmer']; ?></div>
                <div style="font-size: 0.8rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-top: 5px;">Total Farmers</div>
            </div>
        </div>

        <div class="premium-card" style="padding: 2rem; background: white; border: 1px solid var(--border-light); display: flex; align-items: center; gap: 25px;">
            <div style="width: 60px; height: 60px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div>
                <div style="font-size: 2rem; font-weight: 900; color: var(--text-main); line-height: 1;"><?php echo $counts['customer']; ?></div>
                <div style="font-size: 0.8rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-top: 5px;">Active Customers</div>
            </div>
        </div>

        <div class="premium-card" style="padding: 2rem; background: white; border: 1px solid var(--border-light); display: flex; align-items: center; gap: 25px;">
            <div style="width: 60px; height: 60px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <div style="font-size: 2rem; font-weight: 900; color: var(--text-main); line-height: 1;"><?php echo $counts['admin']; ?></div>
                <div style="font-size: 0.8rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-top: 5px;">Administrators</div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="premium-card" style="padding: 0; border-radius: 35px; overflow: hidden;">
        <div style="padding: 2rem 2.5rem; border-bottom: 1px solid var(--border-light); background: var(--bg-main); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.4rem; letter-spacing: -0.5px;">All Registered Users</h3>
            <form method="GET" style="position: relative; width: 300px;">
                <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.9rem;"></i>
                <input type="text" name="search" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($search); ?>" 
                       style="width: 100%; padding: 10px 15px 10px 40px; border-radius: 12px; border: 1px solid var(--border-light); outline: none; font-family: inherit; font-size: 0.9rem;">
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; color: var(--text-muted); border-bottom: 1px solid var(--border-light);">
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">User</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Contact</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Role</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800;">Joined</th>
                        <th style="padding: 1.5rem 2.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): 
                        $role_color = match($user['role']) {
                            'admin' => '#ef4444',
                            'farmer' => '#10b981',
                            'customer' => '#3b82f6',
                            default => 'var(--text-muted)'
                        };
                    ?>
                        <tr style="border-bottom: 1px solid var(--border-light); transition: var(--transition);" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 2rem 2.5rem;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="width: 45px; height: 45px; background: <?php echo $role_color; ?>15; color: <?php echo $role_color; ?>; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem;">
                                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; color: var(--text-main);"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700;">@<?php echo htmlspecialchars($user['username'] ?: 'user_'.$user['id']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 2rem 2.5rem;">
                                <div style="font-weight: 700; color: var(--text-main); font-size: 0.95rem;"><?php echo htmlspecialchars($user['email']); ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;"><?php echo htmlspecialchars($user['phone'] ?: 'No phone'); ?></div>
                            </td>
                            <td style="padding: 2rem 2.5rem;">
                                <span style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 50px; background: <?php echo $role_color; ?>15; color: <?php echo $role_color; ?>; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                                    <div style="width: 6px; height: 6px; border-radius: 50%; background: <?php echo $role_color; ?>;"></div>
                                    <?php echo $user['role']; ?>
                                </span>
                            </td>
                            <td style="padding: 2rem 2.5rem;">
                                <div style="font-weight: 600; color: var(--text-muted); font-size: 0.9rem;">
                                    <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                </div>
                            </td>
                            <td style="padding: 2rem 2.5rem; text-align: right;">
                                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                    <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="btn glass" style="width: 40px; height: 40px; padding: 0; border-radius: 12px; border: 1px solid var(--border-light); color: var(--primary); display: flex; align-items: center; justify-content: center; text-decoration: none;"><i class="fas fa-edit"></i></a>
                                    <a href="delete-user.php?id=<?php echo $user['id']; ?>" class="btn glass" style="width: 40px; height: 40px; padding: 0; border-radius: 12px; border: 1px solid var(--border-light); color: var(--accent); display: flex; align-items: center; justify-content: center; text-decoration: none;" onclick="return confirm('Are you sure you want to remove this user from the platform?')"><i class="fas fa-trash-can"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
