<?php
require_once 'includes/functions.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = cleanInput($_POST['name'] ?? '');
    $email = cleanInput($_POST['email'] ?? '');
    $phone = cleanInput($_POST['phone'] ?? '');
    $subject = cleanInput($_POST['subject'] ?? '');
    $message = cleanInput($_POST['message'] ?? '');
    $course = cleanInput($_POST['course'] ?? '');
    
    $errors = [];
    
    // Validation
    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    }
    
    if (empty($subject)) {
        $errors[] = 'Subject is required';
    }
    
    if (empty($message)) {
        $errors[] = 'Message is required';
    }
    
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $subject, $message]);
            
            // Send email notification (optional)
            $email_subject = "New Contact Form Submission: " . $subject;
            $email_body = "
                <h2>New Contact Form Submission</h2>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Phone:</strong> {$phone}</p>
                <p><strong>Course Interest:</strong> {$course}</p>
                <p><strong>Subject:</strong> {$subject}</p>
                <p><strong>Message:</strong></p>
                <p>" . nl2br($message) . "</p>
            ";
            
            // sendEmail(getSiteSetting('contact_email'), $email_subject, $email_body, $email);
            
            setFlashMessage('success', 'Thank you for your message! We will get back to you soon.');
            header('Location: contact.php?status=success');
            exit();
            
        } catch(PDOException $e) {
            $errors[] = 'Sorry, something went wrong. Please try again later.';
        }
    }
    
    if (!empty($errors)) {
        setFlashMessage('error', implode('<br>', $errors));
    }
}

