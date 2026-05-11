<?php
require_once 'auth.php';
require_once '../config/database.php';
$db   = new Database();
$conn = $db->getConnection();

$success = $error = '';
$edit_id = (int)($_GET['edit'] ?? 0);
$edit    = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id              = (int)($_POST['id'] ?? 0);
        $name            = trim($_POST['name'] ?? '');
        $course          = trim($_POST['course'] ?? '');
        $quote           = trim($_POST['quote'] ?? '');
        $graduation_year = trim($_POST['graduation_year'] ?? '');
        $employer        = trim($_POST['current_employer'] ?? '');
        $photo           = trim($_POST['photo_url'] ?? '');
        $rating          = (int)($_POST['rating'] ?? 5);
        $is_active       = isset($_POST['is_active']) ? 1 : 0;
        $sort_order      = (int)($_POST['sort_order'] ?? 0);

        if (empty($name) || empty($quote)) {
            $error = 'Name and quote are required.';
        } else {
            try {
                if ($id) {
                    $conn->prepare("UPDATE testimonials SET name=?,course=?,quote=?,graduation_year=?,current_employer=?,photo_url=?,rating=?,is_active=?,sort_order=? WHERE id=?")
                         ->execute([$name,$course,$quote,$graduation_year,$employer,$photo,$rating,$is_active,$sort_order,$id]);
                    $success = 'Testimonial updated.';
                } else {
                    $conn->prepare("INSERT INTO testimonials (name,course,quote,graduation_year,current_employer,photo_url,rating,is_active,sort_order,created_at) VALUES (?,?,?,?,?,?,?,?,?,NOW())")
                         ->execute([$name,$course,$quote,$graduation_year,$employer,$photo,$rating,$is_active,$sort_order]);
                    $success = 'Testimonial added.';
                }
                $edit_id = 0;
            } catch(Exception $e) { $error = 'Database error: ' . $e->getMessage(); }
        }
    } elseif ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        $conn->prepare("UPDATE testimonials SET is_active = NOT is_active WHERE id=?")->execute([$id]);
        $success = 'Status updated.';
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $conn->prepare("DELETE FROM testimonials WHERE id=?")->execute([$id]);
        $success = 'Testimonial deleted.';
    }
}

