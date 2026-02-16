<?php
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle(); ?></title>
    <meta name="description" content="Learn about Rising East Training Institute - our mission, vision, and commitment to reducing the skills gap in Zambia's construction industry through quality technical and vocational education.">
    
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
                <li><a href="about.php" class="active">About Us</a></li>
                <li><a href="courses.php">Courses</a></li>
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 900;">About Rising East Training Institute</h1>
            <p style="font-size: 1.3rem; max-width: 700px; margin: 0 auto; font-weight: 500;">Empowering Zambia's workforce through quality technical and vocational education</p>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="mission-vision">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; margin-top: 3rem;">
                <div class="mission-card" style="text-align: center; padding: 2rem;">
                    <div class="feature-icon" style="margin: 0 auto 1.5rem;">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h2>Our Mission</h2>
                    <p style="font-size: 1.125rem; line-height: 1.8;">
                        To provide high-quality, practical, and industry-relevant technical and vocational education that equips learners with the skills, knowledge, and confidence to succeed in Zambia's growing industries and contribute to national development.
                    </p>
                </div>
                
                <div class="vision-card" style="text-align: center; padding: 2rem;">
                    <div class="feature-icon" style="margin: 0 auto 1.5rem;">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h2>Our Vision</h2>
                    <p style="font-size: 1.125rem; line-height: 1.8;">
                        To be the leading technical and vocational training institution in Zambia, recognized for producing highly skilled graduates who drive innovation and excellence in the workplace.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Content -->
    <section class="about-content" style="background: var(--bg-light);">
        <div class="container">
            <div class="section-title">
                <h2>Who We Are</h2>
                <p>Learn more about our commitment to excellence in technical education</p>
            </div>
            
            <div style="max-width: 900px; margin: 0 auto;">
                <p style="font-size: 1.125rem; line-height: 1.8; margin-bottom: 2rem;">
                    Rising East Training Institute (RETI) is a premier technical and vocational education institution dedicated to addressing the critical skills gap in Zambia's construction and related industries. Established with a vision to transform the technical education landscape, we have become a beacon of excellence in practical skills development.
                </p>
                
                <p style="font-size: 1.125rem; line-height: 1.8; margin-bottom: 2rem;">
                    Our institute is strategically located in Lusaka, Zambia's capital, making us accessible to students from across the country. We pride ourselves on offering TEVETA-accredited programs that are designed in consultation with industry experts to ensure our graduates meet the current and future needs of the job market.
                </p>
                
                <p style="font-size: 1.125rem; line-height: 1.8;">
                    At RETI, we believe that quality education should be practical, relevant, and transformative. Our state-of-the-art facilities, experienced instructors, and industry partnerships create an ideal learning environment where theory meets practice, and students graduate ready to make immediate contributions to their workplaces.
                </p>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="core-values">
        <div class="container">
            <div class="section-title">
                <h2>Our Core Values</h2>
                <p>The principles that guide everything we do</p>
            </div>
            
            <div class="feature-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Integrity</h3>
                    <p>We conduct ourselves with honesty and transparency in all our dealings, building trust with students, staff, and stakeholders.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Excellence</h3>
                    <p>We strive for the highest standards in teaching, learning, and service delivery, continuously improving our programs and facilities.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Professionalism</h3>
                    <p>We maintain professional standards in all aspects of our operations, preparing students for successful careers.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Innovation</h3>
                    <p>We embrace new technologies and teaching methods to enhance learning outcomes and stay current with industry trends.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Student-Centered</h3>
                    <p>We prioritize student success and well-being, providing personalized support and guidance throughout their learning journey.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-globe-africa"></i>
                    </div>
                    <h3>Community Impact</h3>
                    <p>We are committed to contributing to Zambia's development by producing skilled graduates who drive economic growth.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="facilities" style="background: var(--bg-light);">
        <div class="container">
            <div class="section-title">
                <h2>Our Facilities</h2>
                <p>State-of-the-art learning environments for practical skills development</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div class="facility-card" style="background: white; border-radius: 1rem; overflow: hidden; box-shadow: var(--shadow-md);">
                    <div class="course-image" style="height: 200px;">
                        <img src="assets/images/workshop.jpg" alt="Workshop" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="padding: 1.5rem;">
                        <h3>Modern Workshops</h3>
                        <p>Well-equipped workshops with modern tools and equipment for hands-on training in various trades.</p>
                    </div>
                </div>
                
                <div class="facility-card" style="background: white; border-radius: 1rem; overflow: hidden; box-shadow: var(--shadow-md);">
                    <div class="course-image" style="height: 200px;">
                        <img src="assets/images/classroom.jpg" alt="Classroom" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="padding: 1.5rem;">
                        <h3>Modern Classrooms</h3>
                        <p>Spacious, air-conditioned classrooms with modern teaching aids for effective theoretical learning.</p>
                    </div>
                </div>
                
                <div class="facility-card" style="background: white; border-radius: 1rem; overflow: hidden; box-shadow: var(--shadow-md);">
                    <div class="course-image" style="height: 200px;">
                        <img src="assets/images/library.jpg" alt="Library" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="padding: 1.5rem;">
                        <h3>Resource Center</h3>
                        <p>A well-stocked library and computer lab with internet access for research and self-study.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="statistics">
        <div class="container">
            <div class="section-title">
                <h2>Our Impact</h2>
                <p>Numbers that speak to our commitment and success</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-top: 3rem; text-align: center;">
                <div class="stat-item">
                    <div style="font-size: 3rem; font-weight: 800; color: var(--primary-color); margin-bottom: 0.5rem;" data-target="5000">0</div>
                    <h4>Graduates</h4>
                    <p>Successfully trained and placed in jobs</p>
                </div>
                
                <div class="stat-item">
                    <div style="font-size: 3rem; font-weight: 800; color: var(--primary-color); margin-bottom: 0.5rem;" data-target="95">0</div>
                    <h4>Employment Rate</h4>
                    <p>Of our graduates find employment within 6 months</p>
                </div>
                
                <div class="stat-item">
                    <div style="font-size: 3rem; font-weight: 800; color: var(--primary-color); margin-bottom: 0.5rem;" data-target="25">0</div>
                    <h4>Programs</h4>
                    <p>Diverse courses across multiple trades</p>
                </div>
                
                <div class="stat-item">
                    <div style="font-size: 3rem; font-weight: 800; color: var(--primary-color); margin-bottom: 0.5rem;" data-target="50">0</div>
                    <h4>Partners</h4>
                    <p>Industry partners for internships and jobs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Management Team -->
    <section class="management" style="background: var(--bg-light);">
        <div class="container">
            <div class="section-title">
                <h2>Our Leadership</h2>
                <p>Experienced professionals committed to educational excellence</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div class="team-member" style="background: white; border-radius: 1rem; padding: 2rem; text-align: center; box-shadow: var(--shadow-md);">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-tie" style="font-size: 3rem; color: white;"></i>
                    </div>
                    <h3>Dr. John Banda</h3>
                    <p style="color: var(--primary-color); font-weight: 600;">Principal Director</p>
                    <p>Over 20 years of experience in technical education and institutional management.</p>
                </div>
                
                <div class="team-member" style="background: white; border-radius: 1rem; padding: 2rem; text-align: center; box-shadow: var(--shadow-md);">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-tie" style="font-size: 3rem; color: white;"></i>
                    </div>
                    <h3>Mrs. Mary Phiri</h3>
                    <p style="color: var(--primary-color); font-weight: 600;">Academic Director</p>
                    <p>Expertise in curriculum development and quality assurance in vocational training.</p>
                </div>
                
                <div class="team-member" style="background: white; border-radius: 1rem; padding: 2rem; text-align: center; box-shadow: var(--shadow-md);">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-tie" style="font-size: 3rem; color: white;"></i>
                    </div>
                    <h3>Mr. James Mwila</h3>
                    <p style="color: var(--primary-color); font-weight: 600;">Operations Manager</p>
                    <p>Specializes in facility management and industry partnership development.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; padding: 4rem 0;">
        <div class="container text-center">
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Join Our Community</h2>
            <p style="font-size: 1.125rem; margin-bottom: 2rem; max-width: 800px; margin-left: auto; margin-right: auto;">
                Become part of a growing community of skilled professionals transforming Zambia's technical landscape. Your journey starts here.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="courses.php" class="btn btn-secondary" style="background: white; color: var(--primary-color);">
                    <i class="fas fa-book"></i> Explore Programs
                </a>
                <a href="contact.php" class="btn btn-secondary" style="background: transparent; color: white; border: 2px solid white;">
                    <i class="fas fa-phone"></i> Get in Touch
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
                <p>&copy; <?php echo date('Y'); ?> <?php echo getSiteSetting('site_title'); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
    
    <script>
        // Initialize counter animation
        document.addEventListener('DOMContentLoaded', () => {
            const statItems = document.querySelectorAll('.stat-item [data-target]');
            
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = parseInt(entry.target.getAttribute('data-target'));
                        animateCounter(entry.target, target);
                        counterObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            statItems.forEach(item => {
                counterObserver.observe(item);
            });
        });
    </script>
</body>
</html>
