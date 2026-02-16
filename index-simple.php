<?php
// Simple index page without database dependency
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rising East Institute - Professional Training & Education</title>
    <meta name="description" content="Rising East Institute offers professional training programs, courses, and workshops to empower students with industry-relevant skills.">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <i class="fas fa-graduation-cap"></i>
                <span class="brand-text">Rising East Institute</span>
            </div>
            <div class="nav-menu">
                <a href="index-simple.php" class="nav-link active">Home</a>
                <a href="about.php" class="nav-link">About</a>
                <a href="courses.php" class="nav-link">Courses</a>
                <a href="contact.php" class="nav-link">Contact</a>
            </div>
            <div class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Empowering Your Future with <span class="accent-text">Professional Skills</span></h1>
                <p class="hero-subtitle">Join Rising East Institute for world-class training programs designed to elevate your career and unlock your potential in today's competitive job market.</p>
            </div>
            <div class="hero-actions">
                <button class="btn btn-primary" onclick="window.location.href='courses.php'">
                    <span>Explore Courses</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
                <button class="btn btn-secondary" onclick="window.location.href='contact.php'">
                    <span>Get Started</span>
                    <i class="fas fa-phone"></i>
                </button>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number" data-target="5000">0</div>
                    <div class="stat-label">Graduates</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="50">0</div>
                    <div class="stat-label">Courses</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="95">0</div>
                    <div class="stat-label">Success Rate %</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">About Rising East Institute</h2>
                <p class="section-subtitle">Excellence in Professional Training Since 2020</p>
            </div>
            <div class="about-content">
                <div class="about-text">
                    <p>Rising East Institute is a premier training institution dedicated to providing high-quality, industry-relevant education that prepares students for successful careers. We combine theoretical knowledge with practical hands-on experience to ensure our graduates are job-ready and competitive in today's dynamic job market.</p>
                    <p>Our state-of-the-art facilities, experienced instructors, and comprehensive curriculum create an optimal learning environment for students to develop their skills and achieve their professional goals.</p>
                    <div class="about-features">
                        <div class="feature">
                            <i class="fas fa-graduation-cap"></i>
                            <h3>Expert Instructors</h3>
                            <p>Learn from industry professionals with years of real-world experience</p>
                        </div>
                        <div class="feature">
                            <i class="fas fa-certificate"></i>
                            <h3>Accredited Programs</h3>
                            <p>Nationally recognized certifications that advance your career</p>
                        </div>
                        <div class="feature">
                            <i class="fas fa-briefcase"></i>
                            <h3>Job Placement</h3>
                            <p>Career support and connections to top employers</p>
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <div class="image-container">
                        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80" alt="Modern Training Facility">
                        <div class="image-overlay">
                            <div class="overlay-content">
                                <h3>Modern Learning Environment</h3>
                                <p>State-of-the-art facilities designed for optimal learning</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses Section -->
    <section id="courses" class="courses">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Courses</h2>
                <p class="section-subtitle">Transform Your Career with Our Premium Programs</p>
            </div>
            <div class="courses-grid">
                <div class="course-card" data-aos="fade-up">
                    <div class="course-image">
                        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Electrical Engineering">
                        <div class="course-level">Level III</div>
                    </div>
                    <div class="course-content">
                        <h3 class="course-title">Electrical Engineering</h3>
                        <p class="course-description">Comprehensive training in electrical systems, installation, and maintenance with hands-on practical experience.</p>
                        <div class="course-meta">
                            <span class="duration"><i class="fas fa-clock"></i> 3 Months</span>
                            <span class="certificate"><i class="fas fa-award"></i> Certificate</span>
                        </div>
                        <button class="btn btn-outline" onclick="window.location.href='contact.php'">Enroll Now</button>
                    </div>
                </div>

                <div class="course-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="course-image">
                        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Construction Management">
                        <div class="course-level">Skills Award</div>
                    </div>
                    <div class="course-content">
                        <h3 class="course-title">Construction Management</h3>
                        <p class="course-description">Learn project planning, site management, and construction industry best practices.</p>
                        <div class="course-meta">
                            <span class="duration"><i class="fas fa-clock"></i> 6 Weeks</span>
                            <span class="certificate"><i class="fas fa-award"></i> Certificate</span>
                        </div>
                        <button class="btn btn-outline" onclick="window.location.href='contact.php'">Enroll Now</button>
                    </div>
                </div>

                <div class="course-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="course-image">
                        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Business Management">
                        <div class="course-level">Short Course</div>
                    </div>
                    <div class="course-content">
                        <h3 class="course-title">Business Management</h3>
                        <p class="course-description">Essential business skills including leadership, strategy, and organizational management.</p>
                        <div class="course-meta">
                            <span class="duration"><i class="fas fa-clock"></i> 2 Weeks</span>
                            <span class="certificate"><i class="fas fa-award"></i> Certificate</span>
                        </div>
                        <button class="btn btn-outline" onclick="window.location.href='contact.php'">Enroll Now</button>
                    </div>
                </div>
            </div>
            <div class="courses-action">
                <button class="btn btn-primary" onclick="window.location.href='courses.php'">
                    <span>View All Courses</span>
                    <i class="fas fa-th-large"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-brand">
                        <i class="fas fa-graduation-cap"></i>
                        <p class="footer-description">Empowering students with professional skills and industry-relevant education for successful careers.</p>
                    </div>
                    <div class="footer-links">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="index-simple.php">Home</a></li>
                            <li><a href="about.php">About</a></li>
                            <li><a href="courses.php">Courses</a></li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer-links">
                        <h4>Programs</h4>
                        <ul>
                            <li><a href="#">Level III Programs</a></li>
                            <li><a href="#">Skills Awards</a></li>
                            <li><a href="#">Short Courses</a></li>
                            <li><a href="#">Workshops</a></li>
                        </ul>
                    </div>
                    <div class="footer-links">
                        <h4>Connect</h4>
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; 2026 Rising East Institute. All rights reserved. | Designed with <i class="fas fa-heart"></i> for education excellence</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Animated Counter
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current).toLocaleString();
            }, 20);
        }

        // Initialize Counters
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px'
        };

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.stat-number');
                    counters.forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-target'));
                        animateCounter(counter, target);
                    });
                    counterObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.hero-stats');
        if (statsSection) {
            counterObserver.observe(statsSection);
        }

        // Navigation Toggle
        const navToggle = document.querySelector('.nav-toggle');
        const navMenu = document.querySelector('.nav-menu');

        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            navToggle.classList.toggle('active');
        });

        // Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
