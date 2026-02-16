<?php
require_once 'includes/functions.php';

// Get pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 6;
$offset = ($page - 1) * $per_page;

// Get total news count
try {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM news WHERE status = 'published'");
    $stmt->execute();
    $total_news = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
} catch(PDOException $e) {
    $total_news = 0;
}

// Get news for current page
try {
    $stmt = $conn->prepare("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->execute([$per_page, $offset]);
    $news_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $news_items = [];
}

// Calculate pagination
$total_pages = ceil($total_news / $per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle(); ?></title>
    <meta name="description" content="Stay updated with the latest news and announcements from Rising East Training Institute. Learn about new programs, achievements, and events.">
    
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
                <li><a href="courses.php">Courses</a></li>
                <li><a href="news.php" class="active">News</a></li>
                <li><a href="contact.php">Contact</a></li>
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 900;">News & Updates</h1>
            <p style="font-size: 1.3rem; max-width: 700px; margin: 0 auto; font-weight: 500;">Stay informed about our latest programs, achievements, and announcements</p>
        </div>
    </section>

    <!-- News Section -->
    <section class="news-section">
        <div class="container">
            <?php if (count($news_items) > 0): ?>
                <div class="news-grid">
                    <?php foreach ($news_items as $news): ?>
                        <article id="news-<?php echo $news['id']; ?>" class="news-card">
                            <div class="news-image">
                                <img src="<?php echo $news['image_url'] ?: 'assets/images/news-placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($news['title']); ?>">
                            </div>
                            <div class="news-content">
                                <div class="news-date">
                                    <i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($news['created_at'])); ?>
                                </div>
                                <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                                <p><?php echo truncateText(htmlspecialchars($news['excerpt'] ?: $news['content']), 200); ?></p>
                                <div style="margin-top: 1rem;">
                                    <button class="btn btn-secondary" onclick="toggleNewsContent(<?php echo $news['id']; ?>)">
                                        <i class="fas fa-plus"></i> Read More
                                    </button>
                                </div>
                                
                                <!-- Full content (hidden by default) -->
                                <div id="news-content-<?php echo $news['id']; ?>" style="display: none; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                                    <p><?php echo nl2br(htmlspecialchars($news['content'])); ?></p>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div style="display: flex; justify-content: center; align-items: center; gap: 1rem; margin-top: 3rem;">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>" class="btn btn-secondary">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        <?php endif; ?>
                        
                        <span style="color: var(--text-light);">
                            Page <?php echo $page; ?> of <?php echo $total_pages; ?>
                        </span>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?>" class="btn btn-secondary">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="text-center" style="padding: 3rem;">
                    <i class="fas fa-newspaper" style="font-size: 4rem; color: var(--text-light); margin-bottom: 1rem;"></i>
                    <h3>No News Available</h3>
                    <p>There are currently no news articles. Please check back later for updates.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; padding: 4rem 0;">
        <div class="container text-center">
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Stay Updated</h2>
            <p style="font-size: 1.125rem; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                Subscribe to our newsletter to receive the latest news, course updates, and special announcements directly in your inbox.
            </p>
            
            <form id="newsletterForm" style="display: flex; gap: 1rem; max-width: 500px; margin: 0 auto;" onsubmit="handleNewsletterSubmit(event)">
                <input type="email" id="newsletterEmail" placeholder="Enter your email address" required 
                       style="flex: 1; padding: 1rem; border: none; border-radius: 0.5rem; font-size: 1rem;">
                <button type="submit" class="btn btn-secondary" style="background: white; color: var(--primary-color); padding: 1rem 2rem;">
                    Subscribe
                </button>
            </form>
            
            <div id="newsletterMessage" style="margin-top: 1rem;"></div>
        </div>
    </section>

    <!-- Recent Updates Section -->
    <section class="recent-updates" style="background: var(--bg-light);">
        <div class="container">
            <div class="section-title">
                <h2>Recent Updates</h2>
                <p>Quick updates and important announcements</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div class="update-card" style="background: white; padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow-md);">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <i class="fas fa-calendar-check" style="font-size: 1.5rem; color: white;"></i>
                    </div>
                    <h3>Academic Calendar 2025</h3>
                    <p>The academic calendar for 2025 is now available. Download your copy to stay updated with important dates.</p>
                    <a href="#" class="btn btn-primary" style="margin-top: 1rem;">Download Calendar</a>
                </div>
                
                <div class="update-card" style="background: white; padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow-md);">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <i class="fas fa-trophy" style="font-size: 1.5rem; color: white;"></i>
                    </div>
                    <h3>Achievement Awards</h3>
                    <p>Congratulations to our outstanding students who received excellence awards in various categories.</p>
                    <a href="#" class="btn btn-primary" style="margin-top: 1rem;">View Winners</a>
                </div>
                
                <div class="update-card" style="background: white; padding: 2rem; border-radius: 1rem; box-shadow: var(--shadow-md);">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        <i class="fas fa-handshake" style="font-size: 1.5rem; color: white;"></i>
                    </div>
                    <h3>New Partnerships</h3>
                    <p>We've established new partnerships with leading companies to enhance internship opportunities for our students.</p>
                    <a href="#" class="btn btn-primary" style="margin-top: 1rem;">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" style="background: var(--bg-light); padding: 4rem 0;">
        <div class="container text-center">
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Have Questions?</h2>
            <p style="font-size: 1.125rem; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                Our team is here to help you with any inquiries about our programs, admissions, or campus life.
            </p>
            <a href="contact.php" class="btn btn-primary">
                <i class="fas fa-phone"></i> Contact Us
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
                <p>&copy; <?php echo date('Y'); ?> <?php echo getSiteSetting('site_title'); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
    
    <script>
        // Toggle news content
        function toggleNewsContent(newsId) {
            const content = document.getElementById(`news-content-${newsId}`);
            const button = event.target.closest('button');
            const icon = button.querySelector('i');
            
            if (content.style.display === 'none') {
                content.style.display = 'block';
                button.innerHTML = '<i class="fas fa-minus"></i> Read Less';
            } else {
                content.style.display = 'none';
                button.innerHTML = '<i class="fas fa-plus"></i> Read More';
            }
        }
        
        // Handle newsletter subscription
        function handleNewsletterSubmit(event) {
            event.preventDefault();
            
            const email = document.getElementById('newsletterEmail').value;
            const messageDiv = document.getElementById('newsletterMessage');
            
            // Simulate subscription
            messageDiv.innerHTML = '<div style="color: white; padding: 0.5rem;"><i class="fas fa-spinner fa-spin"></i> Subscribing...</div>';
            
            setTimeout(() => {
                messageDiv.innerHTML = '<div style="color: white; padding: 0.5rem;"><i class="fas fa-check-circle"></i> Thank you for subscribing! Check your email for confirmation.</div>';
                document.getElementById('newsletterForm').reset();
                
                // Clear message after 5 seconds
                setTimeout(() => {
                    messageDiv.innerHTML = '';
                }, 5000);
            }, 2000);
        }
    </script>
</body>
</html>
