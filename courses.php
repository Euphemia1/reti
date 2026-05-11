<?php
require_once 'includes/functions.php';

$categories = ['level3', 'skills_award', 'short_course'];
$category_titles = [
    'level3'       => 'Level III Programmes',
    'skills_award' => 'Skills Award Programmes',
    'short_course' => 'Short Courses'
];
$category_desc = [
    'level3'       => '12-month in-depth programmes leading to a full TEVETA Level III Certificate',
    'skills_award' => '6-month focused programmes earning a TEVETA Skills Award Certificate',
    'short_course' => '2–8 week intensive courses for rapid skill development and certification'
];

$courses_by_category = [];
foreach ($categories as $cat) {
    try {
        $stmt = $conn->prepare("SELECT * FROM courses WHERE category = ? AND status = 'active' ORDER BY sort_order ASC, title ASC");
        $stmt->execute([$cat]);
        $courses_by_category[$cat] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) { $courses_by_category[$cat] = []; }
}

$site_title = getSiteSetting('site_title') ?: 'Rising East Training Institute';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - <?php echo htmlspecialchars($site_title); ?></title>
    <meta name="description" content="Explore our TEVETA-accredited courses: Level III Programmes, Skills Awards, and Short Courses in construction, electrical, plumbing, and more.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body>
<header id="mainHeader" class="scrolled">
    <nav class="container">
        <a href="index.php" class="logo"><i class="fas fa-graduation-cap"></i><span>RETI</span></a>
        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="courses.php" class="active">Courses</a></li>
            <li><a href="news.php">News</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="enroll.php" class="nav-cta">Apply Now</a></li>
        </ul>
        <div class="menu-toggle" id="menuToggle"><span></span><span></span><span></span></div>
    </nav>
</header>

<div class="page-header">
    <div class="container">
        <h1>Our Courses</h1>
        <p>TEVETA-accredited programmes for the 2025/2026 academic year. Find the right course for your career goals.</p>
        <div class="breadcrumb"><a href="index.php">Home</a><span><i class="fas fa-chevron-right"></i></span><span>Courses</span></div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="category-filter">
    <div class="container">
        <div class="filter-tabs">
            <button class="filter-tab active" data-target="all">All Programmes</button>
            <button class="filter-tab" data-target="level3">Level III</button>
            <button class="filter-tab" data-target="skills_award">Skills Award</button>
            <button class="filter-tab" data-target="short_course">Short Courses</button>
        </div>
    </div>
</div>

<!-- Courses -->
<section class="courses-page">
    <div class="container">
        <?php foreach ($categories as $cat): ?>
        <div id="<?php echo $cat === 'short_course' ? 'short' : ($cat === 'skills_award' ? 'skills' : 'level3'); ?>"
             class="category-section" data-category="<?php echo $cat; ?>">
            <div class="category-title" data-aos="fade-up">
                <h2><?php echo $category_titles[$cat]; ?></h2>
                <span class="cat-badge cat-badge-<?php echo $cat === 'level3' ? 'level3' : ($cat === 'skills_award' ? 'skills' : 'short'); ?>">
                    <?php echo $cat === 'level3' ? '12 Months' : ($cat === 'skills_award' ? '6 Months' : '2–8 Weeks'); ?>
                </span>
            </div>
            <p class="text-light" style="margin-bottom:2rem;" data-aos="fade-up"><?php echo $category_desc[$cat]; ?></p>

            <?php if (!empty($courses_by_category[$cat])): ?>
            <div class="course-grid">
                <?php foreach ($courses_by_category[$cat] as $i => $course): ?>
                <div class="course-card" data-aos="fade-up" data-aos-delay="<?php echo min($i * 80, 300); ?>">
                    <div class="course-image">
                        <img src="<?php echo htmlspecialchars($course['image_url'] ?: 'assets/images/course-placeholder.jpg'); ?>"
                             alt="<?php echo htmlspecialchars($course['title']); ?>" loading="lazy">
                        <span class="course-badge badge-<?php echo $cat; ?>">
                            <?php echo $cat === 'level3' ? 'Level III' : ($cat === 'skills_award' ? 'Skills Award' : 'Short Course'); ?>
                        </span>
                        <div class="course-hover-overlay">
                            <button class="btn-white-sm" onclick="showModal(<?php echo $course['id']; ?>)">Quick View</button>
                        </div>
                    </div>
                    <div class="course-body">
                        <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                        <p><?php echo htmlspecialchars(truncateText($course['short_description'] ?: $course['description'], 110)); ?></p>
                        <div class="course-meta-row">
                            <span class="meta-item"><i class="fas fa-clock"></i> <?php echo htmlspecialchars($course['duration']); ?></span>
                            <span class="meta-item price-tag">
                                <?php echo $course['price'] > 0 ? 'ZMW ' . number_format($course['price'], 0) : 'Contact Us'; ?>
                            </span>
                        </div>
                        <?php if (!empty($course['intake_dates'])): ?>
                        <p class="intake-info"><i class="fas fa-calendar-alt"></i> Intakes: <?php echo htmlspecialchars($course['intake_dates']); ?></p>
                        <?php endif; ?>
                        <a href="contact.php?course=<?php echo urlencode($course['title']); ?>" class="btn btn-primary w-full">
                            Enroll Now <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <p>No <?php echo strtolower($category_titles[$cat]); ?> currently available. Please check back soon.</p>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Admission Requirements -->
