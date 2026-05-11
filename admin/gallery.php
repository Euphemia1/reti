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
        $id          = (int)($_POST['id'] ?? 0);
        $title       = trim($_POST['title'] ?? '');
        $image_url   = trim($_POST['image_url'] ?? '');
        $caption     = trim($_POST['caption'] ?? '');
        $category    = trim($_POST['category'] ?? '');
        $is_active   = isset($_POST['is_active']) ? 1 : 0;
        $sort_order  = (int)($_POST['sort_order'] ?? 0);

        if (empty($image_url)) {
            $error = 'Image URL is required.';
        } else {
            try {
                if ($id) {
                    $conn->prepare("UPDATE gallery SET title=?,image_url=?,caption=?,category=?,is_active=?,sort_order=? WHERE id=?")
                         ->execute([$title,$image_url,$caption,$category,$is_active,$sort_order,$id]);
                    $success = 'Gallery item updated.';
                } else {
                    $conn->prepare("INSERT INTO gallery (title,image_url,caption,category,is_active,sort_order,created_at) VALUES (?,?,?,?,?,?,NOW())")
                         ->execute([$title,$image_url,$caption,$category,$is_active,$sort_order]);
                    $success = 'Gallery item added.';
                }
                $edit_id = 0;
            } catch(Exception $e) { $error = 'Database error.'; }
        }
    } elseif ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        $conn->prepare("UPDATE gallery SET is_active = NOT is_active WHERE id=?")->execute([$id]);
        $success = 'Status updated.';
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $conn->prepare("DELETE FROM gallery WHERE id=?")->execute([$id]);
        $success = 'Gallery item deleted.';
    }
}

