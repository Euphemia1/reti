<?php
require_once 'includes/functions.php';

// Fetch testimonials
$testimonials = [];
try {
    $stmt = $conn->query("SELECT * FROM testimonials WHERE status = 'active' ORDER BY sort_order ASC LIMIT 6");
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) { $testimonials = []; }

// Fetch gallery
$gallery = [];
try {
    $stmt = $conn->query("SELECT * FROM gallery WHERE status = 'active' ORDER BY sort_order ASC LIMIT 8");
    $gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) { $gallery = []; }

$site_title = getSiteSetting('site_title') ?: 'Rising East Training Institute';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - <?php echo htmlspecialchars($site_title); ?></title>
    <meta name="description" content="Learn about Rising East Training Institute - our mission, vision, values, and dedicated team of expert instructors.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <style>
    .mv-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem; }
    .mv-card { background:white; border-radius:var(--radius-xl); padding:2.5rem; text-align:center; box-shadow:var(--shadow-sm); border:1px solid var(--border); transition:var(--transition); }
    .mv-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-lg); }
    .mv-featured { background:var(--gradient); color:white; }
    .mv-featured h3,.mv-featured .mv-icon { color:var(--accent); }
    .mv-featured p { color:rgba(255,255,255,0.9); }
    .mv-icon { font-size:2.5rem; margin-bottom:1.25rem; color:var(--primary); }
    .motto-big { font-size:0.95rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; line-height:1.7; color:rgba(255,255,255,0.95) !important; }
    .stats-mini { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin:1.5rem 0; }
    .stat-mini { text-align:center; background:var(--bg-light); border-radius:var(--radius-md); padding:1rem 0.5rem; }
    .stat-mini-num { display:block; font-size:1.6rem; font-weight:900; color:var(--primary); font-family:'Montserrat',sans-serif; }
    .stat-mini span:last-child { font-size:0.75rem; color:var(--text-light); text-transform:uppercase; letter-spacing:0.5px; }
    .facilities-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem; }
    .facility-card { background:white; border-radius:var(--radius-lg); padding:2rem; box-shadow:var(--shadow-sm); border:1px solid var(--border); transition:var(--transition); }
    .facility-card:hover { transform:translateY(-5px); box-shadow:var(--shadow-md); border-color:var(--primary-lighter); }
    .facility-icon { font-size:2rem; color:var(--primary); margin-bottom:1rem; }
    .facility-card h4 { font-size:1rem; margin-bottom:0.5rem; }
    .facility-card p { font-size:0.875rem; color:var(--text-light); margin:0; }
    .gallery-grid { display:grid; grid-template-columns:repeat(4,1fr); grid-auto-rows:200px; gap:0.75rem; }
    .gallery-wide { grid-column:span 2; grid-row:span 2; }
    .gallery-item { border-radius:var(--radius-md); overflow:hidden; position:relative; cursor:pointer; }
    .gallery-item img { width:100%; height:100%; object-fit:cover; transition:var(--transition); }
    .gallery-item:hover img { transform:scale(1.08); }
    .gallery-overlay { position:absolute; inset:0; background:rgba(15,118,110,0.7); display:flex; align-items:flex-end; padding:1rem; opacity:0; transition:var(--transition); }
    .gallery-item:hover .gallery-overlay { opacity:1; }
    .gallery-overlay span { color:white; font-weight:600; font-size:0.875rem; }
    .testimonials-list { display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem; }
    .testimonial-full-card { background:white; border-radius:var(--radius-xl); padding:2rem; box-shadow:var(--shadow-sm); border:1px solid var(--border); transition:var(--transition); }
    .testimonial-full-card:hover { transform:translateY(-5px); box-shadow:var(--shadow-lg); }
    .testimonial-full-card .testimonial-stars { color:var(--accent); margin-bottom:0.75rem; }
    .testimonial-full-card blockquote { font-size:0.9rem; color:var(--text-body); font-style:italic; line-height:1.7; margin-bottom:1.25rem; }
    @media(max-width:1024px){.mv-grid{grid-template-columns:1fr;}.facilities-grid{grid-template-columns:repeat(2,1fr);}.testimonials-list{grid-template-columns:1fr 1fr;}.gallery-grid{grid-template-columns:repeat(2,1fr);}.gallery-wide{grid-column:span 2;grid-row:span 1;}}
    @media(max-width:768px){.stats-mini{grid-template-columns:repeat(2,1fr);}.testimonials-list{grid-template-columns:1fr;}.gallery-grid{grid-template-columns:1fr 1fr;}.gallery-wide{grid-column:span 2;}.values-grid{grid-template-columns:1fr 1fr;}}
    </style>
