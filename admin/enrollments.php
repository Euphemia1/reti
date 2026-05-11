<?php
require_once 'auth.php';
require_once '../config/database.php';
$db   = new Database();
$conn = $db->getConnection();

$success = $error = '';
$view_id = (int)($_GET['view'] ?? 0);
$viewed  = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);

    if (in_array($action, ['accept','reject','pending'])) {
        $status_map = ['accept' => 'accepted', 'reject' => 'rejected', 'pending' => 'pending'];
        $new_status = $status_map[$action];
        $conn->prepare("UPDATE enrollment_requests SET status=?, updated_at=NOW() WHERE id=?")->execute([$new_status, $id]);
        $success = 'Enrollment request ' . $new_status . '.';
    } elseif ($action === 'delete') {
        $conn->prepare("DELETE FROM enrollment_requests WHERE id=?")->execute([$id]);
        $success = 'Enrollment request deleted.';
        $view_id = 0;
    } elseif ($action === 'save_notes') {
        $notes = trim($_POST['admin_notes'] ?? '');
        $conn->prepare("UPDATE enrollment_requests SET admin_notes=?, updated_at=NOW() WHERE id=?")->execute([$notes, $id]);
        $success = 'Notes saved.';
    }
}

if ($view_id) {
    $stmt = $conn->prepare("SELECT er.*, c.title as course_title, c.category as course_category, c.duration as course_duration FROM enrollment_requests er LEFT JOIN courses c ON er.course_id=c.id WHERE er.id=?");
    $stmt->execute([$view_id]);
    $viewed = $stmt->fetch(PDO::FETCH_ASSOC);
}

$filter_status = $_GET['status'] ?? '';
$search        = trim($_GET['search'] ?? '');
$page          = max(1, (int)($_GET['page'] ?? 1));
$per_page      = 20;

