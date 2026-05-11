<?php
require_once 'auth.php';
require_once '../config/database.php';
$db   = new Database();
$conn = $db->getConnection();

$success = $error = '';

// Load all settings
$stmt = $conn->query("SELECT setting_key, setting_value, setting_type FROM site_settings ORDER BY setting_key");
$settings = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $settings[$row['setting_key']] = $row;
}
function sval($s, $key) { return htmlspecialchars($s[$key]['setting_value'] ?? ''); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_group') {
        $keys = $_POST['keys'] ?? [];
        $vals = $_POST['vals'] ?? [];
        try {
            $conn->beginTransaction();
            for ($i = 0; $i < count($keys); $i++) {
                $k = $keys[$i];
                $v = $vals[$i] ?? '';
                $conn->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?,?)
                    ON DUPLICATE KEY UPDATE setting_value=?")->execute([$k, $v, $v]);
            }
            $conn->commit();
            $success = 'Settings saved successfully.';
            // Refresh
            $stmt = $conn->query("SELECT setting_key, setting_value, setting_type FROM site_settings ORDER BY setting_key");
            $settings = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $settings[$row['setting_key']] = $row;
            }
        } catch(Exception $e) {
            $conn->rollBack();
            $error = 'Failed to save settings.';
        }
    }
}