</head>
<body>
<header id="mainHeader" class="scrolled">
    <nav class="container">
        <a href="index.php" class="logo"><i class="fas fa-graduation-cap"></i><span>RETI</span></a>
        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php" class="active">About Us</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="news.php">News</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="enroll.php" class="nav-cta">Apply Now</a></li>
        </ul>
        <div class="menu-toggle" id="menuToggle"><span></span><span></span><span></span></div>
    </nav>
</header>

<div class="page-header">
    <div class="container">
        <h1>About RETI</h1>
        <p>Rising East Training Institute — Shaping skilled professionals for Zambia's growing industries</p>
        <div class="breadcrumb"><a href="index.php">Home</a><span><i class="fas fa-chevron-right"></i></span><span>About Us</span></div>
    </div>
</div>

<!-- Mission Vision -->
<section>
    <div class="container">
        <div class="mv-grid">
            <div class="mv-card" data-aos="fade-right">
                <div class="mv-icon"><i class="fas fa-bullseye"></i></div>
                <h3>Our Mission</h3>
                <p>To provide quality, accessible, and industry-relevant vocational training that empowers individuals with skills needed to thrive in Zambia's growing economy.</p>
            </div>
            <div class="mv-card mv-featured" data-aos="fade-up">
                <div class="mv-icon"><i class="fas fa-star"></i></div>
                <h3 style="color:var(--accent);">Our Motto</h3>
                <p class="motto-big"><?php echo htmlspecialchars(getSiteSetting('site_tagline') ?: 'STRIVING TO REDUCE THE SKILLS GAP IN THE CONSTRUCTION INDUSTRY'); ?></p>
            </div>
            <div class="mv-card" data-aos="fade-left">
                <div class="mv-icon"><i class="fas fa-eye"></i></div>
                <h3>Our Vision</h3>
                <p>To be the leading vocational training institution in Zambia, recognized for excellence in practical education and contribution to national economic development.</p>
            </div>
        </div>
    </div>
</section>