// Get course from URL parameter
$selected_course = isset($_GET['course']) ? cleanInput($_GET['course']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle(); ?></title>
    <meta name="description" content="Contact Rising East Training Institute for inquiries about our TEVETA accredited courses, admissions, and more. Visit our campus or get in touch via phone or email.">
    
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
                <li><a href="news.php">News</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>
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
            <h1 style="font-size: 3.5rem; margin-bottom: 1rem; font-weight: 900;">Get In Touch</h1>
            <p style="font-size: 1.3rem; max-width: 700px; margin: 0 auto; font-weight: 500;">We'd love to hear from you. Send us a message and we'll respond as soon as possible</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; margin-top: 3rem;">
                <!-- Contact Form -->
                <div class="contact-form-container">
                    <h2>Send Us a Message</h2>
                    <p>Fill out the form below and we'll get back to you as soon as possible.</p>
                    
                    <?php if (hasFlashMessage('success')): ?>
                        <div class="alert alert-success">
                            <?php echo getFlashMessage('success'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (hasFlashMessage('error')): ?>
                        <div class="alert alert-error">
                            <?php echo getFlashMessage('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                        <div class="alert alert-success">
                            Thank you for your message! We will get back to you soon.
                        </div>
                    <?php endif; ?>
                    
                    <form id="contactForm" method="POST" action="contact.php" style="margin-top: 2rem;">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control" required 
                                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control" required 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-control" 
                                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="course">Course Interest</label>
                            <select id="course" name="course" class="form-control">
                                <option value="">Select a course (optional)</option>
                                <option value="Electrical Installation" <?php echo $selected_course === 'Electrical Installation' ? 'selected' : ''; ?>>Electrical Installation</option>
                                <option value="Plumbing and Pipe Fitting" <?php echo $selected_course === 'Plumbing and Pipe Fitting' ? 'selected' : ''; ?>>Plumbing and Pipe Fitting</option>
                                <option value="Customer Care Service" <?php echo $selected_course === 'Customer Care Service' ? 'selected' : ''; ?>>Customer Care Service</option>
                                <option value="Air Conditioner Repair & Installation" <?php echo $selected_course === 'Air Conditioner Repair & Installation' ? 'selected' : ''; ?>>Air Conditioner Repair & Installation</option>
                                <option value="Excavator Operations" <?php echo $selected_course === 'Excavator Operations' ? 'selected' : ''; ?>>Excavator Operations</option>
                                <option value="General Inquiry">General Inquiry</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" class="form-control" required 
                                   value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" class="form-control" rows="5" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
                
                <!-- Contact Information -->
                <div class="contact-info">
                    <h2>Get in Touch</h2>
                    <p>Reach out to us through any of the following channels:</p>
                    
                    <div style="margin-top: 2rem;">
                        <div class="contact-item" style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                <i class="fas fa-map-marker-alt" style="color: white; font-size: 1.25rem;"></i>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 0.25rem;">Address</h4>
                                <p style="margin: 0; color: var(--text-light);"><?php echo getSiteSetting('contact_address'); ?></p>
                            </div>
                        </div>
                        
                        <div class="contact-item" style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                <i class="fas fa-phone" style="color: white; font-size: 1.25rem;"></i>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 0.25rem;">Phone</h4>
                                <p style="margin: 0; color: var(--text-light);"><?php echo getSiteSetting('contact_phone'); ?></p>
                            </div>
                        </div>
                        
                        <div class="contact-item" style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                <i class="fas fa-envelope" style="color: white; font-size: 1.25rem;"></i>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 0.25rem;">Email</h4>
                                <p style="margin: 0; color: var(--text-light);"><?php echo getSiteSetting('contact_email'); ?></p>
                            </div>
                        </div>
                        
                        <div class="contact-item" style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                                <i class="fas fa-clock" style="color: white; font-size: 1.25rem;"></i>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 0.25rem;">Office Hours</h4>
                                <p style="margin: 0; color: var(--text-light);">
                                    Monday - Friday: 8:00 AM - 5:00 PM<br>
                                    Saturday: 8:00 AM - 12:00 PM<br>
                                    Sunday: Closed
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div style="margin-top: 2rem;">
                        <h3>Follow Us</h3>
                        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                            <a href="#" style="width: 40px; height: 40px; background: #1877f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" style="width: 40px; height: 40px; background: #1da1f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" style="width: 40px; height: 40px; background: #e4405f; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" style="width: 40px; height: 40px; background: #0077b5; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section" style="background: var(--bg-light); padding: 3rem 0;">
        <div class="container">
            <div class="section-title">
                <h2>Find Us</h2>
                <p>Visit our campus for a tour and more information</p>
            </div>
            
            <div style="margin-top: 2rem;">
                <div style="background: white; border-radius: 1rem; overflow: hidden; box-shadow: var(--shadow-md); height: 400px; display: flex; align-items: center; justify-content: center;">
                    <div style="text-align: center; padding: 2rem;">
                        <i class="fas fa-map-marked-alt" style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h3>Interactive Map</h3>
                        <p>East Campus, Plot A150, Off Munali Road<br>Lusaka, Zambia</p>
                        <a href="https://maps.google.com/?q=East+Campus+Plot+A150+Off+Munali+Road+Lusaka+Zambia" target="_blank" class="btn btn-primary" style="margin-top: 1rem;">
                            <i class="fas fa-external-link-alt"></i> Open in Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Downloads Section -->
    <section class="downloads">
        <div class="container">
            <div class="section-title">
                <h2>Download Important Documents</h2>
                <p>Get access to application forms and course information</p>
            </div>
            
            <div style="display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap; margin-top: 2rem;">
                <a href="<?php echo getSiteSetting('application_form_url'); ?>" class="btn btn-primary" target="_blank">
                    <i class="fas fa-download"></i> Download Application Form
                </a>
                <a href="<?php echo getSiteSetting('course_list_url'); ?>" class="btn btn-secondary" target="_blank">
                    <i class="fas fa-list"></i> Download Course List
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq" style="background: var(--bg-light);">
        <div class="container">
            <div class="section-title">
                <h2>Frequently Asked Questions</h2>
                <p>Find answers to common questions about our programs</p>
            </div>
            
            <div style="max-width: 800px; margin: 0 auto; margin-top: 2rem;">
                <div class="faq-item" style="background: white; border-radius: 0.5rem; margin-bottom: 1rem; overflow: hidden; box-shadow: var(--shadow-sm);">
                    <button class="faq-question" style="width: 100%; padding: 1.5rem; text-align: left; background: none; border: none; font-size: 1.125rem; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                        What are the admission requirements?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer" style="padding: 0 1.5rem; max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                        <p style="padding: 1rem 0;">Admission requirements vary by program. Level III programs require a Grade 12 certificate with 5 credits including Mathematics. Skills Award programs require Grade 9 certificate, while short courses have no formal education requirements.</p>
                    </div>
                </div>
                
                <div class="faq-item" style="background: white; border-radius: 0.5rem; margin-bottom: 1rem; overflow: hidden; box-shadow: var(--shadow-sm);">
                    <button class="faq-question" style="width: 100%; padding: 1.5rem; text-align: left; background: none; border: none; font-size: 1.125rem; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                        Are your programs TEVETA accredited?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer" style="padding: 0 1.5rem; max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                        <p style="padding: 1rem 0;">Yes, all our programs are fully accredited by TEVETA (Technical Education, Vocational and Entrepreneurship Training Authority), ensuring quality and recognition in Zambia.</p>
                    </div>
                </div>
                
                <div class="faq-item" style="background: white; border-radius: 0.5rem; margin-bottom: 1rem; overflow: hidden; box-shadow: var(--shadow-sm);">
                    <button class="faq-question" style="width: 100%; padding: 1.5rem; text-align: left; background: none; border: none; font-size: 1.125rem; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                        Do you offer accommodation?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer" style="padding: 0 1.5rem; max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                        <p style="padding: 1rem 0;">Currently, we do not offer on-campus accommodation. However, we can assist students in finding suitable accommodation near the campus.</p>
                    </div>
                </div>
                
                <div class="faq-item" style="background: white; border-radius: 0.5rem; margin-bottom: 1rem; overflow: hidden; box-shadow: var(--shadow-sm);">
                    <button class="faq-question" style="width: 100%; padding: 1.5rem; text-align: left; background: none; border: none; font-size: 1.125rem; font-weight: 600; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                        What payment methods do you accept?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer" style="padding: 0 1.5rem; max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                        <p style="padding: 1rem 0;">We accept cash, bank transfers, and mobile money payments. We also offer flexible payment plans for eligible students.</p>
                    </div>
                </div>
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
        // FAQ Accordion
        document.addEventListener('DOMContentLoaded', () => {
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', () => {
                    const answer = question.nextElementSibling;
                    const icon = question.querySelector('i');
                    const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';
                    
                    // Close all other answers
                    document.querySelectorAll('.faq-answer').forEach(a => {
                        a.style.maxHeight = '0';
                    });
                    document.querySelectorAll('.faq-question i').forEach(i => {
                        i.style.transform = 'rotate(0deg)';
                    });
                    
                    // Toggle current answer
                    if (!isOpen) {
                        answer.style.maxHeight = answer.scrollHeight + 'px';
                        icon.style.transform = 'rotate(180deg)';
                    }
                });
            });
        });
    </script>
</body>
</html>
