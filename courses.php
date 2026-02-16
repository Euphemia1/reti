<?php
require_once 'includes/functions.php';

// Get courses by category
$courses_by_category = [];
$categories = ['level3', 'skills_award', 'short_course'];

foreach ($categories as $category) {
    try {
        $stmt = $conn->prepare("SELECT * FROM courses WHERE category = ? AND status = 'active' ORDER BY title");
        $stmt->execute([$category]);
        $courses_by_category[$category] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $courses_by_category[$category] = [];
    }
}

// Get category titles
$category_titles = [
    'level3' => 'Level III Programmes',
    'skills_award' => 'Skills Award Programmes',
    'short_course' => 'Short Courses'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle(); ?></title>
    <meta name="description" content="Explore our comprehensive range of TEVETA accredited courses including Level III Programmes, Skills Award Programmes, and Short Courses at Rising East Training Institute.">
    
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
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
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="courses.php" class="active">Courses</a></li>
                <li><a href="news.php">News</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="admin/login.php" class="cta-button">Admin</a></li>
            </ul>
            
            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <!-- Page Header -->
    <section class="page-header" style="background: var(--gradient-primary); color: white; padding: 7rem 0 3rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(249, 115, 22, 0.15) 0%, transparent 70%); border-radius: 50%; z-index: 0;"></div>
        <div class="container text-center" style="position: relative; z-index: 1;">
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 900;">Discover Our Courses</h1>
            <p style="font-size: 1.3rem; max-width: 700px; margin: 0 auto; font-weight: 500;">Comprehensive range of TEVETA accredited programs designed for your success</p>
        </div>
    </section>

    <!-- Courses Section -->
    <section class="courses">
        <div class="container">
            <?php foreach ($categories as $category): ?>
                <div id="<?php echo $category === 'short_course' ? 'short' : ($category === 'skills_award' ? 'skills' : 'level3'); ?>" class="category-section" style="margin-bottom: 5rem;">
                    <div class="section-title">
                        <h2><?php echo $category_titles[$category]; ?></h2>
                        <p>
                            <?php 
                            switch($category) {
                                case 'level3':
                                    echo 'Comprehensive programs providing in-depth knowledge and practical skills for career advancement';
                                    break;
                                case 'skills_award':
                                    echo 'Focused programs designed to develop specific industry-relevant skills';
                                    break;
                                case 'short_course':
                                    echo 'Quick, intensive training programs for immediate skill development';
                                    break;
                            }
                            ?>
                        </p>
                    </div>
                    
                    <div class="course-grid">
                        <?php if (count($courses_by_category[$category]) > 0): ?>
                            <?php foreach ($courses_by_category[$category] as $course): ?>
                                <div class="course-card">
                                    <div class="course-image">
                                        <img src="<?php echo $course['image_url'] ?: 'assets/images/course-placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                                        <span class="course-category"><?php echo ucfirst(str_replace('_', ' ', $course['category'])); ?></span>
                                    </div>
                                    <div class="course-content">
                                        <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                                        <p><?php echo htmlspecialchars($course['description']); ?></p>
                                        <div class="course-meta">
                                            <span class="course-duration">
                                                <i class="fas fa-clock"></i> <?php echo htmlspecialchars($course['duration']); ?>
                                            </span>
                                            <span class="course-price">
                                                <?php echo $course['price'] > 0 ? formatCurrency($course['price']) : 'Contact for Price'; ?>
                                            </span>
                                        </div>
                                        <div style="margin-top: 1rem;">
                                            <a href="contact.php?course=<?php echo urlencode($course['title']); ?>" class="btn btn-primary" style="width: 100%;">
                                                Enroll Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center">
                                <p>No <?php echo strtolower($category_titles[$category]); ?> available at the moment. Please check back later.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Admission Requirements Section -->
    <section class="admission-requirements" style="background: var(--bg-light);">
        <div class="container">
            <div class="section-title">
                <h2>Admission Requirements</h2>
                <p>Find out what you need to join our programs</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div class="requirement-card" style="background: white; padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow-md);">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                        <i class="fas fa-user-graduate"></i> Level III Programmes
                    </h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--secondary-color); margin-right: 0.5rem;"></i> Grade 12 School Certificate</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--secondary-color); margin-right: 0.5rem;"></i> Minimum of 5 Credits including Mathematics</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--secondary-color); margin-right: 0.5rem;"></i> Science subjects preferred</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--secondary-color); margin-right: 0.5rem;"></i> Minimum age: 16 years</li>
                    </ul>
                </div>
                
                <div class="requirement-card" style="background: white; padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow-md);">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                        <i class="fas fa-award"></i> Skills Award Programmes
                    </h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--secondary-color); margin-right: 0.5rem;"></i> Grade 9 School Certificate</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--secondary-color); margin-right: 0.5rem;"></i> Basic literacy and numeracy</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--secondary-color); margin-right: 0.5rem;"></i> Minimum age: 15 years</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--secondary-color); margin-right: 0.5rem;"></i> Passion for the chosen field</li>
                    </ul>
                </div>
                
                <div class="requirement-card" style="background: white; padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow-md);">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                        <i class="fas fa-certificate"></i> Short Courses
                    </h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--secondary-color); margin-right: 0.5rem;"></i> No formal education required</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--secondary-color); margin-right: 0.5rem;"></i> Basic reading and writing skills</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--secondary-color); margin-right: 0.5rem;"></i> Minimum age: 18 years</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check" style="color: var(--secondary-color); margin-right: 0.5rem;"></i> Willingness to learn</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; padding: 4rem 0;">
        <div class="container text-center">
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Ready to Start Your Journey?</h2>
            <p style="font-size: 1.125rem; margin-bottom: 2rem; max-width: 800px; margin-left: auto; margin-right: auto;">
                Take the first step towards a rewarding career. Download our application form or contact us for more information.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo getSiteSetting('application_form_url'); ?>" class="btn btn-secondary" style="background: white; color: var(--primary-color);" target="_blank">
                    <i class="fas fa-download"></i> Download Application Form
                </a>
                <a href="contact.php" class="btn btn-secondary" style="background: transparent; color: white; border: 2px solid white;">
                    <i class="fas fa-phone"></i> Contact Us
                </a>
            </div>
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
                        <li><a href="#level3">Level III Programmes</a></li>
                        <li><a href="#skills">Skills Award Programmes</a></li>
                        <li><a href="#short">Short Courses</a></li>
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
                <p>&copy; <?php echo date('Y'); ?> <?php echo getSiteSetting('site_title'); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>
