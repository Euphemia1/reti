<?php
require_once 'auth.php';
require_once '../config/database.php';
$db   = new Database();
$conn = $db->getConnection();

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);

    if ($action === 'toggle_status') {
        try {
            $row = $conn->prepare("SELECT status FROM news WHERE id=?")->execute([$id]);
            $cur = $conn->prepare("SELECT status FROM news WHERE id=?");
            $cur->execute([$id]);
            $cur_status = $cur->fetchColumn();
            $new_status = $cur_status === 'published' ? 'draft' : 'published';
            $conn->prepare("UPDATE news SET status=? WHERE id=?")->execute([$new_status, $id]);
            $success = 'Article status changed to ' . $new_status . '.';
        } catch(Exception $e) { $error = 'Failed to update status.'; }
    } elseif ($action === 'delete') {
        try {
            $conn->prepare("DELETE FROM news WHERE id=?")->execute([$id]);
            $success = 'Article deleted.';
        } catch(Exception $e) { $error = 'Failed to delete article.'; }
    }
}

$filter_status = $_GET['status'] ?? '';
$search        = trim($_GET['search'] ?? '');
$page          = max(1, (int)($_GET['page'] ?? 1));
$per_page      = 15;

$where = []; $params = [];
if ($filter_status) { $where[] = 'status = ?'; $params[] = $filter_status; }
if ($search)        { $where[] = '(title LIKE ? OR excerpt LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$total_count = $conn->prepare("SELECT COUNT(*) FROM news $where_sql");
$total_count->execute($params);
$total_count = $total_count->fetchColumn();
$total_pages = max(1, ceil($total_count / $per_page));
$offset      = ($page - 1) * $per_page;

$stmt = $conn->prepare("SELECT * FROM news $where_sql ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
$stmt->execute($params);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>News - RETI Admin</title>
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
        <a href="news.php" class="nav-item active"><i class="fas fa-newspaper"></i> News &amp; Events</a>
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
        <div class="header-title"><h1>News &amp; Events</h1><p>Manage articles, announcements and events</p></div>
        <div class="header-actions">
            <a href="news-add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Write Article</a>
        </div>
    </header>

    <main class="page-content">

        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>

        <div class="section-card">
            <div class="section-card-header">
                <h2><i class="fas fa-newspaper"></i> All Articles <span style="font-weight:400;color:var(--text-lighter);">(<?= $total_count ?>)</span></h2>
                <form method="GET" class="table-toolbar">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Search articles…" value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="published" <?= $filter_status==='published'?'selected':'' ?>>Published</option>
                        <option value="draft" <?= $filter_status==='draft'?'selected':'' ?>>Draft</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                    <?php if ($search || $filter_status): ?>
                    <a href="news.php" class="btn btn-light btn-sm"><i class="fas fa-times"></i> Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <?php if (empty($articles)): ?>
            <div class="empty-state">
                <i class="fas fa-newspaper"></i>
                <h3>No articles found</h3>
                <p><?= $search || $filter_status ? 'Try adjusting your filters.' : 'Start by writing your first article.' ?></p>
                <a href="news-add.php" class="btn btn-primary" style="margin-top:1rem;"><i class="fas fa-plus"></i> Write Article</a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $a): ?>
                        <tr>
                            <td style="max-width:340px;">
                                <div style="display:flex;align-items:center;gap:.75rem;">
                                    <?php if ($a['image_url']): ?>
                                    <img src="../<?= htmlspecialchars($a['image_url']) ?>" class="table-img" alt="">
                                    <?php else: ?>
                                    <div class="table-img" style="background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#cbd5e1;font-size:1.1rem;"><i class="fas fa-newspaper"></i></div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="td-title"><?= htmlspecialchars($a['title']) ?></div>
                                        <?php if ($a['excerpt']): ?>
                                        <div class="td-sub"><?= htmlspecialchars(substr($a['excerpt'], 0, 80)) ?>…</div>
                                        <?php endif; ?>
                                        <?php if ($a['tags'] ?? ''): ?>
                                        <div class="td-sub" style="margin-top:3px;"><?php
                                            foreach (explode(',', $a['tags']) as $tag) {
                                                $tag = trim($tag);
                                                if ($tag) echo '<span style="background:#f1f5f9;color:#64748b;font-size:.7rem;padding:1px 6px;border-radius:4px;margin-right:3px;">' . htmlspecialchars($tag) . '</span>';
                                            }
                                        ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td style="white-space:nowrap;"><?= htmlspecialchars($a['author'] ?? 'RETI') ?></td>
                            <td>
                                <span class="badge <?= $a['status'] === 'published' ? 'published' : 'draft' ?>">
                                    <?= ucfirst($a['status']) ?>
                                </span>
                            </td>
                            <td><?= number_format($a['views'] ?? 0) ?></td>
                            <td style="white-space:nowrap;font-size:.8rem;color:var(--text-light);"><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                            <td>
                                <div class="td-actions">
                                    <a href="news-edit.php?id=<?= $a['id'] ?>" class="btn btn-icon btn-outline btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="toggle_status">
                                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                        <button type="submit" class="btn btn-icon btn-sm <?= $a['status']==='published'?'btn-warning':'btn-success' ?>"
                                                title="<?= $a['status']==='published'?'Set Draft':'Publish' ?>">
                                            <i class="fas fa-<?= $a['status']==='published'?'eye-slash':'check' ?>"></i>
                                        </button>
                                    </form>
                                    <a href="../news.php?id=<?= $a['id'] ?>" target="_blank" class="btn btn-icon btn-light btn-sm" title="Preview"><i class="fas fa-external-link-alt"></i></a>
                                    <button class="btn btn-icon btn-outline-danger btn-sm" title="Delete"
                                            onclick="confirmDelete(<?= $a['id'] ?>, '<?= htmlspecialchars(addslashes($a['title']), ENT_QUOTES) ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_pages > 1): ?>
            <div class="table-pagination">
                <div class="page-info">Showing <?= $offset+1 ?>–<?= min($offset+$per_page, $total_count) ?> of <?= $total_count ?></div>
                <div class="page-buttons">
                    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                    <a href="?page=<?= $p ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($filter_status) ?>"
                       class="page-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
                    <?php endfor; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>

    </main>
</div>

<!-- Delete Modal -->
<div class="modal-backdrop" id="deleteModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-icon-danger"><i class="fas fa-trash"></i></div>
            <div><h3>Delete Article</h3><p id="deleteModalMsg"></p></div>
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

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
document.getElementById('sidebarToggle').addEventListener('click',()=>{ sidebar.classList.toggle('open'); overlay.classList.toggle('show'); });
overlay.addEventListener('click',()=>{ sidebar.classList.remove('open'); overlay.classList.remove('show'); });

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