<!-- Who We Are -->
<section style="background:var(--bg-light);">
    <div class="container">
        <div class="about-grid">
            <div class="about-images" data-aos="fade-right">
                <div class="img-main"><img src="assets/images/20250610_165923.jpg" alt="RETI Campus" loading="lazy"></div>
                <div class="img-secondary">
                    <img src="assets/images/20250617_100130.jpg" alt="Students Training" loading="lazy">
                    <div class="img-badge"><i class="fas fa-award"></i><span>TEVETA<br>Accredited</span></div>
                </div>
            </div>
            <div class="about-content" data-aos="fade-left">
                <span class="section-tag">Who We Are</span>
                <h2>A Premier Vocational Training Centre</h2>
                <p>Rising East Training Institute (RETI) is a TEVETA-accredited vocational and professional training centre based in Lusaka, Zambia. Established to respond to the growing demand for skilled professionals, RETI bridges the national skills gap in construction, engineering, and technical fields.</p>
                <p>Our programmes combine theoretical knowledge with practical, hands-on training, ensuring graduates are academically prepared and job-ready for today's competitive industries.</p>
                <div class="stats-mini">
                    <div class="stat-mini"><span class="stat-mini-num"><?php echo getSiteSetting('graduates_count') ?: '5000'; ?>+</span><span>Graduates</span></div>
                    <div class="stat-mini"><span class="stat-mini-num"><?php echo getSiteSetting('courses_count') ?: '50'; ?>+</span><span>Courses</span></div>
                    <div class="stat-mini"><span class="stat-mini-num"><?php echo getSiteSetting('success_rate') ?: '95'; ?>%</span><span>Employment</span></div>
                    <div class="stat-mini"><span class="stat-mini-num"><?php echo getSiteSetting('years_experience') ?: '10'; ?>+</span><span>Years</span></div>
                </div>
                <a href="contact.php" class="btn btn-primary">Apply for Admission <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section>
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">What Drives Us</span>
            <h2>Our Core Values</h2>
            <p>The principles that guide everything we do at RETI</p>
        </div>
        <div class="values-grid" data-aos="fade-up" data-aos-delay="100">
            <div class="value-card"><div class="value-icon"><i class="fas fa-shield-alt"></i></div><h4>Integrity</h4><p>We uphold honesty, transparency, and ethical conduct in all our operations.</p></div>
            <div class="value-card"><div class="value-icon"><i class="fas fa-star"></i></div><h4>Excellence</h4><p>We are committed to delivering the highest quality of education and training.</p></div>
            <div class="value-card"><div class="value-icon"><i class="fas fa-briefcase"></i></div><h4>Professionalism</h4><p>We maintain professional standards in teaching, staff conduct, and student support.</p></div>
            <div class="value-card"><div class="value-icon"><i class="fas fa-lightbulb"></i></div><h4>Innovation</h4><p>We continuously evolve programmes to reflect the latest industry trends.</p></div>
            <div class="value-card"><div class="value-icon"><i class="fas fa-user-graduate"></i></div><h4>Student-Centred</h4><p>Every decision we make is guided by the best interests of our students.</p></div>
            <div class="value-card"><div class="value-icon"><i class="fas fa-hands-helping"></i></div><h4>Community Impact</h4><p>We contribute positively to communities through skills development.</p></div>
        </div>
    </div>
</section>

<!-- Facilities -->
<section style="background:var(--bg-light);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">Our Campus</span>
            <h2>World-Class Facilities</h2>
            <p>Modern equipment and learning environments for the best practical training</p>
        </div>
        <div class="facilities-grid" data-aos="fade-up" data-aos-delay="100">
            <div class="facility-card"><div class="facility-icon"><i class="fas fa-tools"></i></div><h4>Modern Workshops</h4><p>State-of-the-art facilities equipped with the latest tools for hands-on training.</p></div>
            <div class="facility-card"><div class="facility-icon"><i class="fas fa-chalkboard-teacher"></i></div><h4>Modern Classrooms</h4><p>Well-equipped classrooms with multimedia facilities for effective instruction.</p></div>
            <div class="facility-card"><div class="facility-icon"><i class="fas fa-hard-hat"></i></div><h4>Safety Training Area</h4><p>Dedicated area for practical safety training and emergency response exercises.</p></div>
            <div class="facility-card"><div class="facility-icon"><i class="fas fa-users"></i></div><h4>Student Lounge</h4><p>Comfortable spaces for students to study, collaborate, and relax.</p></div>
            <div class="facility-card"><div class="facility-icon"><i class="fas fa-book"></i></div><h4>Resource Centre</h4><p>Library with technical manuals, reference materials, and study resources.</p></div>
            <div class="facility-card"><div class="facility-icon"><i class="fas fa-first-aid"></i></div><h4>Health & Safety</h4><p>Fully equipped first aid facilities ensuring student safety at all times.</p></div>
        </div>
    </div>
</section>

