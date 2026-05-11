<?php
require_once 'includes/functions.php';

// Fetch featured courses (latest 3 active courses)
$featured_courses = [];
try {
    $stmt = $conn->query("SELECT * FROM courses WHERE is_active=1 AND is_featured=1 ORDER BY sort_order ASC, id DESC LIMIT 3");
    $featured_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) { $featured_courses = []; }

// Fetch testimonials
$testimonials = [];
try {
    $stmt = $conn->query("SELECT * FROM testimonials WHERE is_active=1 ORDER BY sort_order ASC LIMIT 5");
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) { $testimonials = []; }

// Fetch latest news
$latest_news = [];
try {
    $stmt = $conn->query("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    $latest_news = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) { $latest_news = []; }

$site_title = getSiteSetting('site_title') ?: 'Rising East Training Institute';
$graduates_count = getSiteSetting('graduates_count') ?: '5000';
$courses_count = getSiteSetting('courses_count') ?: '50';
$success_rate = getSiteSetting('success_rate') ?: '95';
$years_experience = getSiteSetting('years_experience') ?: '10';

$category_labels = [
    'level3' => 'Level III',
    'skills_award' => 'Skills Award',
    'short_course' => 'Short Course'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_title); ?> - Get Your Education Today</title>
    <meta name="description" content="<?php echo htmlspecialchars(getSiteSetting('site_description') ?: 'TEVETA-accredited vocational training in Lusaka, Zambia'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body>

<!-- Page Loader -->
<div id="pageLoader">
    <div class="loader-inner">
        <div class="loader-logo"><i class="fas fa-graduation-cap"></i></div>
        <div class="loader-bar"><div class="loader-progress"></div></div>
        <p>Loading RETI...</p>
    </div>
</div>

<!-- Navigation -->
<header id="mainHeader">
    <nav class="container">
        <a href="index.php" class="logo">
            <i class="fas fa-graduation-cap"></i>
            <span>RETI</span>
        </a>
        <ul class="nav-links" id="navLinks">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="news.php">News</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="enroll.php" class="nav-cta">Apply Now</a></li>
        </ul>
        <div class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </div>
    </nav>
</header>

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="hero-bg-slider">
        <div class="hero-slide active" style="background-image: url('assets/images/20250610_165923.jpg')"></div>
        <div class="hero-slide" style="background-image: url('assets/images/20250617_100130.jpg')"></div>
        <div class="hero-slide" style="background-image: url('assets/images/FB_IMG_1753195579557.jpg')"></div>
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content container">
        <div class="hero-badge" data-aos="fade-down" data-aos-delay="100">
            <i class="fas fa-award"></i> TEVETA Accredited Institution
        </div>
        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">
            Get Your<br><span class="hero-highlight">Education</span> Today!
        </h1>
        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="300">
            <?php echo htmlspecialchars(getSiteSetting('site_tagline') ?: 'STRIVING TO REDUCE THE SKILLS GAP IN THE CONSTRUCTION INDUSTRY'); ?>
        </p>
        <div class="hero-actions" data-aos="fade-up" data-aos-delay="400">
            <a href="courses.php" class="btn btn-primary btn-lg">
                <i class="fas fa-book-open"></i> Explore Courses
            </a>
            <a href="contact.php" class="btn btn-outline-white btn-lg">
                <i class="fas fa-paper-plane"></i> Apply Now
            </a>
        </div>
        <div class="hero-stats" data-aos="fade-up" data-aos-delay="500">
            <div class="stat-item">
                <div class="stat-number" data-target="<?php echo (int)$graduates_count; ?>">0</div>
                <div class="stat-label"><i class="fas fa-user-graduate"></i> Graduates</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-number" data-target="<?php echo (int)$courses_count; ?>">0</div>
                <div class="stat-label"><i class="fas fa-book"></i> Courses</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-number" data-target="<?php echo (int)$success_rate; ?>">0</div>
                <div class="stat-label"><i class="fas fa-chart-line"></i> Success Rate %</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-number" data-target="<?php echo (int)$years_experience; ?>">0</div>
                <div class="stat-label"><i class="fas fa-calendar-check"></i> Years Experience</div>
            </div>
        </div>
    </div>
    <div class="hero-scroll-indicator">
        <a href="#about" class="scroll-down">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
</section>

<!-- About Preview Section -->
<section id="about" class="about-preview">
    <div class="container">
        <div class="about-grid">
            <div class="about-images" data-aos="fade-right">
                <div class="img-main">
                    <img src="assets/images/20250610_165923.jpg" alt="RETI Training Facility" loading="lazy">
                </div>
                <div class="img-secondary">
                    <img src="assets/images/20250617_100130.jpg" alt="RETI Students" loading="lazy">
                    <div class="img-badge">
                        <i class="fas fa-star"></i>
                        <span>TEVETA<br>Accredited</span>
                    </div>
                </div>
            </div>
            <div class="about-content" data-aos="fade-left">
                <span class="section-tag">About RETI</span>
                <h2>Our Motto</h2>
                <p class="motto-text"><?php echo htmlspecialchars(getSiteSetting('site_tagline') ?: 'STRIVING TO REDUCE THE SKILLS GAP IN THE CONSTRUCTION INDUSTRY'); ?></p>
                <p>Rising East Training Institute (RETI) is a TEVETA-accredited vocational and professional training center based in Lusaka, Zambia. We bridge the national skills gap in construction, engineering, and technical fields through practical, industry-relevant education.</p>
                <div class="about-features-grid">
                    <div class="about-feature">
                        <div class="feature-icon"><i class="fas fa-graduation-cap"></i></div>
                        <div>
                            <h4>Expert Instructors</h4>
                            <p>Industry professionals with real-world experience</p>
                        </div>
                    </div>
                    <div class="about-feature">
                        <div class="feature-icon"><i class="fas fa-certificate"></i></div>
                        <div>
                            <h4>TEVETA Certified</h4>
                            <p>Nationally recognized accreditation</p>
                        </div>
                    </div>
                    <div class="about-feature">
                        <div class="feature-icon"><i class="fas fa-tools"></i></div>
                        <div>
                            <h4>Hands-on Training</h4>
                            <p>Modern equipment and workshops</p>
                        </div>
                    </div>
                    <div class="about-feature">
                        <div class="feature-icon"><i class="fas fa-briefcase"></i></div>
                        <div>
                            <h4>Job Placement</h4>
                            <p>Career support and employer network</p>
                        </div>
                    </div>
                </div>
                <a href="about.php" class="btn btn-primary">
                    Learn More About Us <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Program Types -->
<section class="program-types">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">What We Offer</span>
            <h2>Our Training Programmes</h2>
            <p>Choose the programme that fits your goals and timeline</p>
        </div>
        <div class="program-cards" data-aos="fade-up" data-aos-delay="100">
            <div class="program-card program-level3">
                <div class="program-icon"><i class="fas fa-university"></i></div>
                <h3>Level III Programmes</h3>
                <p>12-month comprehensive programmes leading to a full TEVETA Level III Certificate. Ideal for school leavers seeking in-depth technical qualifications.</p>
                <ul>
                    <li><i class="fas fa-check"></i> 12 months duration</li>
                    <li><i class="fas fa-check"></i> Full TEVETA Certificate</li>
                    <li><i class="fas fa-check"></i> Grade 12 entry requirement</li>
                </ul>
                <a href="courses.php#level3" class="program-link">View Programmes <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="program-card program-skills featured-program">
                <div class="featured-badge">Most Popular</div>
                <div class="program-icon"><i class="fas fa-award"></i></div>
                <h3>Skills Award Programmes</h3>
                <p>6-month focused programmes earning a TEVETA Skills Award. Perfect for those who want industry-ready skills in a shorter timeframe.</p>
                <ul>
                    <li><i class="fas fa-check"></i> 6 months duration</li>
                    <li><i class="fas fa-check"></i> TEVETA Skills Award</li>
                    <li><i class="fas fa-check"></i> Grade 9 entry requirement</li>
                </ul>
                <a href="courses.php#skills" class="program-link">View Programmes <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="program-card program-short">
                <div class="program-icon"><i class="fas fa-bolt"></i></div>
                <h3>Short Courses</h3>
                <p>2–8 week intensive courses for rapid skill development. Open to all backgrounds with minimal entry requirements. Get certified fast.</p>
                <ul>
                    <li><i class="fas fa-check"></i> 2–8 weeks duration</li>
                    <li><i class="fas fa-check"></i> Completion Certificate</li>
                    <li><i class="fas fa-check"></i> No formal entry requirement</li>
                </ul>
                <a href="courses.php#short" class="program-link">View Programmes <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courses -->
<section id="courses" class="featured-courses">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">2025/2026 Programmes</span>
            <h2>Featured Courses</h2>
            <p>Explore our most popular training programmes for this academic year</p>
        </div>
        <div class="courses-grid">
            <?php if (!empty($featured_courses)): ?>
                <?php foreach ($featured_courses as $i => $course): ?>
                <div class="course-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                    <div class="course-image">
                        <img src="<?php echo htmlspecialchars($course['image_url'] ?: 'assets/images/course-placeholder.jpg'); ?>"
                             alt="<?php echo htmlspecialchars($course['title']); ?>" loading="lazy">
                        <span class="course-badge badge-<?php echo htmlspecialchars($course['category']); ?>">
                            <?php echo $category_labels[$course['category']] ?? 'Course'; ?>
                        </span>
                        <div class="course-hover-overlay">
                            <a href="courses.php" class="btn btn-white-sm">View Details</a>
                        </div>
                    </div>
                    <div class="course-body">
                        <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                        <p><?php echo htmlspecialchars(truncateText($course['short_description'] ?: $course['description'], 120)); ?></p>
                        <div class="course-meta-row">
                            <span class="meta-item"><i class="fas fa-clock"></i> <?php echo htmlspecialchars($course['duration']); ?></span>
                            <span class="meta-item price-tag">
                                <?php echo $course['price'] > 0 ? 'ZMW ' . number_format($course['price'], 0) : 'Contact Us'; ?>
                            </span>
                        </div>
                        <a href="contact.php?course=<?php echo urlencode($course['title']); ?>" class="btn btn-primary w-full">
                            Enroll Now <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback static cards -->
                <div class="course-card" data-aos="fade-up">
                    <div class="course-image">
                        <img src="assets/images/FB_IMG_1753195579557.jpg" alt="Electrical Training">
                        <span class="course-badge badge-level3">Level III</span>
                    </div>
                    <div class="course-body">
                        <h3>Electrical Installation Level III</h3>
                        <p>Comprehensive TEVETA-accredited electrical program for residential, commercial and industrial systems.</p>
                        <div class="course-meta-row">
                            <span class="meta-item"><i class="fas fa-clock"></i> 12 months</span>
                            <span class="meta-item price-tag">ZMW 15,000</span>
                        </div>
                        <a href="contact.php" class="btn btn-primary w-full">Enroll Now <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="section-cta" data-aos="fade-up">
            <a href="courses.php" class="btn btn-outline-primary btn-lg">
                View All Courses <i class="fas fa-th-large"></i>
            </a>
        </div>
    </div>
</section>

<!-- Downloads Banner -->
<section class="downloads-banner">
    <div class="container">
        <div class="downloads-content" data-aos="zoom-in">
            <div class="downloads-text">
                <h2><i class="fas fa-download"></i> Download Application Documents</h2>
                <p>Get our 2025/2026 Application Form and Course Catalogue to start your journey</p>
            </div>
            <div class="downloads-actions">
                <a href="<?php echo htmlspecialchars(getSiteSetting('application_form_url') ?: '#'); ?>" class="btn btn-white" target="_blank" rel="noopener">
                    <i class="fas fa-file-pdf"></i> Application Form
                </a>
                <a href="<?php echo htmlspecialchars(getSiteSetting('course_list_url') ?: '#'); ?>" class="btn btn-outline-white" target="_blank" rel="noopener">
                    <i class="fas fa-list"></i> Course Catalogue
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<?php if (!empty($testimonials)): ?>
<section class="testimonials-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">Success Stories</span>
            <h2>What Our Graduates Say</h2>
            <p>Hear from the people who have transformed their careers with RETI</p>
        </div>
        <div class="testimonials-slider" data-aos="fade-up" data-aos-delay="100">
            <div class="testimonials-track" id="testimonialsTrack">
                <?php foreach ($testimonials as $i => $t): ?>
                <div class="testimonial-slide <?php echo $i === 0 ? 'active' : ''; ?>">
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <?php for ($s = 0; $s < (int)$t['rating']; $s++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <blockquote>"<?php echo htmlspecialchars($t['testimonial']); ?>"</blockquote>
                        <div class="testimonial-author">
                            <img src="<?php echo htmlspecialchars($t['image_url'] ?: 'assets/images/course-placeholder.jpg'); ?>"
                                 alt="<?php echo htmlspecialchars($t['student_name']); ?>" loading="lazy">
                            <div class="author-details">
                                <strong><?php echo htmlspecialchars($t['student_name']); ?></strong>
                                <span><?php echo htmlspecialchars($t['course_taken']); ?></span>
                                <?php if (!empty($t['current_employer'])): ?>
                                <span class="employer"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($t['current_employer']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="testimonial-nav">
                <button class="t-prev" id="tPrev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                <div class="t-dots" id="tDots">
                    <?php foreach ($testimonials as $i => $t): ?>
                    <button class="t-dot <?php echo $i === 0 ? 'active' : ''; ?>" data-index="<?php echo $i; ?>"></button>
                    <?php endforeach; ?>
                </div>
                <button class="t-next" id="tNext" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Latest News -->
<?php if (!empty($latest_news)): ?>
<section class="latest-news">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">Stay Updated</span>
            <h2>Latest News & Events</h2>
            <p>Keep up with the latest happenings at Rising East Training Institute</p>
        </div>
        <div class="news-grid">
            <?php foreach ($latest_news as $i => $item): ?>
            <div class="news-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                <div class="news-image">
                    <img src="<?php echo htmlspecialchars($item['image_url'] ?: 'assets/images/news-placeholder.jpg'); ?>"
                         alt="<?php echo htmlspecialchars($item['title']); ?>" loading="lazy">
                    <span class="news-date"><i class="far fa-calendar"></i> <?php echo date('d M Y', strtotime($item['created_at'])); ?></span>
                </div>
                <div class="news-body">
                    <?php if (!empty($item['tags'])): ?>
                    <div class="news-tags">
                        <?php foreach (array_slice(explode(',', $item['tags']), 0, 2) as $tag): ?>
                        <span class="tag"><?php echo htmlspecialchars(trim($tag)); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p><?php echo htmlspecialchars(truncateText($item['excerpt'] ?: strip_tags($item['content']), 120)); ?></p>
                    <a href="news.php?id=<?php echo $item['id']; ?>" class="read-more">
                        Read More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="section-cta" data-aos="fade-up">
            <a href="news.php" class="btn btn-outline-primary btn-lg">View All News <i class="fas fa-newspaper"></i></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="cta-section">
    <div class="cta-bg"></div>
    <div class="container">
        <div class="cta-content" data-aos="zoom-in">
            <span class="section-tag light">Ready to Start?</span>
            <h2>Begin Your Journey With RETI Today</h2>
            <p>Join thousands of graduates who have transformed their careers through quality vocational training. Limited spaces available for 2025/2026 intake.</p>
            <div class="cta-actions">
                <a href="contact.php" class="btn btn-white btn-lg">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </a>
                <a href="tel:+260211296071" class="btn btn-outline-white btn-lg">
                    <i class="fas fa-phone"></i> Call Us Today
                </a>
            </div>
            <div class="cta-contact-info">
                <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars(getSiteSetting('contact_phone') ?: '0211-296071'); ?></span>
                <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars(getSiteSetting('contact_email') ?: 'academic@reti.edu.zm'); ?></span>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="main-footer">
    <div class="footer-top">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <i class="fas fa-graduation-cap"></i>
                        <span>RETI</span>
                    </div>
                    <p><?php echo htmlspecialchars($site_title); ?><br>BUILDING SKILLS THAT OPEN DOORS TO EMPLOYMENT AND ENTERPRISE.</p>
                    <div class="social-links">
                        <a href="<?php echo htmlspecialchars(getSiteSetting('facebook_url') ?: '#'); ?>" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo htmlspecialchars(getSiteSetting('twitter_url') ?: '#'); ?>" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="<?php echo htmlspecialchars(getSiteSetting('instagram_url') ?: '#'); ?>" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="<?php echo htmlspecialchars(getSiteSetting('linkedin_url') ?: '#'); ?>" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <?php $wa = getSiteSetting('whatsapp_number'); if ($wa && $wa !== '#'): ?>
                        <a href="https://wa.me/<?php echo htmlspecialchars($wa); ?>" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="about.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
                        <li><a href="courses.php"><i class="fas fa-chevron-right"></i> Our Courses</a></li>
                        <li><a href="news.php"><i class="fas fa-chevron-right"></i> News & Events</a></li>
                        <li><a href="contact.php"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Our Programmes</h4>
                    <ul>
                        <li><a href="courses.php#level3"><i class="fas fa-chevron-right"></i> Level III Programmes</a></li>
                        <li><a href="courses.php#skills"><i class="fas fa-chevron-right"></i> Skills Award Programmes</a></li>
                        <li><a href="courses.php#short"><i class="fas fa-chevron-right"></i> Short Courses</a></li>
                        <li><a href="<?php echo htmlspecialchars(getSiteSetting('application_form_url') ?: '#'); ?>" target="_blank"><i class="fas fa-chevron-right"></i> Application Form</a></li>
                        <li><a href="<?php echo htmlspecialchars(getSiteSetting('course_list_url') ?: '#'); ?>" target="_blank"><i class="fas fa-chevron-right"></i> Course Catalogue</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact Info</h4>
                    <div class="footer-contact">
                        <div class="contact-line">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo htmlspecialchars(getSiteSetting('contact_address') ?: 'EAST CAMPUS, PLOT A150, OFF MUNALI ROAD, LUSAKA, ZAMBIA'); ?></span>
                        </div>
                        <div class="contact-line">
                            <i class="fas fa-phone"></i>
                            <div>
                                <a href="tel:+260<?php echo preg_replace('/[^0-9]/', '', getSiteSetting('contact_phone') ?: '211296071'); ?>"><?php echo htmlspecialchars(getSiteSetting('contact_phone') ?: '0211-296071'); ?></a><br>
                                <?php if (getSiteSetting('contact_phone_2')): ?><a href="tel:+260<?php echo preg_replace('/[^0-9]/', '', getSiteSetting('contact_phone_2')); ?>"><?php echo htmlspecialchars(getSiteSetting('contact_phone_2')); ?></a><?php endif; ?>
                            </div>
                        </div>
                        <div class="contact-line">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <a href="mailto:<?php echo htmlspecialchars(getSiteSetting('contact_email') ?: 'academic@reti.edu.zm'); ?>"><?php echo htmlspecialchars(getSiteSetting('contact_email') ?: 'academic@reti.edu.zm'); ?></a><br>
                                <?php if (getSiteSetting('contact_email_2')): ?><a href="mailto:<?php echo htmlspecialchars(getSiteSetting('contact_email_2')); ?>"><?php echo htmlspecialchars(getSiteSetting('contact_email_2')); ?></a><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($site_title); ?>. All rights reserved. | Designed with <i class="fas fa-heart"></i> for quality education</p>
            <p><a href="admin/index.php">Admin Login</a></p>
        </div>
    </div>
</footer>

<!-- Back to Top -->
<button id="backToTop" aria-label="Back to top"><i class="fas fa-chevron-up"></i></button>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
// Initialize AOS
AOS.init({ duration: 800, once: true, offset: 80 });

// Page Loader
window.addEventListener('load', () => {
    const loader = document.getElementById('pageLoader');
    if (loader) {
        setTimeout(() => { loader.classList.add('hidden'); }, 600);
    }
});

// Mobile Menu
const menuToggle = document.getElementById('menuToggle');
const navLinks = document.getElementById('navLinks');
menuToggle.addEventListener('click', () => {
    menuToggle.classList.toggle('active');
    navLinks.classList.toggle('open');
});
document.querySelectorAll('#navLinks a').forEach(link => {
    link.addEventListener('click', () => {
        menuToggle.classList.remove('active');
        navLinks.classList.remove('open');
    });
});

// Sticky Header
const header = document.getElementById('mainHeader');
window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 50);
});

// Animated Counters
const counters = document.querySelectorAll('.stat-number');
let counted = false;
const statsSection = document.querySelector('.hero-stats');
if (statsSection) {
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && !counted) {
            counted = true;
            counters.forEach(counter => {
                const target = parseInt(counter.dataset.target);
                let current = 0;
                const step = target / 80;
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) { current = target; clearInterval(timer); }
                    counter.textContent = Math.floor(current).toLocaleString() + (counter.dataset.suffix || '');
                }, 20);
            });
        }
    }, { threshold: 0.5 });
    observer.observe(statsSection);
}

