<?php
require_once 'auth.php';
require_once '../config/database.php';
$db   = new Database();
$conn = $db->getConnection();

$success = $error = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        try {
            $stmt = $conn->prepare("UPDATE courses SET is_active = NOT is_active WHERE id=?");
            $stmt->execute([$id]);
            $success = 'Course status updated.';
        } catch(Exception $e) { $error = 'Failed to update status.'; }

    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        try {
            $conn->prepare("DELETE FROM courses WHERE id=?")->execute([$id]);
            $success = 'Course deleted successfully.';
        } catch(Exception $e) { $error = 'Failed to delete course.'; }
    }
}

// Filters
$filter_cat = $_GET['category'] ?? '';
$search     = trim($_GET['search'] ?? '');
$page       = max(1, (int)($_GET['page'] ?? 1));
$per_page   = 12;

$where  = [];
$params = [];
if ($filter_cat) { $where[] = 'category = ?'; $params[] = $filter_cat; }
if ($search)     { $where[] = '(title LIKE ? OR short_description LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Total
$total = $conn->prepare("SELECT COUNT(*) FROM courses $where_sql");
$total->execute($params);
$total_count = $total->fetchColumn();
$total_pages = max(1, ceil($total_count / $per_page));
$offset = ($page - 1) * $per_page;

// Courses
$stmt = $conn->prepare("SELECT * FROM courses $where_sql ORDER BY sort_order ASC, title ASC LIMIT $per_page OFFSET $offset");
$stmt->execute($params);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$cat_labels = [
    'level3'       => 'Level III',
    'skills_award' => 'Skills Award',
    'short_course' => 'Short Course',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Courses - RETI Admin</title>
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
        <a href="courses.php" class="nav-item active"><i class="fas fa-book-open"></i> Courses</a>
        <a href="news.php" class="nav-item"><i class="fas fa-newspaper"></i> News &amp; Events</a>
        <div class="nav-section-label">Enquiries</div>
        <a href="messages.php" class="nav-item"><i class="fas fa-envelope"></i> Messages</a>
        <a href="enrollments.php" class="nav-item"><i class="fas fa-user-graduate"></i> Enrollments</a>
        <div class="nav-section-label">Content</div>
        <a href="testimonials.php" class="nav-item"><i class="fas fa-quote-right"></i> Testimonials</a>
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
        <div class="header-title"><h1>Courses</h1><p>Manage all training programs and courses</p></div>
        <div class="header-actions">
            <a href="course-add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Course</a>
        </div>
    </header>

    <main class="page-content">

        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>

        <!-- Filters -->
        <div class="section-card" style="margin-bottom:1.5rem;">
            <div style="padding:1.1rem 1.5rem;">
                <form method="GET" class="table-toolbar" style="flex-wrap:wrap;">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Search courses…" value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <select name="category" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php foreach ($cat_labels as $val => $label): ?>
                        <option value="<?= $val ?>" <?= $filter_cat===$val?'selected':'' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Search</button>
                    <?php if ($search || $filter_cat): ?>
                    <a href="courses.php" class="btn btn-light btn-sm"><i class="fas fa-times"></i> Clear</a>
                    <?php endif; ?>
                    <span class="ms-auto" style="margin-left:auto;font-size:.825rem;color:var(--text-light);"><?= $total_count ?> course<?= $total_count!=1?'s':'' ?></span>
                </form>
            </div>
        </div>

        <!-- Courses Grid -->
        <?php if (empty($courses)): ?>
        <div class="section-card">
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <h3>No courses found</h3>
                <p><?= $search || $filter_cat ? 'Try adjusting your search or filters.' : 'Start by adding your first course.' ?></p>
                <a href="course-add.php" class="btn btn-primary" style="margin-top:1rem;"><i class="fas fa-plus"></i> Add Course</a>
            </div>
        </div>
        <?php else: ?>
        <div class="courses-admin-grid">
            <?php foreach ($courses as $c):
                $cat = $c['category'];
                $cat_label = $cat_labels[$cat] ?? $cat;
                $badge_class = $cat === 'level3' ? 'level3' : ($cat === 'skills_award' ? 'skills_award' : 'short_course');
            ?>
            <div class="course-admin-card <?= $c['is_active'] ? '' : 'inactive' ?>">
                <?php if ($c['image_url']): ?>
                <div class="course-admin-img">
                    <img src="../<?= htmlspecialchars($c['image_url']) ?>" alt="<?= htmlspecialchars($c['title']) ?>">
                    <div class="course-admin-img-overlay"></div>
                </div>
                <?php else: ?>
                <div class="course-admin-img no-img"><i class="fas fa-book-open"></i></div>
                <?php endif; ?>
                <div class="course-admin-body">
                    <div class="course-admin-top">
                        <span class="badge <?= $badge_class ?>"><?= $cat_label ?></span>
                        <span class="badge <?= $c['is_active'] ? 'active' : 'inactive' ?>"><?= $c['is_active'] ? 'Active' : 'Inactive' ?></span>
                    </div>
                    <h3 class="course-admin-title"><?= htmlspecialchars($c['title']) ?></h3>
                    <?php if ($c['short_description']): ?>
                    <p class="course-admin-desc"><?= htmlspecialchars(substr($c['short_description'],0,100)) ?>…</p>
                    <?php endif; ?>
                    <div class="course-admin-meta">
                        <?php if ($c['duration']): ?><span><i class="fas fa-clock"></i> <?= htmlspecialchars($c['duration']) ?></span><?php endif; ?>
                        <?php if ($c['fee']): ?><span><i class="fas fa-tag"></i> K<?= number_format($c['fee']) ?></span><?php endif; ?>
                    </div>
                    <div class="course-admin-actions">
                        <a href="course-edit.php?id=<?= $c['id'] ?>" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                            <button type="submit" class="btn btn-sm <?= $c['is_active'] ? 'btn-warning' : 'btn-success' ?>">
                                <i class="fas fa-<?= $c['is_active'] ? 'eye-slash' : 'eye' ?>"></i>
                                <?= $c['is_active'] ? 'Hide' : 'Show' ?>
                            </button>
                        </form>
                        <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['title']), ENT_QUOTES) ?>')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div style="display:flex;justify-content:center;gap:.5rem;margin-top:2rem;">
            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
            <a href="?page=<?= $p ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($filter_cat) ?>"
               class="page-btn <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>

    </main>
