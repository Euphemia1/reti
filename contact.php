<?php
require_once 'includes/functions.php';

// Get courses for dropdown
$courses_list = [];
try {
    $stmt = $conn->query("SELECT id, title, category FROM courses WHERE status = 'active' ORDER BY category, title");
    $courses_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {}

$success = false;
$errors = [];
$form = ['name'=>'','email'=>'','phone'=>'','subject'=>'','message'=>'','course'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['name']    = cleanInput($_POST['name'] ?? '');
    $form['email']   = cleanInput($_POST['email'] ?? '');
    $form['phone']   = cleanInput($_POST['phone'] ?? '');
    $form['subject'] = cleanInput($_POST['subject'] ?? '');
    $form['message'] = cleanInput($_POST['message'] ?? '');
    $form['course']  = cleanInput($_POST['course'] ?? '');

    if (empty($form['name']))    $errors[] = 'Full name is required.';
    if (empty($form['email']))   $errors[] = 'Email address is required.';
    elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if (empty($form['subject'])) $errors[] = 'Subject is required.';
    if (empty($form['message'])) $errors[] = 'Message is required.';

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, course_interest) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$form['name'], $form['email'], $form['phone'], $form['subject'], $form['message'], $form['course']]);
            $success = true;
            $form = ['name'=>'','email'=>'','phone'=>'','subject'=>'','message'=>'','course'=>''];
        } catch(PDOException $e) {
            $errors[] = 'Sorry, something went wrong. Please try again.';
        }
    }
}

$selected_course = isset($_GET['course']) ? cleanInput($_GET['course']) : '';
if ($selected_course && empty($form['course'])) $form['course'] = $selected_course;

