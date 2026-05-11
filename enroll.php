<?php
require_once 'includes/functions.php';
// $conn already set by functions.php

// Pre-selected course from URL
$pre_course = (int)($_GET['course'] ?? 0);

// Load courses for dropdown
$courses_stmt = $conn->query("SELECT id, title, category, duration FROM courses WHERE is_active=1 ORDER BY category, title");
$all_courses  = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name       = trim($_POST['full_name'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $phone           = trim($_POST['phone'] ?? '');
    $date_of_birth   = trim($_POST['date_of_birth'] ?? '');
    $education_level = trim($_POST['education_level'] ?? '');
    $course_id       = (int)($_POST['course_id'] ?? 0);
    $message         = trim($_POST['message'] ?? '');

    if (empty($full_name))       $errors[] = 'Full name is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
    if (empty($phone))           $errors[] = 'Phone number is required.';
    if (!$course_id)             $errors[] = 'Please select a course.';

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO enrollment_requests
                (full_name, email, phone, date_of_birth, education_level, course_id, message, status, created_at)
                VALUES (?,?,?,?,?,?,?,'pending',NOW())");
            $stmt->execute([$full_name, $email, $phone, $date_of_birth ?: null, $education_level ?: null, $course_id, $message ?: null]);
            $success = true;
        } catch(Exception $e) {
            $errors[] = 'There was an error submitting your application. Please try again.';
        }
    }
}