<?php if (!empty($gallery)): ?>
<!-- Gallery -->
<section>
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">Gallery</span>
            <h2>Life at RETI</h2>
            <p>A glimpse into our vibrant learning environment and student activities</p>
        </div>
        <div class="gallery-grid" data-aos="fade-up" data-aos-delay="100">
            <?php foreach ($gallery as $i => $img): ?>
            <div class="gallery-item <?php echo $i === 0 ? 'gallery-wide' : ''; ?>">
                <img src="<?php echo htmlspecialchars($img['image_url']); ?>" alt="<?php echo htmlspecialchars($img['title'] ?: 'RETI'); ?>" loading="lazy">
                <?php if (!empty($img['title'])): ?><div class="gallery-overlay"><span><?php echo htmlspecialchars($img['title']); ?></span></div><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($testimonials)): ?>
<!-- Testimonials -->
<section style="background:var(--bg-light);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">Success Stories</span>
            <h2>Hear From Our Graduates</h2>
        </div>
        <div class="testimonials-list" data-aos="fade-up" data-aos-delay="100">
            <?php foreach ($testimonials as $t): ?>
            <div class="testimonial-full-card">
                <div class="testimonial-stars"><?php for ($s=0;$s<(int)$t['rating'];$s++): ?><i class="fas fa-star"></i><?php endfor; ?></div>
                <blockquote>"<?php echo htmlspecialchars($t['testimonial']); ?>"</blockquote>
                <div class="testimonial-author">
                    <img src="<?php echo htmlspecialchars($t['image_url'] ?: 'assets/images/course-placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($t['student_name']); ?>" loading="lazy">
                    <div class="author-details">
                        <strong><?php echo htmlspecialchars($t['student_name']); ?></strong>
                        <span><?php echo htmlspecialchars($t['course_taken']); ?></span>
                        <?php if (!empty($t['current_employer'])): ?><span class="employer"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($t['current_employer']); ?></span><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="cta-section">
    <div class="cta-bg"></div>
    <div class="container">
        <div class="cta-content" data-aos="zoom-in">
            <span class="section-tag light">Join RETI</span>
            <h2>Ready to Start Your Career Journey?</h2>
            <p>Join thousands of graduates. Apply for the 2025/2026 intake today.</p>
            <div class="cta-actions">
                <a href="contact.php" class="btn btn-white btn-lg"><i class="fas fa-paper-plane"></i> Apply Now</a>
                <a href="courses.php" class="btn btn-outline-white btn-lg"><i class="fas fa-book-open"></i> View Courses</a>
            </div>
        </div>
    </div>
</section>

<footer class="main-footer">
    <div class="footer-top"><div class="container"><div class="footer-grid">
        <div class="footer-brand">
            <div class="footer-logo"><i class="fas fa-graduation-cap"></i><span>RETI</span></div>
            <p><?php echo htmlspecialchars($site_title); ?><br>BUILDING SKILLS THAT OPEN DOORS.</p>
            <div class="social-links">
                <a href="<?php echo htmlspecialchars(getSiteSetting('facebook_url') ?: '#'); ?>"><i class="fab fa-facebook-f"></i></a>
                <a href="<?php echo htmlspecialchars(getSiteSetting('twitter_url') ?: '#'); ?>"><i class="fab fa-twitter"></i></a>
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
            <li><a href="courses.php#level3"><i class="fas fa-chevron-right"></i> Level III</a></li>
            <li><a href="courses.php#skills"><i class="fas fa-chevron-right"></i> Skills Award</a></li>
            <li><a href="courses.php#short"><i class="fas fa-chevron-right"></i> Short Courses</a></li>
        </ul></div>
        <div class="footer-col"><h4>Contact</h4><div class="footer-contact">
            <div class="contact-line"><i class="fas fa-map-marker-alt"></i><span><?php echo htmlspecialchars(getSiteSetting('contact_address') ?: 'East Campus, Lusaka, Zambia'); ?></span></div>
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
</script>
</body>
</html>