</div>

<!-- Delete confirmation modal -->
<div class="modal-backdrop" id="deleteModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-icon-danger"><i class="fas fa-trash"></i></div>
            <div><h3>Delete Course</h3><p id="deleteModalMsg">Are you sure you want to delete this course? This cannot be undone.</p></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-light" onclick="closeModal()">Cancel</button>
            <form method="POST" id="deleteForm">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteId">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<style>
.courses-admin-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:1.25rem; }
.course-admin-card { background:white; border-radius:14px; border:1px solid var(--border); overflow:hidden; transition:all .25s; display:flex; flex-direction:column; }
.course-admin-card:hover { box-shadow:0 8px 24px rgba(0,0,0,.09); transform:translateY(-2px); }
.course-admin-card.inactive { opacity:.7; }
.course-admin-img { height:150px; overflow:hidden; position:relative; background:var(--content-bg); }
.course-admin-img img { width:100%; height:100%; object-fit:cover; }
.course-admin-img.no-img { display:flex; align-items:center; justify-content:center; font-size:2.5rem; color:var(--border); }
.course-admin-img-overlay { position:absolute; inset:0; background:linear-gradient(to bottom,transparent 50%,rgba(0,0,0,.3)); }
.course-admin-body { padding:1.1rem; flex:1; display:flex; flex-direction:column; }
.course-admin-top { display:flex; gap:.4rem; flex-wrap:wrap; margin-bottom:.6rem; }
.course-admin-title { font-size:.95rem; font-weight:700; color:var(--text-dark); margin-bottom:.4rem; line-height:1.35; }
.course-admin-desc { font-size:.78rem; color:var(--text-light); flex:1; }
.course-admin-meta { display:flex; gap:.75rem; font-size:.78rem; color:var(--text-lighter); margin:.6rem 0; flex-wrap:wrap; }
.course-admin-meta i { color:var(--primary); }
.course-admin-actions { display:flex; gap:.4rem; flex-wrap:wrap; margin-top:.75rem; padding-top:.75rem; border-top:1px solid var(--border); }
.badge.level3       { background:#dbeafe; color:#1e40af; }
.badge.skills_award { background:#dcfce7; color:#166534; }
.badge.short_course { background:#fef3c7; color:#92400e; }
</style>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
document.getElementById('sidebarToggle').addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('show'); });
overlay.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('show'); });

function confirmDelete(id, title) {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteModalMsg').textContent = `Delete "${title}"? This cannot be undone.`;
    document.getElementById('deleteModal').classList.add('open');
}
function closeModal() { document.getElementById('deleteModal').classList.remove('open'); }
document.getElementById('deleteModal').addEventListener('click', function(e){ if(e.target===this)closeModal(); });
</script>
</body>
</html>
