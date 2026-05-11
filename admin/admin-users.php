<?php
require_once 'auth.php';
require_once '../config/database.php';
$db   = new Database();
$conn = $db->getConnection();

// Only super_admin can manage users
if ($admin_user['role'] !== 'super_admin') {
    // Still allow viewing own profile
}

$success = $error = '';
$edit_id = (int)($_GET['edit'] ?? 0);
$edit    = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id        = (int)($_POST['id'] ?? 0);
        $username  = trim($_POST['username'] ?? '');
        $full_name = trim($_POST['full_name'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $role      = $_POST['role'] ?? 'editor';
        $status    = $_POST['status'] ?? 'active';
        $password  = $_POST['password'] ?? '';

        if (empty($username) || empty($full_name)) {
            $error = 'Username and full name are required.';
        } else {
            try {
                if ($id) {
                    if (!empty($password)) {
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $conn->prepare("UPDATE admin_users SET username=?,full_name=?,email=?,role=?,status=?,password=?,updated_at=NOW() WHERE id=?")
                             ->execute([$username,$full_name,$email,$role,$status,$hash,$id]);
                    } else {
                        $conn->prepare("UPDATE admin_users SET username=?,full_name=?,email=?,role=?,status=?,updated_at=NOW() WHERE id=?")
                             ->execute([$username,$full_name,$email,$role,$status,$id]);
                    }
                    $success = 'User updated.';
                } else {
                    if (empty($password)) { $error = 'Password is required for new users.'; }
                    else {
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $conn->prepare("INSERT INTO admin_users (username,full_name,email,role,status,password,created_at) VALUES (?,?,?,?,?,?,NOW())")
                             ->execute([$username,$full_name,$email,$role,$status,$hash]);
                        $success = 'Admin user created.';
                        $edit_id = 0;
                    }
                }
            } catch(Exception $e) {
                $error = str_contains($e->getMessage(), 'Duplicate') ? 'Username already exists.' : 'Database error.';
            }
        }
    } elseif ($action === 'toggle_status') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id !== $admin_user['id']) {
            $cur = $conn->prepare("SELECT status FROM admin_users WHERE id=?");
            $cur->execute([$id]);
            $new_status = $cur->fetchColumn() === 'active' ? 'inactive' : 'active';
            $conn->prepare("UPDATE admin_users SET status=? WHERE id=?")->execute([$new_status, $id]);
            $success = 'User status updated.';
        } else {
            $error = 'You cannot deactivate your own account.';
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id === $admin_user['id']) {
            $error = 'You cannot delete your own account.';
        } else {
            $conn->prepare("DELETE FROM admin_users WHERE id=?")->execute([$id]);
            $success = 'User deleted.';
        }
    }
}