// Hero Background Slider
const heroSlides = document.querySelectorAll('.hero-slide');
let currentSlide = 0;
setInterval(() => {
    heroSlides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide + 1) % heroSlides.length;
    heroSlides[currentSlide].classList.add('active');
}, 5000);

// Testimonials Slider
const tTrack = document.getElementById('testimonialsTrack');
if (tTrack) {
    const slides = tTrack.querySelectorAll('.testimonial-slide');
    const dots = document.querySelectorAll('.t-dot');
    let current = 0;
    function goTo(n) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }
    document.getElementById('tPrev').addEventListener('click', () => goTo(current - 1));
    document.getElementById('tNext').addEventListener('click', () => goTo(current + 1));
    dots.forEach((dot, i) => dot.addEventListener('click', () => goTo(i)));
    setInterval(() => goTo(current + 1), 6000);
    // Touch swipe
    let tx = 0;
    tTrack.addEventListener('touchstart', e => tx = e.touches[0].clientX);
    tTrack.addEventListener('touchend', e => {
        const diff = tx - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) goTo(diff > 0 ? current + 1 : current - 1);
    });
}

// Back to Top
const btt = document.getElementById('backToTop');
window.addEventListener('scroll', () => btt.classList.toggle('visible', window.scrollY > 400));
btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
    });
});
</script>
</body>
</html>