$where = []; $params = [];
if ($filter_status) { $where[] = 'er.status = ?'; $params[] = $filter_status; }
if ($search)        { $where[] = '(er.full_name LIKE ? OR er.email LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$total_count = $conn->prepare("SELECT COUNT(*) FROM enrollment_requests er $where_sql");
$total_count->execute($params);
$total_count = $total_count->fetchColumn();
$total_pages = max(1, ceil($total_count / $per_page));
$offset      = ($page - 1) * $per_page;

$stmt = $conn->prepare("SELECT er.*, c.title as course_title FROM enrollment_requests er LEFT JOIN courses c ON er.course_id=c.id $where_sql ORDER BY er.created_at DESC LIMIT $per_page OFFSET $offset");
$stmt->execute($params);
$enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Enrollments - RETI Admin</title>
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
        <a href="enrollments.php" class="nav-item active"><i class="fas fa-user-graduate"></i> Enrollments</a>
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
        <div class="header-title"><h1>Enrollment Requests</h1><p>Manage student enrollment applications</p></div>
    </header>

    <main class="page-content">

        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>

        <?php if ($viewed): ?>
        <!-- Detail view -->
        <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">
            <div class="section-card">
                <div class="section-card-header">
                    <h2><i class="fas fa-user-graduate"></i> <?= htmlspecialchars($viewed['full_name']) ?></h2>
                    <div style="display:flex;gap:.5rem;align-items:center;">
                        <span class="badge <?= $viewed['status'] === 'pending' ? 'pending' : ($viewed['status'] === 'accepted' ? 'active' : 'inactive') ?>"><?= ucfirst($viewed['status']) ?></span>
                        <a href="enrollments.php" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                </div>
                <div style="padding:1.5rem;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid var(--border);">
                        <div><div class="td-sub">Full Name</div><div style="font-weight:600;"><?= htmlspecialchars($viewed['full_name']) ?></div></div>
                        <div><div class="td-sub">Email</div><div><a href="mailto:<?= htmlspecialchars($viewed['email']) ?>" style="color:var(--primary);"><?= htmlspecialchars($viewed['email']) ?></a></div></div>
                        <?php if ($viewed['phone']): ?><div><div class="td-sub">Phone</div><div><?= htmlspecialchars($viewed['phone']) ?></div></div><?php endif; ?>
                        <?php if ($viewed['date_of_birth']): ?><div><div class="td-sub">Date of Birth</div><div><?= htmlspecialchars($viewed['date_of_birth']) ?></div></div><?php endif; ?>
                        <?php if ($viewed['education_level']): ?><div><div class="td-sub">Education Level</div><div><?= htmlspecialchars($viewed['education_level']) ?></div></div><?php endif; ?>
                        <div><div class="td-sub">Applied</div><div><?= date('d M Y, H:i', strtotime($viewed['created_at'])) ?></div></div>
                    </div>
                    <div style="margin-bottom:1.25rem;">
                        <div class="td-sub" style="margin-bottom:.25rem;">Applied Course</div>
                        <div style="font-size:1rem;font-weight:700;color:var(--primary);"><?= htmlspecialchars($viewed['course_title'] ?? 'N/A') ?></div>
                        <?php if ($viewed['course_duration']): ?>
                        <div class="td-sub">Duration: <?= htmlspecialchars($viewed['course_duration']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php if ($viewed['message']): ?>
                    <div>
                        <div class="td-sub" style="margin-bottom:.5rem;">Personal Statement / Message</div>
                        <div style="background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:1.25rem;line-height:1.7;white-space:pre-wrap;"><?= htmlspecialchars($viewed['message']) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <div class="section-card" style="margin-bottom:1.25rem;">
                    <div class="section-card-header"><h2><i class="fas fa-tasks"></i> Decision</h2></div>
                    <div style="padding:1.25rem;display:flex;flex-direction:column;gap:.6rem;">
                        <form method="POST"><input type="hidden" name="action" value="accept"><input type="hidden" name="id" value="<?= $viewed['id'] ?>"><input type="hidden" name="view" value="<?= $viewed['id'] ?>">
                            <button type="submit" class="btn btn-success" style="width:100%;" <?= $viewed['status']==='accepted'?'disabled':'' ?>><i class="fas fa-check"></i> Accept</button>
                        </form>
                        <form method="POST"><input type="hidden" name="action" value="reject"><input type="hidden" name="id" value="<?= $viewed['id'] ?>"><input type="hidden" name="view" value="<?= $viewed['id'] ?>">
                            <button type="submit" class="btn btn-danger" style="width:100%;" <?= $viewed['status']==='rejected'?'disabled':'' ?>><i class="fas fa-times"></i> Reject</button>
                        </form>
                        <?php if ($viewed['status'] !== 'pending'): ?>
                        <form method="POST"><input type="hidden" name="action" value="pending"><input type="hidden" name="id" value="<?= $viewed['id'] ?>"><input type="hidden" name="view" value="<?= $viewed['id'] ?>">
                            <button type="submit" class="btn btn-warning" style="width:100%;"><i class="fas fa-clock"></i> Reset to Pending</button>
                        </form>
                        <?php endif; ?>
                        <a href="mailto:<?= htmlspecialchars($viewed['email']) ?>" class="btn btn-outline" style="width:100%;"><i class="fas fa-envelope"></i> Email Applicant</a>
                        <button class="btn btn-outline-danger" style="width:100%;" onclick="document.getElementById('deleteEnr').classList.add('open')"><i class="fas fa-trash"></i> Delete</button>
                    </div>
                </div>
                <div class="section-card">
                    <div class="section-card-header"><h2><i class="fas fa-sticky-note"></i> Notes</h2></div>
                    <div style="padding:1.25rem;">
                        <form method="POST" action="?view=<?= $viewed['id'] ?>">
                            <input type="hidden" name="action" value="save_notes">
                            <input type="hidden" name="id" value="<?= $viewed['id'] ?>">
                            <textarea name="admin_notes" class="form-control" rows="4" placeholder="Internal notes…"><?= htmlspecialchars($viewed['admin_notes'] ?? '') ?></textarea>
                            <button type="submit" class="btn btn-primary btn-sm" style="margin-top:.75rem;width:100%;"><i class="fas fa-save"></i> Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-backdrop" id="deleteEnr">
            <div class="modal">
                <div class="modal-header"><div class="modal-icon-danger"><i class="fas fa-trash"></i></div><div><h3>Delete Enrollment</h3><p>Delete application from <?= htmlspecialchars($viewed['full_name']) ?>?</p></div></div>
                <div class="modal-footer">
                    <button class="btn btn-light" onclick="document.getElementById('deleteEnr').classList.remove('open')">Cancel</button>
                    <form method="POST"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $viewed['id'] ?>">
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                </div>
            </div>
        </div>

        <?php else: ?>
        <!-- List -->
        <div class="section-card">
            <div class="section-card-header">
                <h2><i class="fas fa-clipboard-list"></i> All Applications (<?= $total_count ?>)</h2>
                <form method="GET" class="table-toolbar">
                    <div class="search-box"><i class="fas fa-search"></i><input type="text" name="search" placeholder="Search name, email…" value="<?= htmlspecialchars($search) ?>"></div>
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" <?= $filter_status==='pending'?'selected':'' ?>>Pending</option>
                        <option value="accepted" <?= $filter_status==='accepted'?'selected':'' ?>>Accepted</option>
                        <option value="rejected" <?= $filter_status==='rejected'?'selected':'' ?>>Rejected</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                    <?php if ($search || $filter_status): ?><a href="enrollments.php" class="btn btn-light btn-sm"><i class="fas fa-times"></i></a><?php endif; ?>
                </form>
            </div>
            <?php if (empty($enrollments)): ?>
            <div class="empty-state"><i class="fas fa-user-graduate"></i><h3>No enrollment requests</h3><p>No applications have been submitted yet.</p></div>
            <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead><tr><th>Applicant</th><th>Course</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($enrollments as $e): ?>
                        <tr>
                            <td>
                                <div class="td-title"><?= htmlspecialchars($e['full_name']) ?></div>
                                <div class="td-sub"><?= htmlspecialchars($e['email']) ?></div>
                            </td>
                            <td><div class="td-title"><?= htmlspecialchars($e['course_title'] ?? 'N/A') ?></div></td>
                            <td>
                                <span class="badge <?= $e['status']==='pending'?'pending':($e['status']==='accepted'?'active':'inactive') ?>"><?= ucfirst($e['status']) ?></span>
                            </td>
                            <td style="white-space:nowrap;font-size:.8rem;color:var(--text-light);"><?= date('d M Y', strtotime($e['created_at'])) ?></td>
                            <td>
                                <div class="td-actions">
                                    <a href="enrollments.php?view=<?= $e['id'] ?>" class="btn btn-icon btn-outline btn-sm" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="mailto:<?= htmlspecialchars($e['email']) ?>" class="btn btn-icon btn-light btn-sm" title="Email"><i class="fas fa-envelope"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($total_pages > 1): ?>
            <div class="table-pagination">
                <div class="page-info">Showing <?= $offset+1 ?>–<?= min($offset+$per_page,$total_count) ?> of <?= $total_count ?></div>
                <div class="page-buttons">
                    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                    <a href="?page=<?= $p ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($filter_status) ?>" class="page-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
                    <?php endfor; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
document.getElementById('sidebarToggle').addEventListener('click',()=>{ sidebar.classList.toggle('open'); overlay.classList.toggle('show'); });
overlay.addEventListener('click',()=>{ sidebar.classList.remove('open'); overlay.classList.remove('show'); });
</script>
</body>
</html>
