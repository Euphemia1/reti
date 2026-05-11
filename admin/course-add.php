<?php
require_once 'auth.php';
require_once '../config/database.php';
$db   = new Database();
$conn = $db->getConnection();

$errors  = [];
$success = false;

$cat_labels = [
    'level3'       => 'Level III (12 months)',
    'skills_award' => 'Skills Award (6 months)',
    'short_course' => 'Short Course (2–8 weeks)',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title             = trim($_POST['title'] ?? '');
    $category          = $_POST['category'] ?? '';
    $duration          = trim($_POST['duration'] ?? '');
    $fee               = trim($_POST['fee'] ?? '');
    $short_description = trim($_POST['short_description'] ?? '');
    $description       = trim($_POST['description'] ?? '');
    $requirements      = trim($_POST['requirements'] ?? '');
    $outcomes          = trim($_POST['outcomes'] ?? '');
    $intake_dates      = trim($_POST['intake_dates'] ?? '');
    $image_url         = trim($_POST['image_url'] ?? '');
    $is_active         = isset($_POST['is_active']) ? 1 : 0;
    $is_featured       = isset($_POST['is_featured']) ? 1 : 0;
    $sort_order        = (int)($_POST['sort_order'] ?? 0);

    if (empty($title))    $errors[] = 'Course title is required.';
    if (empty($category) || !array_key_exists($category, $cat_labels)) $errors[] = 'Please select a valid category.';

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO courses
                (title, category, duration, fee, short_description, description, requirements, outcomes, intake_dates, image_url, is_active, is_featured, sort_order, created_at)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
            $stmt->execute([
                $title, $category, $duration ?: null, $fee ?: null,
                $short_description ?: null, $description ?: null,
                $requirements ?: null, $outcomes ?: null,
                $intake_dates ?: null, $image_url ?: null,
                $is_active, $is_featured, $sort_order,
            ]);
            header('Location: courses.php?added=1');
            exit;
        } catch(Exception $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Course - RETI Admin</title>
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
        <div class="header-title">
            <h1>Add Course</h1>
            <p><a href="courses.php" style="color:var(--primary);">Courses</a> / Add New</p>
        </div>
        <div class="header-actions">
            <a href="courses.php" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </header>

    <main class="page-content">

        <?php if (!empty($errors)): ?>
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>
            <div><?php foreach ($errors as $e) echo htmlspecialchars($e) . '<br>'; ?></div>
        </div>
        <?php endif; ?>

        <form method="POST" id="courseForm">
            <div class="form-layout">
                <!-- Main -->
                <div class="form-main">
                    <div class="section-card" style="margin-bottom:1.5rem;">
                        <div class="section-card-header"><h2><i class="fas fa-info-circle"></i> Course Information</h2></div>
                        <div style="padding:1.5rem;">
                            <div class="form-grid" style="gap:1.25rem;">
                                <div class="form-group">
                                    <label>Course Title <span class="req">*</span></label>
                                    <input type="text" name="title" class="form-control" required
                                           value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" placeholder="e.g., Computer Applications">
                                </div>
                                <div class="form-grid form-grid-2">
                                    <div class="form-group">
                                        <label>Category <span class="req">*</span></label>
                                        <select name="category" class="form-control" required>
                                            <option value="">Select category…</option>
                                            <?php foreach ($cat_labels as $val => $lbl): ?>
                                            <option value="<?= $val ?>" <?= ($_POST['category']??'')===$val?'selected':'' ?>><?= $lbl ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Duration</label>
                                        <input type="text" name="duration" class="form-control"
                                               value="<?= htmlspecialchars($_POST['duration'] ?? '') ?>" placeholder="e.g., 12 months">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Short Description</label>
                                    <textarea name="short_description" class="form-control" rows="2"
                                              placeholder="Brief overview (shown in course cards, max ~200 chars)"><?= htmlspecialchars($_POST['short_description'] ?? '') ?></textarea>
                                    <div class="form-hint">Shown on course listing cards. Keep concise.</div>
                                </div>
                                <div class="form-group">
                                    <label>Full Description</label>
                                    <textarea name="description" class="form-control" rows="5"
                                              placeholder="Detailed course description…"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Requirements / Entry Criteria</label>
                                    <textarea name="requirements" class="form-control" rows="3"
                                              placeholder="Who can apply, prerequisites, documents needed…"><?= htmlspecialchars($_POST['requirements'] ?? '') ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Learning Outcomes</label>
                                    <textarea name="outcomes" class="form-control" rows="3"
                                              placeholder="What students will gain from this course…"><?= htmlspecialchars($_POST['outcomes'] ?? '') ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Intake Dates</label>
                                    <input type="text" name="intake_dates" class="form-control"
                                           value="<?= htmlspecialchars($_POST['intake_dates'] ?? '') ?>" placeholder="e.g., January, April, July, October">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="form-sidebar">
                    <div class="section-card" style="margin-bottom:1.25rem;">
                        <div class="section-card-header"><h2><i class="fas fa-sliders-h"></i> Details</h2></div>
                        <div style="padding:1.25rem;display:flex;flex-direction:column;gap:1rem;">
                            <div class="form-group">
                                <label>Tuition Fee (K)</label>
                                <input type="number" name="fee" class="form-control" step="0.01" min="0"
                                       value="<?= htmlspecialchars($_POST['fee'] ?? '') ?>" placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label>Image URL</label>
                                <input type="text" name="image_url" class="form-control" id="imageUrl"
                                       value="<?= htmlspecialchars($_POST['image_url'] ?? '') ?>"
                                       placeholder="assets/images/courses/image.jpg" oninput="previewImage(this.value)">
                                <img id="imgPreview" class="img-preview" style="display:none;margin-top:.5rem;" alt="Preview">
                            </div>
                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" min="0"
                                       value="<?= htmlspecialchars($_POST['sort_order'] ?? '0') ?>">
                                <div class="form-hint">Lower numbers appear first.</div>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:.5rem;padding-top:.5rem;border-top:1px solid var(--border);">
                                <label style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;font-weight:600;cursor:pointer;">
                                    <input type="checkbox" name="is_active" value="1" <?= ($_POST['is_active'] ?? 1) ? 'checked' : '' ?>>
                                    Active (visible on website)
                                </label>
                                <label style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;font-weight:600;cursor:pointer;">
                                    <input type="checkbox" name="is_featured" value="1" <?= isset($_POST['is_featured']) ? 'checked' : '' ?>>
                                    Featured on homepage
                                </label>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:.75rem;">
                        <button type="submit" class="btn btn-primary" style="width:100%;">
                            <i class="fas fa-save"></i> Save Course
                        </button>
                        <a href="courses.php" class="btn btn-light" style="width:100%;text-align:center;">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<style>
.form-layout { display:grid; grid-template-columns:1fr 300px; gap:1.5rem; align-items:start; }
.form-main {}
.form-sidebar {}
@media(max-width:900px){ .form-layout { grid-template-columns:1fr; } }
</style>
<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
document.getElementById('sidebarToggle').addEventListener('click',()=>{ sidebar.classList.toggle('open'); overlay.classList.toggle('show'); });
overlay.addEventListener('click',()=>{ sidebar.classList.remove('open'); overlay.classList.remove('show'); });

function previewImage(src) {
    const img = document.getElementById('imgPreview');
    if (src) { img.src = '../' + src; img.style.display='block'; }
    else img.style.display='none';
}
// Init preview on page load (if reloading after error)
const initSrc = document.getElementById('imageUrl').value;
if (initSrc) previewImage(initSrc);
</script>
</body>
</html>