if ($edit_id) {
    $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id=?");
    $stmt->execute([$edit_id]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY sort_order ASC, created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Testimonials - RETI Admin</title>
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
        <a href="testimonials.php" class="nav-item active"><i class="fas fa-quote-right"></i> Testimonials</a>
        <a href="gallery.php" class="nav-item"><i class="fas fa-images"></i> Gallery</a>
        <div class="nav-section-label">System</div>
        <a href="settings.php" class="nav-item"><i class="fas fa-cog"></i> Settings</a>
        <a href="admin-users.php" class="nav-item"><i class="fas fa-users-cog"></i> Admin Users</a>
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
        <div class="header-title"><h1>Testimonials</h1><p>Manage student success stories</p></div>
        <div class="header-actions">
            <a href="testimonials.php?add=1" class="btn btn-primary"><i class="fas fa-plus"></i> Add Testimonial</a>
        </div>
    </header>

    <main class="page-content">

        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>

        <?php if ($edit || isset($_GET['add'])): ?>
        <!-- Add/Edit Form -->
        <div class="section-card" style="margin-bottom:2rem;">
            <div class="section-card-header">
                <h2><i class="fas fa-<?= $edit ? 'edit' : 'plus' ?>"></i> <?= $edit ? 'Edit Testimonial' : 'Add New Testimonial' ?></h2>
                <a href="testimonials.php" class="btn btn-light btn-sm"><i class="fas fa-times"></i> Cancel</a>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?= $edit ? $edit['id'] : 0 ?>">
                    <div class="form-grid form-grid-2" style="margin-bottom:1.25rem;">
                        <div class="form-group">
                            <label>Graduate Name <span class="req">*</span></label>
                            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($edit['name'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Course Completed</label>
                            <input type="text" name="course" class="form-control" value="<?= htmlspecialchars($edit['course'] ?? '') ?>" placeholder="e.g., Computer Applications">
                        </div>
                        <div class="form-group">
                            <label>Graduation Year</label>
                            <input type="text" name="graduation_year" class="form-control" value="<?= htmlspecialchars($edit['graduation_year'] ?? '') ?>" placeholder="e.g., 2024">
                        </div>
                        <div class="form-group">
                            <label>Current Employer / Position</label>
                            <input type="text" name="current_employer" class="form-control" value="<?= htmlspecialchars($edit['current_employer'] ?? '') ?>" placeholder="e.g., Bank of Zambia">
                        </div>
                        <div class="form-group">
                            <label>Photo URL</label>
                            <input type="text" name="photo_url" class="form-control" value="<?= htmlspecialchars($edit['photo_url'] ?? '') ?>" placeholder="assets/images/testimonials/photo.jpg">
                        </div>
                        <div class="form-group">
                            <label>Rating (1–5)</label>
                            <select name="rating" class="form-control">
                                <?php for ($r = 5; $r >= 1; $r--): ?>
                                <option value="<?= $r ?>" <?= ($edit['rating'] ?? 5) == $r ? 'selected' : '' ?>><?= $r ?> Star<?= $r>1?'s':'' ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom:1.25rem;">
                        <label>Quote / Testimonial <span class="req">*</span></label>
                        <textarea name="quote" class="form-control" rows="3" required><?= htmlspecialchars($edit['quote'] ?? '') ?></textarea>
                    </div>
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" min="0" value="<?= (int)($edit['sort_order'] ?? 0) ?>">
                        </div>
                        <div class="form-group" style="justify-content:flex-end;padding-top:1.6rem;">
                            <label style="display:flex;align-items:center;gap:.5rem;font-weight:600;cursor:pointer;">
                                <input type="checkbox" name="is_active" value="1" <?= ($edit['is_active'] ?? 1) ? 'checked' : '' ?>> Active
                            </label>
                        </div>
                    </div>
                    <div style="margin-top:1.25rem;display:flex;gap:.75rem;">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $edit ? 'Update' : 'Add' ?> Testimonial</button>
                        <a href="testimonials.php" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- List -->
        <div class="section-card">
            <div class="section-card-header"><h2><i class="fas fa-quote-right"></i> All Testimonials (<?= count($testimonials) ?>)</h2></div>
            <?php if (empty($testimonials)): ?>
            <div class="empty-state"><i class="fas fa-quote-right"></i><h3>No testimonials yet</h3><p>Add your first graduate testimonial.</p></div>
            <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead><tr><th>Graduate</th><th>Quote</th><th>Rating</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($testimonials as $t): ?>
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:.75rem;">
                                    <?php if ($t['photo_url']): ?>
                                    <img src="../<?= htmlspecialchars($t['photo_url']) ?>" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                                    <?php else: ?>
                                    <div style="width:40px;height:40px;border-radius:50%;background:var(--primary);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;"><?= strtoupper(substr($t['name'],0,1)) ?></div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="td-title"><?= htmlspecialchars($t['name']) ?></div>
                                        <div class="td-sub"><?= htmlspecialchars($t['course'] ?? '') ?><?= $t['graduation_year'] ? ' · ' . $t['graduation_year'] : '' ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="max-width:300px;"><div style="font-size:.825rem;color:var(--text-body);font-style:italic;">"<?= htmlspecialchars(substr($t['quote'],0,120)) ?>…"</div></td>
                            <td style="white-space:nowrap;color:#f59e0b;"><?= str_repeat('★', (int)($t['rating'] ?? 5)) ?></td>
                            <td><span class="badge <?= $t['is_active'] ? 'active' : 'inactive' ?>"><?= $t['is_active'] ? 'Active' : 'Hidden' ?></span></td>
                            <td>
                                <div class="td-actions">
                                    <a href="testimonials.php?edit=<?= $t['id'] ?>" class="btn btn-icon btn-outline btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= $t['id'] ?>">
                                        <button type="submit" class="btn btn-icon btn-sm <?= $t['is_active']?'btn-warning':'btn-success' ?>" title="Toggle"><i class="fas fa-<?= $t['is_active']?'eye-slash':'eye' ?>"></i></button>
                                    </form>
                                    <button class="btn btn-icon btn-outline-danger btn-sm" onclick="confirmDelete(<?= $t['id'] ?>, '<?= htmlspecialchars(addslashes($t['name'])) ?>')"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<div class="modal-backdrop" id="deleteModal">
    <div class="modal">
        <div class="modal-header"><div class="modal-icon-danger"><i class="fas fa-trash"></i></div><div><h3>Delete Testimonial</h3><p id="deleteModalMsg"></p></div></div>
        <div class="modal-footer">
            <button class="btn btn-light" onclick="closeModal()">Cancel</button>
            <form method="POST" id="deleteForm"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" id="deleteId">
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
    document.getElementById('deleteModalMsg').textContent = `Delete testimonial from "${name}"?`;
    document.getElementById('deleteModal').classList.add('open');
}
function closeModal() { document.getElementById('deleteModal').classList.remove('open'); }
document.getElementById('deleteModal').addEventListener('click',function(e){ if(e.target===this)closeModal(); });
</script>
</body>
</html>
