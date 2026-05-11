<?php
require_once 'auth.php';
require_once '../config/database.php';
$db   = new Database();
$conn = $db->getConnection();

$success = $error = '';

// Handle single message view and actions
$view_id = (int)($_GET['view'] ?? 0);
$viewed_msg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);

    if ($action === 'mark_read') {
        $conn->prepare("UPDATE contact_messages SET status='read' WHERE id=?")->execute([$id]);
        $success = 'Marked as read.';
    } elseif ($action === 'mark_replied') {
        $conn->prepare("UPDATE contact_messages SET status='replied' WHERE id=?")->execute([$id]);
        $success = 'Marked as replied.';
    } elseif ($action === 'delete') {
        $conn->prepare("DELETE FROM contact_messages WHERE id=?")->execute([$id]);
        $success = 'Message deleted.';
        $view_id = 0;
    } elseif ($action === 'save_notes') {
        $notes = trim($_POST['admin_notes'] ?? '');
        $conn->prepare("UPDATE contact_messages SET admin_notes=?, updated_at=NOW() WHERE id=?")->execute([$notes, $id]);
        $success = 'Notes saved.';
    }
}

if ($view_id) {
    $stmt = $conn->prepare("SELECT * FROM contact_messages WHERE id=?");
    $stmt->execute([$view_id]);
    $viewed_msg = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($viewed_msg && $viewed_msg['status'] === 'new') {
        $conn->prepare("UPDATE contact_messages SET status='read' WHERE id=?")->execute([$view_id]);
        $viewed_msg['status'] = 'read';
    }
}

// List
$filter_status = $_GET['status'] ?? '';
$search        = trim($_GET['search'] ?? '');
$page          = max(1, (int)($_GET['page'] ?? 1));
$per_page      = 20;