if ($edit_id) {
    $stmt = $conn->prepare("SELECT * FROM gallery WHERE id=?");
    $stmt->execute([$edit_id]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

$gallery = $conn->query("SELECT * FROM gallery ORDER BY sort_order ASC, created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gallery - RETI Admin</title>
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
        <a href="gallery.php" class="nav-item active"><i class="fas fa-images"></i> Gallery</a>
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
        <div class="header-title"><h1>Gallery</h1><p>Manage campus photos and media</p></div>
        <div class="header-actions">
            <a href="gallery.php?add=1" class="btn btn-primary"><i class="fas fa-plus"></i> Add Photo</a>
        </div>
    </header>

    <main class="page-content">

        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>

        <?php if ($edit || isset($_GET['add'])): ?>
        <div class="section-card" style="margin-bottom:2rem;">
            <div class="section-card-header">
                <h2><i class="fas fa-<?= $edit ? 'edit' : 'plus' ?>"></i> <?= $edit ? 'Edit Photo' : 'Add Gallery Photo' ?></h2>
                <a href="gallery.php" class="btn btn-light btn-sm"><i class="fas fa-times"></i> Cancel</a>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?= $edit ? $edit['id'] : 0 ?>">
                    <div class="form-grid form-grid-2" style="margin-bottom:1.25rem;">
                        <div class="form-group">
                            <label>Image URL <span class="req">*</span></label>
                            <input type="text" name="image_url" class="form-control" required
                                   value="<?= htmlspecialchars($edit['image_url'] ?? '') ?>"
                                   placeholder="assets/images/gallery/photo.jpg"
                                   id="galleryImgUrl" oninput="previewImg(this.value)">
                        </div>
                        <div class="form-group">
                            <label>Title / Alt Text</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($edit['title'] ?? '') ?>" placeholder="e.g., Computer Lab">
                        </div>
                        <div class="form-group">
                            <label>Caption</label>
                            <input type="text" name="caption" class="form-control" value="<?= htmlspecialchars($edit['caption'] ?? '') ?>" placeholder="Short caption">
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($edit['category'] ?? '') ?>" placeholder="e.g., campus, events, students">
                        </div>
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" min="0" value="<?= (int)($edit['sort_order'] ?? 0) ?>">
                        </div>
                        <div class="form-group" style="justify-content:flex-end;padding-top:1.6rem;">
                            <label style="display:flex;align-items:center;gap:.5rem;font-weight:600;cursor:pointer;">
                                <input type="checkbox" name="is_active" value="1" <?= ($edit['is_active'] ?? 1) ? 'checked' : '' ?>> Active / Visible
                            </label>
                        </div>
                    </div>
                    <?php if ($edit && $edit['image_url']): ?>
                    <img id="galleryPreview" src="../<?= htmlspecialchars($edit['image_url']) ?>" class="img-preview" style="max-width:280px;margin-bottom:1rem;" alt="Preview">
                    <?php else: ?>
                    <img id="galleryPreview" class="img-preview" style="display:none;max-width:280px;margin-bottom:1rem;" alt="Preview">
                    <?php endif; ?>
                    <div style="display:flex;gap:.75rem;">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $edit ? 'Update' : 'Add' ?> Photo</button>
                        <a href="gallery.php" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Gallery grid -->
        <?php if (empty($gallery)): ?>
        <div class="section-card">
            <div class="empty-state"><i class="fas fa-images"></i><h3>No gallery photos</h3><p>Add your first campus photo.</p></div>
        </div>
        <?php else: ?>
        <div class="gallery-admin-grid">
            <?php foreach ($gallery as $g): ?>
            <div class="gallery-admin-item <?= $g['is_active'] ? '' : 'hidden-item' ?>">
                <div class="gallery-admin-img">
                    <img src="../<?= htmlspecialchars($g['image_url']) ?>" alt="<?= htmlspecialchars($g['title'] ?? '') ?>">
                    <?php if (!$g['is_active']): ?><div class="hidden-overlay"><i class="fas fa-eye-slash"></i> Hidden</div><?php endif; ?>
                </div>
                <div class="gallery-admin-info">
                    <div class="gallery-admin-title"><?= htmlspecialchars($g['title'] ?: '(No title)') ?></div>
                    <?php if ($g['caption']): ?><div class="gallery-admin-cap"><?= htmlspecialchars($g['caption']) ?></div><?php endif; ?>
                    <?php if ($g['category']): ?><span class="badge" style="background:#f1f5f9;color:#64748b;font-size:.68rem;"><?= htmlspecialchars($g['category']) ?></span><?php endif; ?>
                </div>
                <div class="gallery-admin-actions">
                    <a href="gallery.php?edit=<?= $g['id'] ?>" class="btn btn-outline btn-sm" style="flex:1;"><i class="fas fa-edit"></i> Edit</a>
                    <form method="POST" style="flex:1;">
                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= $g['id'] ?>">
                        <button type="submit" class="btn btn-sm <?= $g['is_active']?'btn-warning':'btn-success' ?>" style="width:100%;">
                            <i class="fas fa-<?= $g['is_active']?'eye-slash':'eye' ?>"></i> <?= $g['is_active']?'Hide':'Show' ?>
                        </button>
                    </form>
                    <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete(<?= $g['id'] ?>, '<?= htmlspecialchars(addslashes($g['title'] ?? 'this photo')) ?>')"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </main>
</div>

<div class="modal-backdrop" id="deleteModal">
    <div class="modal">
        <div class="modal-header"><div class="modal-icon-danger"><i class="fas fa-trash"></i></div><div><h3>Delete Photo</h3><p id="deleteModalMsg"></p></div></div>
        <div class="modal-footer">
            <button class="btn btn-light" onclick="closeModal()">Cancel</button>
            <form method="POST"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" id="deleteId">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<style>
.gallery-admin-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:1.25rem; }
.gallery-admin-item { background:white; border-radius:12px; border:1px solid var(--border); overflow:hidden; display:flex; flex-direction:column; transition:.25s; }
.gallery-admin-item:hover { box-shadow:0 6px 20px rgba(0,0,0,.08); }
.gallery-admin-item.hidden-item { opacity:.7; }
.gallery-admin-img { height:160px; overflow:hidden; position:relative; background:#f1f5f9; }
.gallery-admin-img img { width:100%; height:100%; object-fit:cover; }
.hidden-overlay { position:absolute; inset:0; background:rgba(0,0,0,.5); display:flex; align-items:center; justify-content:center; color:white; font-size:.875rem; font-weight:600; gap:.4rem; }
.gallery-admin-info { padding:.75rem; flex:1; }
.gallery-admin-title { font-weight:600; font-size:.875rem; color:var(--text-dark); margin-bottom:.2rem; }
.gallery-admin-cap { font-size:.775rem; color:var(--text-light); margin-bottom:.35rem; }
.gallery-admin-actions { display:flex; gap:.4rem; padding:.6rem; border-top:1px solid var(--border); }
</style>
<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
document.getElementById('sidebarToggle').addEventListener('click',()=>{ sidebar.classList.toggle('open'); overlay.classList.toggle('show'); });
overlay.addEventListener('click',()=>{ sidebar.classList.remove('open'); overlay.classList.remove('show'); });
function previewImg(src) {
    const img = document.getElementById('galleryPreview');
    if (src) { img.src = '../' + src; img.style.display='block'; }
    else img.style.display='none';
}
function confirmDelete(id, title) {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteModalMsg').textContent = `Delete "${title}"? This cannot be undone.`;
    document.getElementById('deleteModal').classList.add('open');
}
function closeModal() { document.getElementById('deleteModal').classList.remove('open'); }
document.getElementById('deleteModal').addEventListener('click',function(e){ if(e.target===this)closeModal(); });
</script>
</body>
</html>