$site_name = getSiteSetting('site_name') ?: 'Rising East Training Institute';
$cat_labels = ['level3' => 'Level III', 'skills_award' => 'Skills Award', 'short_course' => 'Short Courses'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Apply / Enroll - <?= htmlspecialchars($site_name) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
<link rel="stylesheet" href="assets/css/style.css">
<style>
.enroll-page { max-width:780px; margin:0 auto; padding:3rem 1.5rem 5rem; }
.enroll-header { text-align:center; margin-bottom:2.5rem; }
.enroll-header .badge-pill { display:inline-flex; align-items:center; gap:.5rem; background:rgba(15,118,110,.08); color:var(--primary); padding:.35rem 1rem; border-radius:50px; font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; margin-bottom:1rem; }
.enroll-header h1 { font-size:2.2rem; font-weight:900; color:var(--text-dark); margin-bottom:.75rem; }
.enroll-header p { color:var(--text-light); font-size:1.05rem; max-width:500px; margin:0 auto; }
.enroll-card { background:white; border-radius:20px; border:1px solid var(--border); box-shadow:0 8px 40px rgba(0,0,0,.06); overflow:hidden; }
.enroll-card-header { background:linear-gradient(135deg,#0f766e,#14b8a6); padding:1.75rem 2rem; color:white; }
.enroll-card-header h2 { font-size:1.2rem; font-weight:700; margin-bottom:.25rem; }
.enroll-card-header p { opacity:.85; font-size:.875rem; }
.enroll-body { padding:2rem; }
.form-section { margin-bottom:1.75rem; }
.form-section-title { font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--primary); margin-bottom:1rem; padding-bottom:.5rem; border-bottom:2px solid rgba(15,118,110,.12); }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
@media(max-width:600px){ .form-row { grid-template-columns:1fr; } }
.form-group { margin-bottom:1.25rem; }
.form-group label { display:block; font-weight:600; font-size:.875rem; color:var(--text-dark); margin-bottom:.4rem; }
.form-group label .req { color:#ef4444; }
.form-control { width:100%; padding:.75rem 1rem; border:2px solid var(--border); border-radius:10px; font-size:.9rem; font-family:inherit; outline:none; transition:border-color .2s,box-shadow .2s; }
.form-control:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(15,118,110,.1); }
.form-control::placeholder { color:#94a3b8; }
textarea.form-control { resize:vertical; min-height:100px; }
.submit-btn { width:100%; padding:1rem; background:linear-gradient(135deg,var(--primary),var(--primary-light)); color:white; border:none; border-radius:12px; font-size:1.05rem; font-weight:700; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:.6rem; transition:all .2s; }
.submit-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(15,118,110,.3); }
.alert { padding:1rem 1.25rem; border-radius:10px; margin-bottom:1.25rem; display:flex; gap:.75rem; font-size:.875rem; }
.alert-error { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
.alert-success { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
.success-box { text-align:center; padding:2.5rem; }
.success-icon { width:72px; height:72px; border-radius:50%; background:#dcfce7; color:#16a34a; font-size:2rem; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; }
.success-box h3 { font-size:1.4rem; font-weight:800; color:var(--text-dark); margin-bottom:.5rem; }
.success-box p { color:var(--text-light); margin-bottom:1.5rem; }
</style>
</head>
<body>

<!-- Navigation -->
<header id="mainHeader">
    <nav class="container">
        <a href="index.php" class="logo"><i class="fas fa-graduation-cap"></i><span>RETI</span></a>
        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="news.php">News</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="enroll.php" class="nav-cta active">Apply Now</a></li>
        </ul>
        <div class="menu-toggle" id="menuToggle"><span></span><span></span><span></span></div>
    </nav>
</header>

<section style="background:linear-gradient(135deg,#f0fdf4,#f8fafc);padding:4rem 0 2rem;margin-top:70px;">
<div class="enroll-page">
    <div class="enroll-header" data-aos="fade-up">
        <div class="badge-pill"><i class="fas fa-user-graduate"></i> Application Form</div>
        <h1>Apply to RETI</h1>
        <p>Fill in the form below to enroll in your chosen program. Our admissions team will contact you within 2 working days.</p>
    </div>

    <div class="enroll-card" data-aos="fade-up" data-aos-delay="100">
        <div class="enroll-card-header">
            <h2><i class="fas fa-clipboard-list"></i> Enrollment Application</h2>
            <p>All fields marked with * are required</p>
        </div>
        <div class="enroll-body">

            <?php if ($success): ?>
            <div class="success-box">
                <div class="success-icon"><i class="fas fa-check"></i></div>
                <h3>Application Submitted!</h3>
                <p>Thank you for applying to <?= htmlspecialchars($site_name) ?>. We have received your application and will be in touch shortly.</p>
                <a href="index.php" class="btn-primary" style="display:inline-flex;align-items:center;gap:.5rem;padding:.85rem 2rem;background:var(--gradient);color:white;border-radius:10px;font-weight:700;text-decoration:none;"><i class="fas fa-home"></i> Back to Home</a>
            </div>
            <?php else: ?>

            <?php if (!empty($errors)): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i>
                <ul style="margin:0;padding:0;list-style:none;"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-user"></i> Personal Information</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name <span class="req">*</span></label>
                            <input type="text" name="full_name" class="form-control" required
                                   value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" placeholder="As on NRC/passport">
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control"
                                   value="<?= htmlspecialchars($_POST['date_of_birth'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email Address <span class="req">*</span></label>
                            <input type="email" name="email" class="form-control" required
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="your@email.com">
                        </div>
                        <div class="form-group">
                            <label>Phone Number <span class="req">*</span></label>
                            <input type="tel" name="phone" class="form-control" required
                                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" placeholder="+260 XXX XXX XXX">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Highest Education Level</label>
                        <select name="education_level" class="form-control">
                            <option value="">Select level…</option>
                            <?php foreach (['Grade 9','Grade 12','Certificate','Diploma','Degree','Other'] as $lvl): ?>
                            <option value="<?= $lvl ?>" <?= ($_POST['education_level']??'') === $lvl ? 'selected' : '' ?>><?= $lvl ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-book-open"></i> Course Selection</div>
                    <div class="form-group">
                        <label>Course <span class="req">*</span></label>
                        <select name="course_id" class="form-control" required>
                            <option value="">Select a course…</option>
                            <?php
                            $grouped = [];
                            foreach ($all_courses as $c) { $grouped[$c['category']][] = $c; }
                            foreach ($grouped as $cat => $items):
                                $cat_lbl = $cat_labels[$cat] ?? $cat;
                            ?>
                            <optgroup label="<?= htmlspecialchars($cat_lbl) ?>">
                                <?php foreach ($items as $c): ?>
                                <option value="<?= $c['id'] ?>"
                                        <?= ((int)($_POST['course_id'] ?? $pre_course)) === $c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['title']) ?><?= $c['duration'] ? ' (' . $c['duration'] . ')' : '' ?>
                                </option>
                                <?php endforeach; ?>
                            </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-comment-alt"></i> Additional Information</div>
                    <div class="form-group">
                        <label>Why do you want to enroll in this program?</label>
                        <textarea name="message" class="form-control" placeholder="Tell us a bit about your motivation and goals…"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Application
                </button>
                <p style="text-align:center;font-size:.8rem;color:#94a3b8;margin-top:1rem;">
                    By submitting this form you agree to be contacted by <?= htmlspecialchars($site_name) ?>.
                </p>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</section>

<!-- Footer -->
<footer style="background:#0f172a;color:rgba(255,255,255,.7);padding:2rem 0;text-align:center;font-size:.875rem;">
    <div class="container">
        <p style="margin:0;">&copy; <?= date('Y') ?> <?= htmlspecialchars($site_name) ?>. All rights reserved. &nbsp;|&nbsp;
        <a href="admin/" style="color:rgba(255,255,255,.4);text-decoration:none;">Admin</a></p>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.js"></script>
<script>
AOS.init({ duration:700, once:true });
// Mobile menu toggle (same as other pages)
const toggle = document.getElementById('menuToggle');
const nav    = document.getElementById('navLinks');
if (toggle) toggle.addEventListener('click', () => { nav.classList.toggle('open'); toggle.classList.toggle('active'); });
</script>
</body>
</html>
