<?php
require_once 'auth.php';
require_once '../config/database.php';
$db   = new Database();
$conn = $db->getConnection();

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: news.php'); exit; }

$article = $conn->prepare("SELECT * FROM news WHERE id=?");
$article->execute([$id]);
$article = $article->fetch(PDO::FETCH_ASSOC);
if (!$article) { header('Location: news.php'); exit; }

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $author  = trim($_POST['author'] ?? '');
    $tags    = trim($_POST['tags'] ?? '');
    $image   = trim($_POST['image_url'] ?? '');
    $status  = in_array($_POST['status'] ?? '', ['published','draft']) ? $_POST['status'] : 'draft';

    if (empty($title))   $errors[] = 'Title is required.';
    if (empty($content)) $errors[] = 'Content is required.';

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("UPDATE news SET title=?,excerpt=?,content=?,author=?,tags=?,image_url=?,status=? WHERE id=?");
            $stmt->execute([$title, $excerpt ?: null, $content, $author, $tags ?: null, $image ?: null, $status, $id]);
            $success = true;
            $article = $conn->prepare("SELECT * FROM news WHERE id=?");
            $article->execute([$id]);
            $article = $article->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}
$d = (empty($errors) && !$success) ? $article : (empty($errors) ? $article : array_merge($article, $_POST));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Article - RETI Admin</title>
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
        <div class="header-title">
            <h1>Edit Article</h1>
            <p><a href="news.php" style="color:var(--primary);">News</a> / <?= htmlspecialchars(substr($article['title'],0,50)) ?>…</p>
        </div>
        <div class="header-actions">
            <a href="../news.php?id=<?= $id ?>" target="_blank" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> Preview</a>
            <a href="news.php" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </header>

    <main class="page-content">

        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> Article updated successfully.</div><?php endif; ?>
        <?php if (!empty($errors)): ?>
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>
            <div><?php foreach ($errors as $e) echo htmlspecialchars($e) . '<br>'; ?></div>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-layout">
                <div class="form-main">
                    <div class="section-card" style="margin-bottom:1.5rem;">
                        <div class="section-card-header"><h2><i class="fas fa-pen-alt"></i> Article Content</h2></div>
                        <div style="padding:1.5rem;display:flex;flex-direction:column;gap:1.25rem;">
                            <div class="form-group">
                                <label>Title <span class="req">*</span></label>
                                <input type="text" name="title" class="form-control" required
                                       value="<?= htmlspecialchars($d['title']) ?>"
                                       style="font-size:1.1rem;font-weight:600;">
                            </div>
                            <div class="form-group">
                                <label>Excerpt / Summary</label>
                                <textarea name="excerpt" class="form-control" rows="2"><?= htmlspecialchars($d['excerpt'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Full Content <span class="req">*</span></label>
                                <textarea name="content" class="form-control" rows="14"><?= htmlspecialchars($d['content'] ?? '') ?></textarea>
                                <div class="form-hint">Basic HTML is supported.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-sidebar">
                    <div class="section-card" style="margin-bottom:1.25rem;">
                        <div class="section-card-header"><h2><i class="fas fa-sliders-h"></i> Publish</h2></div>
                        <div style="padding:1.25rem;display:flex;flex-direction:column;gap:1rem;">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="draft" <?= $d['status']==='draft'?'selected':'' ?>>Draft</option>
                                    <option value="published" <?= $d['status']==='published'?'selected':'' ?>>Published</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Author</label>
                                <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($d['author'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label>Tags</label>
                                <input type="text" name="tags" class="form-control" value="<?= htmlspecialchars($d['tags'] ?? '') ?>" placeholder="comma-separated">
                            </div>
                            <div class="form-group">
                                <label>Featured Image URL</label>
                                <input type="text" name="image_url" class="form-control" id="imageUrl"
                                       value="<?= htmlspecialchars($d['image_url'] ?? '') ?>"
                                       oninput="previewImage(this.value)">
                                <?php if (!empty($d['image_url'])): ?>
                                <img id="imgPreview" class="img-preview" src="../<?= htmlspecialchars($d['image_url']) ?>" alt="Preview" style="margin-top:.5rem;">
                                <?php else: ?>
                                <img id="imgPreview" class="img-preview" style="display:none;margin-top:.5rem;" alt="Preview">
                                <?php endif; ?>
                            </div>
                            <div style="font-size:.78rem;color:var(--text-lighter);">
                                <i class="fas fa-eye"></i> <?= number_format($article['views'] ?? 0) ?> views &nbsp;
                                <i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($article['created_at'])) ?>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:.75rem;">
                        <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fas fa-save"></i> Update Article</button>
                        <a href="news.php" class="btn btn-light" style="width:100%;text-align:center;">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
<style>
.form-layout { display:grid; grid-template-columns:1fr 300px; gap:1.5rem; align-items:start; }
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
</script>
</body>
</html>