// Group definitions
$groups = [
    'General' => [
        'site_name'         => ['label' => 'College Name', 'type' => 'text', 'placeholder' => 'Rising East Training Institute'],
        'site_tagline'      => ['label' => 'Tagline / Motto', 'type' => 'text', 'placeholder' => ''],
        'site_description'  => ['label' => 'Site Description', 'type' => 'textarea', 'placeholder' => 'Short description for SEO'],
        'contact_email'     => ['label' => 'Contact Email', 'type' => 'email', 'placeholder' => 'info@risingeast.ac.zm'],
        'contact_phone'     => ['label' => 'Phone Number', 'type' => 'text', 'placeholder' => '+260 XXX XXX XXX'],
        'contact_phone2'    => ['label' => 'Phone Number 2', 'type' => 'text', 'placeholder' => ''],
        'contact_whatsapp'  => ['label' => 'WhatsApp Number', 'type' => 'text', 'placeholder' => '+260 XXX XXX XXX'],
        'contact_address'   => ['label' => 'Physical Address', 'type' => 'textarea', 'placeholder' => ''],
        'office_hours'      => ['label' => 'Office Hours', 'type' => 'text', 'placeholder' => 'Mon–Fri: 08:00–17:00'],
    ],
    'Statistics' => [
        'graduates_count'   => ['label' => 'Total Graduates', 'type' => 'number', 'placeholder' => '500'],
        'courses_count'     => ['label' => 'Total Courses', 'type' => 'number', 'placeholder' => '20'],
        'success_rate'      => ['label' => 'Success Rate (%)', 'type' => 'number', 'placeholder' => '95'],
        'years_experience'  => ['label' => 'Years in Operation', 'type' => 'number', 'placeholder' => '10'],
    ],
    'Social Media' => [
        'facebook_url'      => ['label' => 'Facebook URL', 'type' => 'url', 'placeholder' => 'https://facebook.com/...'],
        'twitter_url'       => ['label' => 'Twitter / X URL', 'type' => 'url', 'placeholder' => 'https://twitter.com/...'],
        'instagram_url'     => ['label' => 'Instagram URL', 'type' => 'url', 'placeholder' => 'https://instagram.com/...'],
        'linkedin_url'      => ['label' => 'LinkedIn URL', 'type' => 'url', 'placeholder' => 'https://linkedin.com/...'],
        'youtube_url'       => ['label' => 'YouTube URL', 'type' => 'url', 'placeholder' => 'https://youtube.com/...'],
    ],
    'Downloads' => [
        'application_form_url' => ['label' => 'Application Form URL', 'type' => 'text', 'placeholder' => 'assets/downloads/application-form.pdf'],
        'course_list_url'      => ['label' => 'Course List / Prospectus URL', 'type' => 'text', 'placeholder' => 'assets/downloads/course-list.pdf'],
    ],
    'Hero / Appearance' => [
        'hero_title'        => ['label' => 'Hero Title', 'type' => 'text', 'placeholder' => ''],
        'hero_subtitle'     => ['label' => 'Hero Subtitle', 'type' => 'text', 'placeholder' => ''],
        'hero_image'        => ['label' => 'Hero Background Image', 'type' => 'text', 'placeholder' => 'assets/images/hero-1.jpg'],
        'hero_image_2'      => ['label' => 'Hero Background Image 2', 'type' => 'text', 'placeholder' => ''],
        'hero_image_3'      => ['label' => 'Hero Background Image 3', 'type' => 'text', 'placeholder' => ''],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings - RETI Admin</title>
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
        <a href="settings.php" class="nav-item active"><i class="fas fa-cog"></i> Settings</a>
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
        <div class="header-title"><h1>Site Settings</h1><p>Configure your website content and contact details</p></div>
    </header>

    <main class="page-content">

        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>

        <!-- Tab nav -->
        <div class="settings-tabs">
            <?php $first = true; foreach ($groups as $group_name => $fields): ?>
            <button class="settings-tab <?= $first ? 'active' : '' ?>" data-tab="<?= strtolower(str_replace([' ', '/'], ['-', '-'], $group_name)) ?>">
                <?= $group_name ?>
            </button>
            <?php $first = false; endforeach; ?>
        </div>

        <?php $first = true; foreach ($groups as $group_name => $fields): ?>
        <div class="settings-panel <?= $first ? 'active' : '' ?>" id="tab-<?= strtolower(str_replace([' ', '/'], ['-', '-'], $group_name)) ?>">
            <div class="section-card">
                <div class="section-card-header"><h2><i class="fas fa-cog"></i> <?= $group_name ?></h2></div>
                <div style="padding:1.5rem;">
                    <form method="POST">
                        <input type="hidden" name="action" value="save_group">
                        <?php $idx = 0; foreach ($fields as $key => $field): ?>
                        <input type="hidden" name="keys[<?= $idx ?>]" value="<?= htmlspecialchars($key) ?>">
                        <div class="setting-row">
                            <div class="setting-info">
                                <label for="val_<?= $key ?>"><?= $field['label'] ?></label>
                                <?php if (isset($field['hint'])): ?><p><?= $field['hint'] ?></p><?php endif; ?>
                            </div>
                            <div class="setting-control">
                                <?php $val = $settings[$key]['setting_value'] ?? ''; ?>
                                <?php if ($field['type'] === 'textarea'): ?>
                                <textarea id="val_<?= $key ?>" name="vals[<?= $idx ?>]" class="form-control" rows="3"
                                          placeholder="<?= htmlspecialchars($field['placeholder'] ?? '') ?>"><?= htmlspecialchars($val) ?></textarea>
                                <?php else: ?>
                                <input type="<?= $field['type'] ?>" id="val_<?= $key ?>" name="vals[<?= $idx ?>]"
                                       class="form-control" value="<?= htmlspecialchars($val) ?>"
                                       placeholder="<?= htmlspecialchars($field['placeholder'] ?? '') ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php $idx++; endforeach; ?>
                        <div style="margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid var(--border);">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save <?= $group_name ?> Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php $first = false; endforeach; ?>

    </main>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<style>
.settings-tabs { display:flex; gap:.4rem; margin-bottom:1.5rem; flex-wrap:wrap; }
.settings-tab { padding:.5rem 1rem; border-radius:8px; border:1.5px solid var(--border); background:white; font-size:.825rem; font-weight:600; color:var(--text-body); cursor:pointer; transition:.2s; font-family:inherit; }
.settings-tab:hover { border-color:var(--primary); color:var(--primary); }
.settings-tab.active { background:var(--primary); border-color:var(--primary); color:white; }
.settings-panel { display:none; }
.settings-panel.active { display:block; }
.setting-row { display:grid; grid-template-columns:200px 1fr; gap:1.5rem; align-items:start; padding:1.1rem 0; border-bottom:1px solid #f1f5f9; }
.setting-row:last-of-type { border-bottom:none; }
.setting-info label { font-weight:600; font-size:.875rem; color:var(--text-dark); display:block; }
.setting-info p { font-size:.775rem; color:var(--text-light); margin-top:2px; }
.setting-control {}
@media(max-width:640px){ .setting-row { grid-template-columns:1fr; gap:.4rem; } }
</style>
<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
document.getElementById('sidebarToggle').addEventListener('click',()=>{ sidebar.classList.toggle('open'); overlay.classList.toggle('show'); });
overlay.addEventListener('click',()=>{ sidebar.classList.remove('open'); overlay.classList.remove('show'); });

// Tabs
document.querySelectorAll('.settings-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.settings-tab').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
    });
});
</script>
</body>
</html>