if ($edit_id) {
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE id=?");
    $stmt->execute([$edit_id]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

$users = $conn->query("SELECT * FROM admin_users ORDER BY role ASC, full_name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Users - RETI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <i class="fas fa-graduation-cap"></i>
        <div><div class="sidebar-logo-title">RETI Admin</div><div class="sidebar-logo-sub">Rising East Training</div></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="dashboard.php" class="nav-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="courses.php" class="nav-item"><i class="fas fa-book-open"></i> Courses</a>
        <a href="news.php" class="nav-item"><i class="fas fa-newspaper"></i> News &amp; Events</a>
        <div class="nav-section-label">Enquiries</div>
        <a href="messages.php" class="nav-item"><i class="fas fa-envelope"></i> Messages</a>
        <a href="enrollments.php" class="nav-item"><i class="fas fa-user-graduate"></i> Enrollments</a>
        <div class="nav-section-label">Content</div>
        <a href="testimonials.php" class="nav-item"><i class="fas fa-quote-right"></i> Testimonials</a>
        <a href="gallery.php" class="nav-item"><i class="fas fa-images"></i> Gallery</a>
        <div class="nav-section-label">System</div>
        <a href="settings.php" class="nav-item"><i class="fas fa-cog"></i> Settings</a>
        <a href="admin-users.php" class="nav-item active"><i class="fas fa-users-cog"></i> Admin Users</a>
    </nav>
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar"><?= strtoupper(substr($admin_user['full_name'],0,1)) ?></div>
            <div><div class="sidebar-user-name"><?= htmlspecialchars($admin_user['full_name']) ?></div><div class="sidebar-user-role"><?= ucfirst(str_replace('_',' ',$admin_user['role'])) ?></div></div>
        </div>
        <a href="logout.php" class="btn-logout" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
    </div>
</aside>

<div class="main-wrap">
    <header class="top-header">
        <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <div class="header-title"><h1>Admin Users</h1><p>Manage admin panel access</p></div>
        <div class="header-actions">
            <?php if ($admin_user['role'] === 'super_admin'): ?>
            <a href="admin-users.php?add=1" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add User</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="page-content">

        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>

        <?php if (($edit || isset($_GET['add'])) && $admin_user['role'] === 'super_admin'): ?>
        <div class="section-card" style="margin-bottom:2rem;">
            <div class="section-card-header">
                <h2><i class="fas fa-<?= $edit ? 'edit' : 'user-plus' ?>"></i> <?= $edit ? 'Edit User' : 'Add Admin User' ?></h2>
                <a href="admin-users.php" class="btn btn-light btn-sm"><i class="fas fa-times"></i> Cancel</a>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?= $edit ? $edit['id'] : 0 ?>">
                    <div class="form-grid form-grid-2" style="margin-bottom:1.25rem;">
                        <div class="form-group">
                            <label>Full Name <span class="req">*</span></label>
                            <input type="text" name="full_name" class="form-control" required value="<?= htmlspecialchars($edit['full_name'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Username <span class="req">*</span></label>
                            <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($edit['username'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($edit['email'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Password <?= $edit ? '(leave blank to keep current)' : '<span class="req">*</span>' ?></label>
                            <input type="password" name="password" class="form-control" <?= $edit ? '' : 'required' ?> placeholder="<?= $edit ? 'Leave blank to keep current' : 'Set password' ?>">
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="editor" <?= ($edit['role'] ?? '') === 'editor' ? 'selected' : '' ?>>Editor</option>
                                <option value="admin" <?= ($edit['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="super_admin" <?= ($edit['role'] ?? '') === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active" <?= ($edit['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= ($edit['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $edit ? 'Update User' : 'Create User' ?></button>
                    <a href="admin-users.php" class="btn btn-light" style="margin-left:.5rem;">Cancel</a>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="section-card">
            <div class="section-card-header"><h2><i class="fas fa-users-cog"></i> All Admin Users (<?= count($users) ?>)</h2></div>
            <div class="table-responsive">
                <table>
                    <thead><tr><th>User</th><th>Username</th><th>Role</th><th>Status</th><th>Last Login</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr <?= $u['id'] === $admin_user['id'] ? 'style="background:#f0fdf4;"' : '' ?>>
                            <td>
                                <div style="display:flex;align-items:center;gap:.75rem;">
                                    <div style="width:36px;height:36px;border-radius:50%;background:var(--primary);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;"><?= strtoupper(substr($u['full_name'],0,1)) ?></div>
                                    <div>
                                        <div class="td-title"><?= htmlspecialchars($u['full_name']) ?> <?= $u['id']===$admin_user['id']?'<span style="font-size:.7rem;color:var(--primary);">(you)</span>':'' ?></div>
                                        <?php if ($u['email']): ?><div class="td-sub"><?= htmlspecialchars($u['email']) ?></div><?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td style="font-weight:600;"><?= htmlspecialchars($u['username']) ?></td>
                            <td>
                                <?php
                                $role_colors = ['super_admin'=>'background:#f3e8ff;color:#7c3aed;', 'admin'=>'background:#dbeafe;color:#1e40af;', 'editor'=>'background:#f1f5f9;color:#475569;'];
                                $role_style  = $role_colors[$u['role']] ?? '';
                                ?>
                                <span class="badge" style="<?= $role_style ?>"><?= ucfirst(str_replace('_',' ',$u['role'])) ?></span>
                            </td>
                            <td><span class="badge <?= $u['status'] === 'active' ? 'active' : 'inactive' ?>"><?= ucfirst($u['status']) ?></span></td>
                            <td style="font-size:.8rem;color:var(--text-light);"><?= $u['last_login'] ? date('d M Y, H:i', strtotime($u['last_login'])) : 'Never' ?></td>
                            <td>
                                <?php if ($admin_user['role'] === 'super_admin'): ?>
                                <div class="td-actions">
                                    <a href="admin-users.php?edit=<?= $u['id'] ?>" class="btn btn-icon btn-outline btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <?php if ($u['id'] !== $admin_user['id']): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="toggle_status"><input type="hidden" name="id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="btn btn-icon btn-sm <?= $u['status']==='active'?'btn-warning':'btn-success' ?>" title="Toggle status"><i class="fas fa-<?= $u['status']==='active'?'ban':'check' ?>"></i></button>
                                    </form>
                                    <button class="btn btn-icon btn-outline-danger btn-sm" onclick="confirmDelete(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['full_name'])) ?>')"><i class="fas fa-trash"></i></button>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="alert alert-info" style="margin-top:1.5rem;">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Role Permissions:</strong><br>
                <strong>Super Admin</strong> — full access including user management &nbsp;|&nbsp;
                <strong>Admin</strong> — manage all content, cannot manage users &nbsp;|&nbsp;
                <strong>Editor</strong> — manage courses and news only
            </div>
        </div>

    </main>
</div>

<div class="modal-backdrop" id="deleteModal">
    <div class="modal">
        <div class="modal-header"><div class="modal-icon-danger"><i class="fas fa-trash"></i></div><div><h3>Delete Admin User</h3><p id="deleteModalMsg"></p></div></div>
        <div class="modal-footer">
            <button class="btn btn-light" onclick="closeModal()">Cancel</button>
            <form method="POST"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" id="deleteId">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
document.getElementById('sidebarToggle').addEventListener('click',()=>{ sidebar.classList.toggle('open'); overlay.classList.toggle('show'); });
overlay.addEventListener('click',()=>{ sidebar.classList.remove('open'); overlay.classList.remove('show'); });
function confirmDelete(id, name) {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteModalMsg').textContent = `Delete admin user "${name}"? They will lose all access.`;
    document.getElementById('deleteModal').classList.add('open');
}
function closeModal() { document.getElementById('deleteModal').classList.remove('open'); }
document.getElementById('deleteModal').addEventListener('click',function(e){ if(e.target===this)closeModal(); });
</script>
</body>
</html>