$site_title = getSiteSetting('site_title') ?: 'Rising East Training Institute';
$cat_labels = ['level3'=>'Level III','skills_award'=>'Skills Award','short_course'=>'Short Course'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - <?php echo htmlspecialchars($site_title); ?></title>
    <meta name="description" content="Get in touch with Rising East Training Institute. Apply for courses, ask questions, or visit our campus.">
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
            <li><a href="courses.php">Courses</a></li>
            <li><a href="news.php">News</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
            <li><a href="enroll.php" class="nav-cta">Apply Now</a></li>
        </ul>
        <div class="menu-toggle" id="menuToggle"><span></span><span></span><span></span></div>
    </nav>
</header>

<div class="page-header">
    <div class="container">
        <h1>Get In Touch</h1>
        <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        <div class="breadcrumb"><a href="index.php">Home</a><span><i class="fas fa-chevron-right"></i></span><span>Contact</span></div>
    </div>
</div>

<!-- Contact Info Cards -->
<section style="background:var(--bg-light);padding:2.5rem 0;">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;">
            <div class="info-block" data-aos="fade-up" data-aos-delay="0">
                <div class="info-icon-box"><i class="fas fa-map-marker-alt"></i></div>
                <div><h4>Visit Us</h4><p><?php echo htmlspecialchars(getSiteSetting('contact_address') ?: 'East Campus, Plot A150, Off Munali Road, P.O Box 33381, Lusaka, Zambia'); ?></p></div>
            </div>
            <div class="info-block" data-aos="fade-up" data-aos-delay="80">
                <div class="info-icon-box"><i class="fas fa-phone"></i></div>
                <div><h4>Call Us</h4>
                    <a href="tel:+260211296071"><?php echo htmlspecialchars(getSiteSetting('contact_phone') ?: '0211-296071'); ?></a><br>
                    <?php if (getSiteSetting('contact_phone_2')): ?><a href="tel:+260<?php echo preg_replace('/[^0-9]/','',$_getSiteSetting=''); ?>"><?php echo htmlspecialchars(getSiteSetting('contact_phone_2')); ?></a><?php endif; ?>
                    <?php if (getSiteSetting('contact_phone_3')): ?><br><a href="tel:+260"><?php echo htmlspecialchars(getSiteSetting('contact_phone_3')); ?></a><?php endif; ?>
                </div>
            </div>
            <div class="info-block" data-aos="fade-up" data-aos-delay="160">
                <div class="info-icon-box"><i class="fas fa-envelope"></i></div>
                <div><h4>Email Us</h4>
                    <a href="mailto:<?php echo htmlspecialchars(getSiteSetting('contact_email') ?: 'academic@reti.edu.zm'); ?>"><?php echo htmlspecialchars(getSiteSetting('contact_email') ?: 'academic@reti.edu.zm'); ?></a>
                    <?php if (getSiteSetting('contact_email_2')): ?><br><a href="mailto:<?php echo htmlspecialchars(getSiteSetting('contact_email_2')); ?>"><?php echo htmlspecialchars(getSiteSetting('contact_email_2')); ?></a><?php endif; ?>
                </div>
            </div>
            <div class="info-block" data-aos="fade-up" data-aos-delay="240">
                <div class="info-icon-box"><i class="fas fa-clock"></i></div>
                <div><h4>Office Hours</h4><p><?php echo nl2br(htmlspecialchars(getSiteSetting('office_hours') ?: "Mon–Fri: 8:00 AM – 5:00 PM\nSat: 8:00 AM – 12:00 PM")); ?></p></div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form + Map -->
<section>
    <div class="container">
        <div class="contact-grid">
            <!-- Form -->
            <div class="contact-form-wrap" data-aos="fade-right">
                <h2>Send Us a Message</h2>
                <p style="color:var(--text-light);margin-bottom:1.5rem;">Fill in the form and we'll get back to you as soon as possible.</p>

                <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>Thank you for your message! We will get back to you soon. Check your email for confirmation.</div>
                </div>
                <?php endif; ?>
                <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></div>
                </div>
                <?php endif; ?>

                <form method="POST" action="contact.php" id="contactForm" novalidate>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name <span class="req">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" required
                                   value="<?php echo htmlspecialchars($form['name']); ?>" placeholder="Your full name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address <span class="req">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" required
                                   value="<?php echo htmlspecialchars($form['email']); ?>" placeholder="your@email.com">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-control"
                                   value="<?php echo htmlspecialchars($form['phone']); ?>" placeholder="0900 000 000">
                        </div>
                        <div class="form-group">
                            <label for="course">Course Interest</label>
                            <select id="course" name="course" class="form-control">
                                <option value="">— Select a course (optional) —</option>
                                <?php
                                $current_cat = '';
                                foreach ($courses_list as $c):
                                    if ($c['category'] !== $current_cat) {
                                        if ($current_cat !== '') echo '</optgroup>';
                                        echo '<optgroup label="' . htmlspecialchars($cat_labels[$c['category']] ?? $c['category']) . '">';
                                        $current_cat = $c['category'];
                                    }
                                ?>
                                <option value="<?php echo htmlspecialchars($c['title']); ?>" <?php echo $form['course'] === $c['title'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($c['title']); ?>
                                </option>
                                <?php endforeach; ?>
                                <?php if ($current_cat !== '') echo '</optgroup>'; ?>
                                <option value="General Inquiry" <?php echo $form['course'] === 'General Inquiry' ? 'selected' : ''; ?>>General Inquiry</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject <span class="req">*</span></label>
                        <input type="text" id="subject" name="subject" class="form-control" required
                               value="<?php echo htmlspecialchars($form['subject']); ?>" placeholder="What is this about?">
                    </div>
                    <div class="form-group">
                        <label for="message">Message <span class="req">*</span></label>
                        <textarea id="message" name="message" class="form-control" required rows="5"
                                  placeholder="Tell us about your goals, questions, or anything else..."><?php echo htmlspecialchars($form['message']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-full" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>

            <!-- Info + Map -->
            <div data-aos="fade-left">
                <!-- Downloads -->
                <div style="background:var(--gradient);border-radius:var(--radius-xl);padding:2rem;margin-bottom:1.5rem;color:white;">
                    <h3 style="color:white;font-size:1.1rem;margin-bottom:0.5rem;"><i class="fas fa-download"></i> Download Documents</h3>
                    <p style="color:rgba(255,255,255,0.85);font-size:0.875rem;margin-bottom:1.25rem;">Get your application form and course catalogue</p>
                    <div style="display:flex;flex-direction:column;gap:0.75rem;">
                        <a href="<?php echo htmlspecialchars(getSiteSetting('application_form_url') ?: '#'); ?>" class="btn btn-white btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Application Form 2025/2026
                        </a>
                        <a href="<?php echo htmlspecialchars(getSiteSetting('course_list_url') ?: '#'); ?>" class="btn btn-outline-white btn-sm" target="_blank">
                            <i class="fas fa-list"></i> Full Course Catalogue
                        </a>
                    </div>
                </div>

                <!-- Map -->
                <div class="map-block">
                    <div style="height:250px;background:var(--bg-gray);display:flex;align-items:center;justify-content:center;border-radius:var(--radius-lg) var(--radius-lg) 0 0;">
                        <div style="text-align:center;padding:2rem;">
                            <i class="fas fa-map-marked-alt" style="font-size:3rem;color:var(--primary);display:block;margin-bottom:0.75rem;"></i>
                            <h4 style="margin-bottom:0.5rem;">East Campus, Lusaka</h4>
                            <p style="font-size:0.875rem;color:var(--text-light);">Plot A150, Off Munali Road<br>P.O Box 33381, Lusaka, Zambia</p>
                        </div>
                    </div>
                    <div class="map-cta">
                        <a href="https://maps.google.com/?q=East+Campus+Plot+A150+Off+Munali+Road+Lusaka+Zambia" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt"></i> Open in Google Maps
                        </a>
                    </div>
                </div>

                <!-- Social -->
                <div style="background:white;border-radius:var(--radius-lg);padding:1.5rem;box-shadow:var(--shadow-sm);border:1px solid var(--border);margin-top:1.5rem;">
                    <h4 style="margin-bottom:1rem;">Follow Us</h4>
                    <div style="display:flex;gap:0.75rem;">
                        <a href="<?php echo htmlspecialchars(getSiteSetting('facebook_url') ?: '#'); ?>" style="width:44px;height:44px;background:#1877f2;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:1rem;transition:var(--transition);" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo htmlspecialchars(getSiteSetting('twitter_url') ?: '#'); ?>" style="width:44px;height:44px;background:#1da1f2;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:1rem;transition:var(--transition);" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''"><i class="fab fa-twitter"></i></a>
                        <a href="<?php echo htmlspecialchars(getSiteSetting('instagram_url') ?: '#'); ?>" style="width:44px;height:44px;background:linear-gradient(45deg,#405de6,#5851db,#833ab4,#c13584,#e1306c,#fd1d1d);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:1rem;transition:var(--transition);" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''"><i class="fab fa-instagram"></i></a>
                        <a href="<?php echo htmlspecialchars(getSiteSetting('linkedin_url') ?: '#'); ?>" style="width:44px;height:44px;background:#0077b5;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:1rem;transition:var(--transition);" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''"><i class="fab fa-linkedin-in"></i></a>
                        <?php $wa = getSiteSetting('whatsapp_number'); if ($wa && $wa !== '#'): ?>
                        <a href="https://wa.me/<?php echo htmlspecialchars($wa); ?>" style="width:44px;height:44px;background:#25d366;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:1rem;transition:var(--transition);" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''"><i class="fab fa-whatsapp"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section style="background:var(--bg-light);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">FAQ</span>
            <h2>Frequently Asked Questions</h2>
            <p>Quick answers to common questions about RETI programmes and admissions</p>
        </div>
        <div class="faq-list" data-aos="fade-up" data-aos-delay="100">
            <div class="faq-item">
                <button class="faq-question">What are the admission requirements? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer"><div class="faq-answer-inner">Requirements vary by programme. Level III requires Grade 12 with 5 credits including Mathematics. Skills Award requires Grade 9. Short Courses have no formal education requirements. Minimum ages apply (16–18 depending on programme).</div></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Are your programmes TEVETA accredited? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer"><div class="faq-answer-inner">Yes! All RETI programmes are fully accredited by TEVETA (Technical Education, Vocational and Entrepreneurship Training Authority), ensuring nationally recognised certificates valued by employers across Zambia.</div></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">When are the intakes? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer"><div class="faq-answer-inner">Level III and Skills Award programmes typically start in January and July. Short Courses run throughout the year with multiple intake dates per course. Contact us for specific dates.</div></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">What payment options are available? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer"><div class="faq-answer-inner">We accept cash, bank transfers, and mobile money (Airtel and MTN). Flexible payment plans are available for eligible students. Contact our finance office for more details.</div></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Do you offer accommodation? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer"><div class="faq-answer-inner">Currently, we do not offer on-campus accommodation. However, our student support team can help you find suitable accommodation near the campus in Lusaka.</div></div>
            </div>
            <div class="faq-item">
                <button class="faq-question">How do I apply? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer"><div class="faq-answer-inner">Download the application form from this page or visit our campus. Complete the form, attach certified copies of your academic certificates, and submit to our admissions office. You can also enquire using the contact form above.</div></div>
            </div>
        </div>
    </div>
</section>

<footer class="main-footer">
    <div class="footer-top"><div class="container"><div class="footer-grid">
        <div class="footer-brand"><div class="footer-logo"><i class="fas fa-graduation-cap"></i><span>RETI</span></div><p><?php echo htmlspecialchars($site_title); ?><br>BUILDING SKILLS THAT OPEN DOORS.</p><div class="social-links"><a href="#"><i class="fab fa-facebook-f"></i></a><a href="#"><i class="fab fa-twitter"></i></a><a href="#"><i class="fab fa-instagram"></i></a></div></div>
        <div class="footer-col"><h4>Quick Links</h4><ul><li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li><li><a href="about.php"><i class="fas fa-chevron-right"></i> About</a></li><li><a href="courses.php"><i class="fas fa-chevron-right"></i> Courses</a></li><li><a href="news.php"><i class="fas fa-chevron-right"></i> News</a></li><li><a href="contact.php"><i class="fas fa-chevron-right"></i> Contact</a></li></ul></div>
        <div class="footer-col"><h4>Programmes</h4><ul><li><a href="courses.php#level3"><i class="fas fa-chevron-right"></i> Level III</a></li><li><a href="courses.php#skills"><i class="fas fa-chevron-right"></i> Skills Award</a></li><li><a href="courses.php#short"><i class="fas fa-chevron-right"></i> Short Courses</a></li></ul></div>
        <div class="footer-col"><h4>Contact</h4><div class="footer-contact">
            <div class="contact-line"><i class="fas fa-map-marker-alt"></i><span><?php echo htmlspecialchars(getSiteSetting('contact_address') ?: 'East Campus, Lusaka, Zambia'); ?></span></div>
            <div class="contact-line"><i class="fas fa-phone"></i><a href="tel:+260211296071"><?php echo htmlspecialchars(getSiteSetting('contact_phone') ?: '0211-296071'); ?></a></div>
            <div class="contact-line"><i class="fas fa-envelope"></i><a href="mailto:academic@reti.edu.zm"><?php echo htmlspecialchars(getSiteSetting('contact_email') ?: 'academic@reti.edu.zm'); ?></a></div>
        </div></div>
    </div></div></div>
    <div class="footer-bottom"><div class="container"><p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($site_title); ?>. All rights reserved.</p><p><a href="admin/index.php">Admin</a></p></div></div>
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
// FAQ Accordion
document.querySelectorAll('.faq-question').forEach(q=>{
    q.addEventListener('click',()=>{
        const isOpen=q.classList.contains('open');
        document.querySelectorAll('.faq-question.open').forEach(oq=>{
            oq.classList.remove('open');
            oq.nextElementSibling.style.maxHeight='0';
        });
        if(!isOpen){
            q.classList.add('open');
            q.nextElementSibling.style.maxHeight=q.nextElementSibling.scrollHeight+'px';
        }
    });
});
// Submit loading state
document.getElementById('contactForm').addEventListener('submit',function(){
    const btn=document.getElementById('submitBtn');
    btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Sending...';
    btn.disabled=true;
});
</script>
</body>
</html>
