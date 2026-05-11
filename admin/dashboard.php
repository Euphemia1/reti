<?php
require_once 'auth.php';
require_once '../config/database.php';
$db   = new Database();
$conn = $db->getConnection();

// Stats
$stats = [];
$queries = [
    'total_courses'      => "SELECT COUNT(*) FROM courses WHERE is_active=1",
    'total_news'         => "SELECT COUNT(*) FROM news WHERE status='published'",
    'new_messages'       => "SELECT COUNT(*) FROM contact_messages WHERE status='new'",
    'total_messages'     => "SELECT COUNT(*) FROM contact_messages",
    'total_testimonials' => "SELECT COUNT(*) FROM testimonials WHERE is_active=1",
    'gallery_items'      => "SELECT COUNT(*) FROM gallery WHERE is_active=1",
    'total_enrollments'  => "SELECT COUNT(*) FROM enrollment_requests",
    'pending_enrollments'=> "SELECT COUNT(*) FROM enrollment_requests WHERE status='pending'",
];
foreach ($queries as $key => $sql) {
    try { $stats[$key] = $conn->query($sql)->fetchColumn(); }
    catch(Exception $e) { $stats[$key] = 0; }
}

// Recent messages
$recent_messages = [];
try {
    $stmt = $conn->query("SELECT id, name, email, subject, course_interest, status, created_at FROM contact_messages ORDER BY created_at DESC LIMIT 6");
    $recent_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {}

// Recent enrollments
$recent_enrollments = [];
try {
    $stmt = $conn->query("SELECT er.id, er.full_name, er.email, er.status, er.created_at, c.title as course_title FROM enrollment_requests er LEFT JOIN courses c ON er.course_id=c.id ORDER BY er.created_at DESC LIMIT 6");
    $recent_enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {}

// Courses by category
$course_stats = [];
try {
    $stmt = $conn->query("SELECT category, COUNT(*) as cnt FROM courses WHERE is_active=1 GROUP BY category");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $course_stats[$row['category']] = $row['cnt'];
    }
} catch(Exception $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - RETI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <i class="fas fa-graduation-cap"></i>
        <div>
            <div class="sidebar-logo-title">RETI Admin</div>
            <div class="sidebar-logo-sub">Rising East Training</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="dashboard.php" class="nav-item active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="courses.php" class="nav-item"><i class="fas fa-book-open"></i> Courses</a>
        <a href="news.php" class="nav-item"><i class="fas fa-newspaper"></i> News &amp; Events</a>
        <div class="nav-section-label">Enquiries</div>
        <a href="messages.php" class="nav-item">
            <i class="fas fa-envelope"></i> Messages
            <?php if ($stats['new_messages'] > 0): ?>
            <span class="nav-badge"><?= $stats['new_messages'] ?></span>
            <?php endif; ?>
        </a>
        <a href="enrollments.php" class="nav-item">
            <i class="fas fa-user-graduate"></i> Enrollments
            <?php if ($stats['pending_enrollments'] > 0): ?>
            <span class="nav-badge"><?= $stats['pending_enrollments'] ?></span>
            <?php endif; ?>
        </a>
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
            <div>
                <div class="sidebar-user-name"><?= htmlspecialchars($admin_user['full_name']) ?></div>
                <div class="sidebar-user-role"><?= ucfirst(str_replace('_',' ',$admin_user['role'])) ?></div>
            </div>
        </div>
        <a href="logout.php" class="btn-logout" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
    </div>
</aside>

<!-- Main -->
<div class="main-wrap">
    <header class="top-header">
        <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <div class="header-title">
            <h1>Dashboard</h1>
            <p>Welcome back, <?= htmlspecialchars($admin_user['full_name']) ?>! Here's what's happening.</p>
        </div>
        <div class="header-actions">
            <a href="../index.php" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-external-link-alt"></i> View Site</a>
            <a href="logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </header>

    <main class="page-content">

        <!-- Stat Cards Row 1 -->
        <div class="stats-grid">
            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                <div class="stat-body">
                    <div class="stat-value"><?= $stats['total_courses'] ?></div>
                    <div class="stat-label">Active Courses</div>
                    <div class="stat-sub">
                        <?= ($course_stats['level3'] ?? 0) ?> Level III &bull;
                        <?= ($course_stats['skills_award'] ?? 0) ?> Skills Award &bull;
                        <?= ($course_stats['short_course'] ?? 0) ?> Short
                    </div>
                </div>
                <a href="courses.php" class="stat-link">Manage <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
                <div class="stat-body">
                    <div class="stat-value"><?= $stats['total_news'] ?></div>
                    <div class="stat-label">Published News</div>
                    <div class="stat-sub">Articles &amp; announcements</div>
                </div>
                <a href="news.php" class="stat-link">Manage <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                <div class="stat-body">
                    <div class="stat-value"><?= $stats['total_messages'] ?></div>
                    <div class="stat-label">Total Messages</div>
                    <div class="stat-sub">
                        <?php if ($stats['new_messages'] > 0): ?>
                        <span style="color:#ef4444;font-weight:600;"><?= $stats['new_messages'] ?> unread</span>
                        <?php else: ?>
                        All messages read
                        <?php endif; ?>
                    </div>
                </div>
                <a href="messages.php" class="stat-link">View <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="stat-card teal">
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-body">
                    <div class="stat-value"><?= $stats['total_enrollments'] ?></div>
                    <div class="stat-label">Enrollments</div>
                    <div class="stat-sub">
                        <?php if ($stats['pending_enrollments'] > 0): ?>
                        <span style="color:#f97316;font-weight:600;"><?= $stats['pending_enrollments'] ?> pending</span>
                        <?php else: ?>
                        No pending requests
                        <?php endif; ?>
                    </div>
                </div>
                <a href="enrollments.php" class="stat-link">Review <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon"><i class="fas fa-quote-right"></i></div>
                <div class="stat-body">
                    <div class="stat-value"><?= $stats['total_testimonials'] ?></div>
                    <div class="stat-label">Testimonials</div>
                    <div class="stat-sub">Active success stories</div>
                </div>
                <a href="testimonials.php" class="stat-link">Manage <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="stat-card red">
                <div class="stat-icon"><i class="fas fa-images"></i></div>
                <div class="stat-body">
                    <div class="stat-value"><?= $stats['gallery_items'] ?></div>
                    <div class="stat-label">Gallery Items</div>
                    <div class="stat-sub">Photos &amp; media</div>
                </div>
                <a href="gallery.php" class="stat-link">Manage <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-card" style="margin-bottom:1.5rem;">
            <div class="section-card-header">
                <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
            </div>
            <div class="quick-actions">
                <a href="course-add.php" class="quick-action green">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Course</span>
                </a>
                <a href="news-add.php" class="quick-action blue">
                    <i class="fas fa-pen-alt"></i>
                    <span>Write Article</span>
                </a>
                <a href="messages.php" class="quick-action orange">
                    <i class="fas fa-inbox"></i>
                    <span>View Messages</span>
                    <?php if ($stats['new_messages'] > 0): ?>
                    <span class="qa-badge"><?= $stats['new_messages'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="enrollments.php" class="quick-action teal">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Enrollments</span>
                    <?php if ($stats['pending_enrollments'] > 0): ?>
                    <span class="qa-badge"><?= $stats['pending_enrollments'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="testimonials.php" class="quick-action purple">
                    <i class="fas fa-star"></i>
                    <span>Testimonials</span>
                </a>
                <a href="settings.php" class="quick-action gray">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </div>
        </div>

        <!-- Two-column: Recent Messages + Recent Enrollments -->
        <div class="two-col-grid">

            <!-- Recent Messages -->
            <div class="section-card">
                <div class="section-card-header">
                    <h2><i class="fas fa-envelope"></i> Recent Messages</h2>
                    <a href="messages.php" class="btn btn-sm btn-outline">View All</a>
                </div>
                <?php if (empty($recent_messages)): ?>
                <div class="empty-state"><i class="fas fa-inbox"></i><p>No messages yet</p></div>
                <?php else: ?>
                <div class="activity-list">
                    <?php foreach ($recent_messages as $msg): ?>
                    <div class="activity-item">
                        <div class="activity-avatar" style="background:<?= $msg['status']==='new'?'#0f766e':'#94a3b8' ?>">
                            <?= strtoupper(substr($msg['name'],0,1)) ?>
                        </div>
                        <div class="activity-body">
                            <div class="activity-title">
                                <?= htmlspecialchars($msg['name']) ?>
                                <?php if ($msg['status']==='new'): ?>
                                <span class="badge new">New</span>
                                <?php elseif ($msg['status']==='read'): ?>
                                <span class="badge read">Read</span>
                                <?php else: ?>
                                <span class="badge replied">Replied</span>
                                <?php endif; ?>
                            </div>
                            <div class="activity-sub"><?= htmlspecialchars($msg['subject'] ?: 'No subject') ?></div>
                            <?php if ($msg['course_interest']): ?>
                            <div class="activity-sub" style="color:#0f766e;"><i class="fas fa-book-open" style="font-size:.7rem;"></i> <?= htmlspecialchars($msg['course_interest']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="activity-meta">
                            <div class="activity-time"><?= date('M j', strtotime($msg['created_at'])) ?></div>
                            <a href="messages.php?view=<?= $msg['id'] ?>" class="btn btn-icon btn-sm"><i class="fas fa-eye"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Recent Enrollments -->
            <div class="section-card">
                <div class="section-card-header">
                    <h2><i class="fas fa-user-graduate"></i> Recent Enrollments</h2>
                    <a href="enrollments.php" class="btn btn-sm btn-outline">View All</a>
                </div>
                <?php if (empty($recent_enrollments)): ?>
                <div class="empty-state"><i class="fas fa-user-graduate"></i><p>No enrollment requests yet</p></div>
                <?php else: ?>
                <div class="activity-list">
                    <?php foreach ($recent_enrollments as $enr): ?>
                    <div class="activity-item">
                        <div class="activity-avatar" style="background:#14b8a6">
                            <?= strtoupper(substr($enr['full_name'],0,1)) ?>
                        </div>
                        <div class="activity-body">
                            <div class="activity-title">
                                <?= htmlspecialchars($enr['full_name']) ?>
                                <?php
                                $enr_status = $enr['status'];
                                $badge_class = $enr_status === 'pending' ? 'pending' : ($enr_status === 'accepted' ? 'active' : 'inactive');
                                ?>
                                <span class="badge <?= $badge_class ?>"><?= ucfirst($enr_status) ?></span>
                            </div>
                            <div class="activity-sub"><?= htmlspecialchars($enr['course_title'] ?? 'N/A') ?></div>
                            <div class="activity-sub" style="font-size:.75rem;color:#94a3b8;"><?= htmlspecialchars($enr['email']) ?></div>
                        </div>
                        <div class="activity-meta">
                            <div class="activity-time"><?= date('M j', strtotime($enr['created_at'])) ?></div>
                            <a href="enrollments.php?view=<?= $enr['id'] ?>" class="btn btn-icon btn-sm"><i class="fas fa-eye"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </main>
</div>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
const toggle  = document.getElementById('sidebarToggle');

toggle.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
});
overlay.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
});
</script>
</body>
</html>