<section style="background:var(--bg-light);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">Entry Requirements</span>
            <h2>Admission Requirements</h2>
            <p>Entry requirements vary by programme level. Find out what you need to join.</p>
        </div>
        <div class="requirements-grid" data-aos="fade-up" data-aos-delay="100">
            <div class="req-card">
                <h3><i class="fas fa-university"></i> Level III Programmes</h3>
                <ul>
                    <li><i class="fas fa-check"></i> Grade 12 School Certificate</li>
                    <li><i class="fas fa-check"></i> Minimum 5 credits including Mathematics</li>
                    <li><i class="fas fa-check"></i> Science subjects preferred</li>
                    <li><i class="fas fa-check"></i> Minimum age: 16 years</li>
                    <li><i class="fas fa-check"></i> Completed application form</li>
                </ul>
            </div>
            <div class="req-card" style="border-top-color:var(--secondary);">
                <h3 style="color:var(--secondary);"><i class="fas fa-award"></i> Skills Award Programmes</h3>
                <ul>
                    <li><i class="fas fa-check"></i> Grade 9 School Certificate</li>
                    <li><i class="fas fa-check"></i> Basic literacy and numeracy</li>
                    <li><i class="fas fa-check"></i> Minimum age: 15 years</li>
                    <li><i class="fas fa-check"></i> Interest in chosen field</li>
                    <li><i class="fas fa-check"></i> Completed application form</li>
                </ul>
            </div>
            <div class="req-card" style="border-top-color:var(--primary-light);">
                <h3 style="color:var(--primary-light);"><i class="fas fa-bolt"></i> Short Courses</h3>
                <ul>
                    <li><i class="fas fa-check"></i> No formal education required</li>
                    <li><i class="fas fa-check"></i> Basic reading and writing skills</li>
                    <li><i class="fas fa-check"></i> Minimum age: 18 years</li>
                    <li><i class="fas fa-check"></i> Willingness to learn</li>
                    <li><i class="fas fa-check"></i> Registration fee payment</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Downloads CTA -->
