<?php
require_once 'includes/functions.php';

// Get featured courses for homepage
try {
    $stmt = $conn->prepare("SELECT * FROM courses WHERE status = 'active' ORDER BY created_at DESC LIMIT 6");
    $stmt->execute();
    $featured_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $featured_courses = [];
}

// Get latest news
try {
    $stmt = $conn->prepare("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $latest_news = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $latest_news = [];
}

// Get testimonials
try {
    $stmt = $conn->prepare("SELECT * FROM testimonials WHERE status = 'active' ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $testimonials = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle(); ?></title>
    <meta name="description" content="<?php echo getSiteSetting('site_description'); ?>">
    <meta name="keywords" content="training institute, vocational skills, technical education, Zambia, construction skills">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo getSiteSetting('site_title'); ?>">
    <meta property="og:description" content="<?php echo getSiteSetting('site_description'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "<?php echo getSiteSetting('site_title'); ?>",
        "description": "<?php echo getSiteSetting('site_description'); ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "EAST CAMPUS, PLOT A150, OFF MUNALI ROAD",
            "addressLocality": "Lusaka",
            "postalCode": "33381",
            "addressCountry": "Zambia"
        },
        "telephone": "<?php echo getSiteSetting('contact_phone'); ?>",
        "email": "<?php echo getSiteSetting('contact_email'); ?>"
    }
    </script>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="container">
            <a href="index.php" class="logo">
                <i class="fas fa-graduation-cap"></i>
                RETI
            </a>
            
            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="courses.php">Courses</a></li>
                <li><a href="news.php">News</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            
            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Get Your Education Today!</h1>
                <p>Join RETI now and take the first step toward a skilled, empowered future. Our practical, TEVETA accredited programs are designed to elevate your expertise and position you for meaningful career opportunities in Zambia's evolving industries.</p>
                <div class="hero-buttons">
                    <a href="courses.php" class="btn btn-primary">Explore Courses</a>
                    <a href="contact.php" class="btn btn-secondary">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Motto Section -->
    <section class="motto" style="background: var(--gradient-primary); color: white; padding: 5rem 0; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; right: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(249, 115, 22, 0.15) 0%, transparent 70%); border-radius: 50%; z-index: 0;"></div>
        <div style="position: absolute; bottom: 0; left: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%); border-radius: 50%; z-index: 0;"></div>
        <div class="container text-center" style="position: relative; z-index: 1;">
            <h2 style="font-size: 2.2rem; margin-bottom: 1.5rem; font-weight: 900;">OUR MOTTO</h2>
            <p style="font-size: 1.4rem; font-weight: 700; max-width: 900px; margin: 0 auto; line-height: 1.8; letter-spacing: 0.5px;">
                STRIVING TO REDUCE THE SKILLS GAP IN THE CONSTRUCTION INDUSTRY
            </p>
        </div>
    </section>

    <!-- Featured Courses Section -->
    <section class="courses">
        <div class="container">
            <div class="section-title">
                <h2>2025/2026 Skills Training Programmes On Offer</h2>
                <p>Choose from our comprehensive range of accredited programs designed to meet industry demands</p>
            </div>
            
            <div class="course-grid">
                <?php if (count($featured_courses) > 0): ?>
                    <?php foreach ($featured_courses as $course): ?>
                        <div class="course-card">
                            <div class="course-image">
                                <img src="<?php echo $course['image_url'] ?: 'assets/images/course-placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                                <span class="course-category"><?php echo ucfirst(str_replace('_', ' ', $course['category'])); ?></span>
                            </div>
                            <div class="course-content">
                                <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                                <p><?php echo truncateText(htmlspecialchars($course['description'])); ?></p>
                                <div class="course-meta">
                                    <span class="course-duration">
                                        <i class="fas fa-clock"></i> <?php echo htmlspecialchars($course['duration']); ?>
                                    </span>
                                    <span class="course-price">
                                        <?php echo $course['price'] > 0 ? formatCurrency($course['price']) : 'Contact for Price'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>No courses available at the moment. Please check back later.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center" style="margin-top: 3rem;">
                <a href="courses.php" class="btn btn-primary">View All Courses</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose RETI?</h2>
                <p>We provide high quality, practical, and industry-relevant training</p>
            </div>
            
            <div class="feature-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3>TEVETA Accredited</h3>
                    <p>All our programs are fully accredited by TEVETA, ensuring quality and recognition</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Practical Training</h3>
                    <p>Hands-on experience with modern equipment and real-world projects</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3>Job Ready Skills</h3>
                    <p>Graduate with the skills employers are actively looking for</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Expert Instructors</h3>
                    <p>Learn from industry professionals with years of practical experience</p>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="news">
        <div class="container">
            <div class="section-title">
                <h2>Latest News & Updates</h2>
                <p>Stay informed about our latest programs and achievements</p>
            </div>
            
            <div class="news-grid">
                <?php if (count($latest_news) > 0): ?>
                    <?php foreach ($latest_news as $news): ?>
                        <div class="news-card">
                            <div class="news-image">
                                <img src="<?php echo $news['image_url'] ?: 'assets/images/news-placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($news['title']); ?>">
                            </div>
                            <div class="news-content">
                                <div class="news-date">
                                    <i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($news['created_at'])); ?>
                                </div>
                                <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                                <p><?php echo truncateText(htmlspecialchars($news['excerpt'] ?: $news['content'])); ?></p>
                                <a href="news.php#news-<?php echo $news['id']; ?>" class="btn btn-secondary">Read More</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>No news available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center" style="margin-top: 3rem;">
                <a href="news.php" class="btn btn-primary">View All News</a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials" style="background: var(--bg-light);">
        <div class="container">
            <div class="section-title">
                <h2>What Our Students Say</h2>
                <p>Success stories from our graduates</p>
            </div>
            
            <div class="news-grid">
                <?php if (count($testimonials) > 0): ?>
                    <?php foreach ($testimonials as $testimonial): ?>
                        <div class="news-card">
                            <div class="news-content">
                                <div class="rating" style="color: var(--accent-color); margin-bottom: 1rem;">
                                    <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                                        <i class="fas fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <p>"<?php echo htmlspecialchars($testimonial['testimonial']); ?>"</p>
                                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                                    <strong><?php echo htmlspecialchars($testimonial['student_name']); ?></strong>
                                    <?php if ($testimonial['course_taken']): ?>
                                        <br><small style="color: var(--text-light);"><?php echo htmlspecialchars($testimonial['course_taken']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Downloads Section -->
    <section class="downloads" style="background: linear-gradient(180deg, white 0%, var(--bg-light) 100%); padding: 5.5rem 0;">
        <div class="container">
            <div class="section-title">
                <h2>Download Your Application Form and Course List</h2>
                <p>Get started with your application process</p>
            </div>
            
            <div style="display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo getSiteSetting('application_form_url'); ?>" class="btn btn-primary" target="_blank" style="background: var(--gradient-secondary); display: flex; align-items: center; gap: 0.8rem;">
                    <i class="fas fa-file-pdf" style="font-size: 1.2rem;"></i> Download Application Form
                </a>
                <a href="<?php echo getSiteSetting('course_list_url'); ?>" class="btn btn-secondary" target="_blank" style="background: white; color: var(--primary-color); border: 2px solid var(--primary-color); display: flex; align-items: center; gap: 0.8rem; font-weight: 700;">
                    <i class="fas fa-list" style="font-size: 1.2rem;"></i> Download Course List
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" style="background: var(--gradient-warm); color: white; padding: 5.5rem 0; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%); border-radius: 50%; z-index: 0;"></div>
        <div class="container text-center" style="position: relative; z-index: 1;">
            <h2 style="font-size: 3rem; margin-bottom: 1.5rem; font-weight: 900;">Enroll Now – Don't Miss Out!</h2>
            <p style="font-size: 1.2rem; margin-bottom: 2.5rem; max-width: 900px; margin-left: auto; margin-right: auto; font-weight: 500; line-height: 1.8;">
                RISING EAST TRAINING INSTITUTE. We provide high quality, practical, and industry-relevant training that equips learners with the skills, knowledge, and confidence to succeed in Zambia's growing technical and vocational sectors.
            </p>
            <a href="contact.php" class="btn btn-secondary" style="background: white; color: var(--primary-color); padding: 1.2rem 2.5rem; font-size: 1.1rem; font-weight: 700; box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);">
                Register Now <i class="fas fa-arrow-right" style="margin-left: 0.8rem;"></i>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Rising East Training Institute</h3>
                    <p><?php echo getSiteSetting('site_description'); ?></p>
                    <div style="margin-top: 1rem;">
                        <a href="#" style="color: white; margin-right: 1rem;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" style="color: white; margin-right: 1rem;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: white; margin-right: 1rem;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="color: white;"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="courses.php">Courses</a></li>
                        <li><a href="news.php">News</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Programs</h3>
                    <ul>
                        <li><a href="courses.php#level3">Level III Programmes</a></li>
                        <li><a href="courses.php#skills">Skills Award Programmes</a></li>
                        <li><a href="courses.php#short">Short Courses</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo getSiteSetting('contact_address'); ?></p>
                    <p><i class="fas fa-phone"></i> <?php echo getSiteSetting('contact_phone'); ?></p>
                    <p><i class="fas fa-envelope"></i> <?php echo getSiteSetting('contact_email'); ?></p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo getSiteSetting('site_title'); ?>. All rights reserved. | Building Skills that Open Doors to Employment and Enterprise.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>