$where = []; $params = [];
if ($filter_status) { $where[] = 'status = ?'; $params[] = $filter_status; }
if ($search)        { $where[] = '(name LIKE ? OR email LIKE ? OR subject LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%"; }
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$total_count = $conn->prepare("SELECT COUNT(*) FROM contact_messages $where_sql");
$total_count->execute($params);
$total_count = $total_count->fetchColumn();
$total_pages = max(1, ceil($total_count / $per_page));
$offset      = ($page - 1) * $per_page;

$stmt = $conn->prepare("SELECT * FROM contact_messages $where_sql ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
$stmt->execute($params);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Messages - RETI Admin</title>
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
        <a href="messages.php" class="nav-item active"><i class="fas fa-envelope"></i> Messages</a>
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
        <div class="header-title"><h1>Contact Messages</h1><p>Enquiries and messages from the website</p></div>
    </header>

    <main class="page-content">

        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>

        <?php if ($viewed_msg): ?>
        <!-- Single message view -->
        <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start;">
            <div class="section-card">
                <div class="section-card-header">
                    <h2><i class="fas fa-envelope-open"></i> Message from <?= htmlspecialchars($viewed_msg['name']) ?></h2>
                    <a href="messages.php" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i> Back to list</a>
                </div>
                <div style="padding:1.5rem;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid var(--border);">
                        <div><div class="td-sub">From</div><div style="font-weight:600;"><?= htmlspecialchars($viewed_msg['name']) ?></div></div>
                        <div><div class="td-sub">Email</div><div><a href="mailto:<?= htmlspecialchars($viewed_msg['email']) ?>" style="color:var(--primary);"><?= htmlspecialchars($viewed_msg['email']) ?></a></div></div>
                        <?php if ($viewed_msg['phone']): ?><div><div class="td-sub">Phone</div><div><?= htmlspecialchars($viewed_msg['phone']) ?></div></div><?php endif; ?>
                        <?php if ($viewed_msg['course_interest']): ?><div><div class="td-sub">Course Interest</div><div style="color:var(--primary);font-weight:600;"><?= htmlspecialchars($viewed_msg['course_interest']) ?></div></div><?php endif; ?>
                        <div><div class="td-sub">Received</div><div><?= date('d M Y, H:i', strtotime($viewed_msg['created_at'])) ?></div></div>
                        <div><div class="td-sub">Status</div><div><span class="badge <?= $viewed_msg['status'] ?>"><?= ucfirst($viewed_msg['status']) ?></span></div></div>
                    </div>
                    <?php if ($viewed_msg['subject']): ?>
                    <div style="margin-bottom:1rem;"><div class="td-sub" style="margin-bottom:.25rem;">Subject</div><div style="font-size:1rem;font-weight:600;"><?= htmlspecialchars($viewed_msg['subject']) ?></div></div>
                    <?php endif; ?>
                    <div class="td-sub" style="margin-bottom:.5rem;">Message</div>
                    <div style="background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:1.25rem;line-height:1.7;white-space:pre-wrap;"><?= htmlspecialchars($viewed_msg['message']) ?></div>
                </div>
            </div>
            <div>
                <div class="section-card" style="margin-bottom:1.25rem;">
                    <div class="section-card-header"><h2><i class="fas fa-tasks"></i> Actions</h2></div>
                    <div style="padding:1.25rem;display:flex;flex-direction:column;gap:.6rem;">
                        <a href="mailto:<?= htmlspecialchars($viewed_msg['email']) ?>?subject=Re: <?= urlencode($viewed_msg['subject'] ?: 'Your enquiry') ?>"
                           class="btn btn-primary" style="width:100%;"><i class="fas fa-reply"></i> Reply by Email</a>
                        <?php if ($viewed_msg['status'] !== 'replied'): ?>
                        <form method="POST"><input type="hidden" name="action" value="mark_replied"><input type="hidden" name="id" value="<?= $viewed_msg['id'] ?>">
                            <button type="submit" class="btn btn-success" style="width:100%;"><i class="fas fa-check-double"></i> Mark as Replied</button>
                        </form>
                        <?php endif; ?>
                        <?php if ($viewed_msg['status'] === 'new'): ?>
                        <form method="POST"><input type="hidden" name="action" value="mark_read"><input type="hidden" name="id" value="<?= $viewed_msg['id'] ?>">
                            <button type="submit" class="btn btn-outline" style="width:100%;"><i class="fas fa-eye"></i> Mark as Read</button>
                        </form>
                        <?php endif; ?>
                        <button class="btn btn-outline-danger" style="width:100%;" onclick="document.getElementById('deleteMsg').classList.add('open')"><i class="fas fa-trash"></i> Delete</button>
                    </div>
                </div>
                <div class="section-card">
                    <div class="section-card-header"><h2><i class="fas fa-sticky-note"></i> Admin Notes</h2></div>
                    <div style="padding:1.25rem;">
                        <form method="POST">
                            <input type="hidden" name="action" value="save_notes">
                            <input type="hidden" name="id" value="<?= $viewed_msg['id'] ?>">
                            <textarea name="admin_notes" class="form-control" rows="4" placeholder="Internal notes…"><?= htmlspecialchars($viewed_msg['admin_notes'] ?? '') ?></textarea>
                            <button type="submit" class="btn btn-primary btn-sm" style="margin-top:.75rem;width:100%;"><i class="fas fa-save"></i> Save Notes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete confirm modal -->
        <div class="modal-backdrop" id="deleteMsg">
            <div class="modal">
                <div class="modal-header">
                    <div class="modal-icon-danger"><i class="fas fa-trash"></i></div>
                    <div><h3>Delete Message</h3><p>Delete message from <?= htmlspecialchars($viewed_msg['name']) ?>? This cannot be undone.</p></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" onclick="document.getElementById('deleteMsg').classList.remove('open')">Cancel</button>
                    <form method="POST">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $viewed_msg['id'] ?>">
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                </div>
            </div>
        </div>

        <?php else: ?>
        <!-- Messages list -->
        <div class="section-card">
            <div class="section-card-header">
                <h2><i class="fas fa-inbox"></i> All Messages <span style="font-weight:400;color:var(--text-lighter);">(<?= $total_count ?>)</span></h2>
                <form method="GET" class="table-toolbar">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Search name, email…" value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="new" <?= $filter_status==='new'?'selected':'' ?>>New</option>
                        <option value="read" <?= $filter_status==='read'?'selected':'' ?>>Read</option>
                        <option value="replied" <?= $filter_status==='replied'?'selected':'' ?>>Replied</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                    <?php if ($search || $filter_status): ?><a href="messages.php" class="btn btn-light btn-sm"><i class="fas fa-times"></i></a><?php endif; ?>
                </form>
            </div>

            <?php if (empty($messages)): ?>
            <div class="empty-state"><i class="fas fa-inbox"></i><h3>No messages</h3><p>No contact messages yet.</p></div>
            <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead><tr><th>Sender</th><th>Subject / Course</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($messages as $m): ?>
                        <tr style="<?= $m['status']==='new'?'background:#eff6ff;':'' ?>">
                            <td>
                                <div class="td-title" style="<?= $m['status']==='new'?'font-weight:700;':'' ?>"><?= htmlspecialchars($m['name']) ?></div>
                                <div class="td-sub"><?= htmlspecialchars($m['email']) ?></div>
                            </td>
                            <td>
                                <div class="td-title"><?= htmlspecialchars($m['subject'] ?: '(No subject)') ?></div>
                                <?php if ($m['course_interest']): ?>
                                <div class="td-sub" style="color:var(--primary);"><i class="fas fa-book-open" style="font-size:.7rem;"></i> <?= htmlspecialchars($m['course_interest']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge <?= $m['status'] ?>"><?= ucfirst($m['status']) ?></span></td>
                            <td style="white-space:nowrap;font-size:.8rem;color:var(--text-light);"><?= date('d M Y', strtotime($m['created_at'])) ?></td>
                            <td>
                                <div class="td-actions">
                                    <a href="messages.php?view=<?= $m['id'] ?>" class="btn btn-icon btn-outline btn-sm" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="btn btn-icon btn-outline btn-sm" title="Reply by email"><i class="fas fa-reply"></i></a>
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