<section class="downloads-banner">
    <div class="container">
        <div class="downloads-content" data-aos="zoom-in">
            <div class="downloads-text">
                <h2><i class="fas fa-download"></i> Get the Full Course Catalogue</h2>
                <p>Download our 2025/2026 Application Form and full Course List</p>
            </div>
            <div class="downloads-actions">
                <a href="<?php echo htmlspecialchars(getSiteSetting('application_form_url') ?: '#'); ?>" class="btn btn-white" target="_blank">
                    <i class="fas fa-file-pdf"></i> Application Form
                </a>
                <a href="<?php echo htmlspecialchars(getSiteSetting('course_list_url') ?: '#'); ?>" class="btn btn-outline-white" target="_blank">
                    <i class="fas fa-list"></i> Course Catalogue
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Course Detail Modals -->
<?php foreach ($categories as $cat): ?>
<?php foreach ($courses_by_category[$cat] as $course): ?>
<div id="modal-<?php echo $course['id']; ?>" class="course-modal" role="dialog" aria-modal="true">
    <div class="modal-overlay" onclick="closeModal(<?php echo $course['id']; ?>)"></div>
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal(<?php echo $course['id']; ?>)"><i class="fas fa-times"></i></button>
        <div class="modal-image">
            <img src="<?php echo htmlspecialchars($course['image_url'] ?: 'assets/images/course-placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
            <span class="course-badge badge-<?php echo $course['category']; ?>"><?php echo $cat === 'level3' ? 'Level III' : ($cat === 'skills_award' ? 'Skills Award' : 'Short Course'); ?></span>
        </div>
        <div class="modal-body">
            <h2><?php echo htmlspecialchars($course['title']); ?></h2>
            <div class="modal-meta">
                <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($course['duration']); ?></span>
                <span><i class="fas fa-tag"></i> <?php echo $course['price'] > 0 ? 'ZMW ' . number_format($course['price'], 0) : 'Contact for Price'; ?></span>
                <?php if (!empty($course['intake_dates'])): ?><span><i class="fas fa-calendar"></i> <?php echo htmlspecialchars($course['intake_dates']); ?></span><?php endif; ?>
            </div>
            <p><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
            <?php if (!empty($course['requirements'])): ?>
            <h4>Entry Requirements</h4>
            <p><?php echo nl2br(htmlspecialchars($course['requirements'])); ?></p>
            <?php endif; ?>
            <?php if (!empty($course['outcomes'])): ?>
            <h4>What You'll Gain</h4>
            <p><?php echo nl2br(htmlspecialchars($course['outcomes'])); ?></p>
            <?php endif; ?>
            <a href="contact.php?course=<?php echo urlencode($course['title']); ?>" class="btn btn-primary w-full" style="margin-top:1rem;">
                <i class="fas fa-paper-plane"></i> Apply for This Course
            </a>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endforeach; ?>

<!-- Footer -->
<footer class="main-footer">
    <div class="footer-top"><div class="container"><div class="footer-grid">
        <div class="footer-brand">
            <div class="footer-logo"><i class="fas fa-graduation-cap"></i><span>RETI</span></div>
            <p><?php echo htmlspecialchars($site_title); ?><br>BUILDING SKILLS THAT OPEN DOORS.</p>
            <div class="social-links">
                <a href="<?php echo htmlspecialchars(getSiteSetting('facebook_url') ?: '#'); ?>"><i class="fab fa-facebook-f"></i></a>
                <a href="<?php echo htmlspecialchars(getSiteSetting('instagram_url') ?: '#'); ?>"><i class="fab fa-instagram"></i></a>
                <a href="<?php echo htmlspecialchars(getSiteSetting('linkedin_url') ?: '#'); ?>"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
        <div class="footer-col"><h4>Quick Links</h4><ul>
            <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
            <li><a href="about.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
            <li><a href="courses.php"><i class="fas fa-chevron-right"></i> Courses</a></li>
            <li><a href="news.php"><i class="fas fa-chevron-right"></i> News</a></li>
            <li><a href="contact.php"><i class="fas fa-chevron-right"></i> Contact</a></li>
        </ul></div>
        <div class="footer-col"><h4>Programmes</h4><ul>
            <li><a href="#level3"><i class="fas fa-chevron-right"></i> Level III Programmes</a></li>
            <li><a href="#skills"><i class="fas fa-chevron-right"></i> Skills Award Programmes</a></li>
            <li><a href="#short"><i class="fas fa-chevron-right"></i> Short Courses</a></li>
        </ul></div>
        <div class="footer-col"><h4>Contact</h4><div class="footer-contact">
            <div class="contact-line"><i class="fas fa-phone"></i><a href="tel:+260211296071"><?php echo htmlspecialchars(getSiteSetting('contact_phone') ?: '0211-296071'); ?></a></div>
            <div class="contact-line"><i class="fas fa-envelope"></i><a href="mailto:<?php echo htmlspecialchars(getSiteSetting('contact_email') ?: 'academic@reti.edu.zm'); ?>"><?php echo htmlspecialchars(getSiteSetting('contact_email') ?: 'academic@reti.edu.zm'); ?></a></div>
        </div></div>
    </div></div></div>
    <div class="footer-bottom"><div class="container">
        <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($site_title); ?>. All rights reserved.</p>
        <p><a href="admin/index.php">Admin</a></p>
    </div></div>
</footer>
<button id="backToTop"><i class="fas fa-chevron-up"></i></button>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
AOS.init({duration:800,once:true,offset:80});
const menuToggle=document.getElementById('menuToggle'),navLinks=document.getElementById('navLinks');
menuToggle.addEventListener('click',()=>{menuToggle.classList.toggle('active');navLinks.classList.toggle('open');});
const btt=document.getElementById('backToTop');
window.addEventListener('scroll',()=>btt.classList.toggle('visible',window.scrollY>400));
btt.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));

// Category filter
document.querySelectorAll('.filter-tab').forEach(tab=>{
    tab.addEventListener('click',()=>{
        document.querySelectorAll('.filter-tab').forEach(t=>t.classList.remove('active'));
        tab.classList.add('active');
        const target=tab.dataset.target;
        document.querySelectorAll('.category-section').forEach(sec=>{
            if(target==='all'||sec.dataset.category===target){
                sec.style.display='block';
            } else {
                sec.style.display='none';
            }
        });
    });
});

// Modal
function showModal(id){
    document.getElementById('modal-'+id).classList.add('open');
    document.body.style.overflow='hidden';
}
function closeModal(id){
    document.getElementById('modal-'+id).classList.remove('open');
    document.body.style.overflow='';
}
document.addEventListener('keydown',e=>{if(e.key==='Escape') document.querySelectorAll('.course-modal.open').forEach(m=>m.classList.remove('open'));});
</script>
<style>
.intake-info{font-size:0.8rem;color:var(--text-light);margin-bottom:1rem;display:flex;align-items:center;gap:0.3rem;}
.intake-info i{color:var(--primary);}
.empty-state{text-align:center;padding:3rem;color:var(--text-light);}
.empty-state i{font-size:3rem;color:var(--border);margin-bottom:1rem;display:block;}
.course-modal{display:none;position:fixed;inset:0;z-index:2000;align-items:center;justify-content:center;padding:1rem;}
.course-modal.open{display:flex;}
.modal-overlay{position:absolute;inset:0;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);}
.modal-content{position:relative;z-index:1;background:white;border-radius:var(--radius-xl);max-width:700px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 32px 80px rgba(0,0,0,0.3);}
.modal-image{position:relative;height:250px;overflow:hidden;border-radius:var(--radius-xl) var(--radius-xl) 0 0;}
.modal-image img{width:100%;height:100%;object-fit:cover;}
.modal-close{position:absolute;top:1rem;right:1rem;z-index:2;background:rgba(0,0,0,0.5);color:white;border:none;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;transition:var(--transition);}
.modal-close:hover{background:rgba(0,0,0,0.8);}
.modal-body{padding:2rem;}
.modal-body h2{font-size:1.5rem;margin-bottom:0.75rem;}
.modal-body h4{font-size:1rem;margin:1.25rem 0 0.5rem;color:var(--primary);}
.modal-meta{display:flex;flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem;}
.modal-meta span{font-size:0.85rem;color:var(--text-light);display:flex;align-items:center;gap:0.4rem;}
.modal-meta i{color:var(--primary);}
</style>
</body>
</html>
