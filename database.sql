-- Rising East Training Institute Database Schema
-- Full schema with admin panel support

CREATE DATABASE IF NOT EXISTS risingeast CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE risingeast;

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('super_admin', 'admin', 'editor') DEFAULT 'admin',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    short_description VARCHAR(400),
    category ENUM('level3', 'skills_award', 'short_course') NOT NULL,
    duration VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) DEFAULT 0.00,
    image_url VARCHAR(500),
    requirements TEXT,
    outcomes TEXT,
    intake_dates VARCHAR(300),
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- News/Events table
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    excerpt VARCHAR(400),
    image_url VARCHAR(500),
    author VARCHAR(100) DEFAULT 'RETI Admin',
    tags VARCHAR(300),
    views INT DEFAULT 0,
    status ENUM('published', 'draft') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    course_interest VARCHAR(200),
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Enrollment requests table
CREATE TABLE IF NOT EXISTS enrollment_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    dob DATE,
    gender ENUM('male', 'female', 'other'),
    address TEXT,
    course_id INT,
    course_name VARCHAR(200),
    education_level VARCHAR(100),
    how_heard VARCHAR(100),
    message TEXT,
    status ENUM('pending', 'reviewed', 'accepted', 'rejected') DEFAULT 'pending',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL
);

-- Site settings table
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'textarea', 'url', 'email', 'phone', 'toggle') DEFAULT 'text',
    description VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Gallery images table
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    description TEXT,
    image_url VARCHAR(500) NOT NULL,
    category VARCHAR(50) DEFAULT 'general',
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    course_taken VARCHAR(200),
    testimonial TEXT NOT NULL,
    rating INT DEFAULT 5 CHECK (rating BETWEEN 1 AND 5),
    image_url VARCHAR(500),
    graduation_year VARCHAR(10),
    current_employer VARCHAR(200),
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- DEFAULT DATA
-- =============================================

-- Admin user (password: admin123)
INSERT INTO admin_users (username, password, email, full_name, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@reti.edu.zm', 'RETI Administrator', 'super_admin')
ON DUPLICATE KEY UPDATE email = email;

-- Site settings
INSERT INTO site_settings (setting_key, setting_value, setting_type, description) VALUES
('site_title', 'Rising East Training Institute', 'text', 'Website title shown in browser tab'),
('site_tagline', 'STRIVING TO REDUCE THE SKILLS GAP IN THE CONSTRUCTION INDUSTRY', 'text', 'Tagline/motto'),
('site_description', 'TEVETA-accredited vocational and professional training in Lusaka, Zambia', 'textarea', 'Brief site description for SEO'),
('contact_email', 'academic@reti.edu.zm', 'email', 'Main contact email address'),
('contact_email_2', 'info@reti.edu.zm', 'email', 'Secondary contact email'),
('contact_phone', '0211-296071', 'phone', 'Main phone number'),
('contact_phone_2', '0954-999900', 'phone', 'Secondary phone number'),
('contact_phone_3', '0964-082013', 'phone', 'Third phone number'),
('contact_address', 'EAST CAMPUS, PLOT A150, OFF MUNALI ROAD, P.O BOX 33381, LUSAKA, ZAMBIA', 'textarea', 'Physical address'),
('office_hours', 'Monday - Friday: 8:00 AM - 5:00 PM | Saturday: 8:00 AM - 12:00 PM', 'text', 'Office hours'),
('application_form_url', '/downloads/RISING EAST TRAINING INSTITUTE APPLICATION FORM 2025-2026.pdf', 'url', 'Application form PDF download link'),
('course_list_url', '/downloads/RETI_Courses_2025_2026.pdf', 'url', 'Course list PDF download link'),
('facebook_url', '#', 'url', 'Facebook page URL'),
('twitter_url', '#', 'url', 'Twitter/X profile URL'),
('instagram_url', '#', 'url', 'Instagram profile URL'),
('linkedin_url', '#', 'url', 'LinkedIn page URL'),
('whatsapp_number', '260954999900', 'phone', 'WhatsApp contact number (with country code, no +)'),
('graduates_count', '5000', 'text', 'Number of graduates (for stats display)'),
('courses_count', '50', 'text', 'Number of courses offered'),
('success_rate', '95', 'text', 'Employment success rate percentage'),
('years_experience', '10', 'text', 'Years of experience')
ON DUPLICATE KEY UPDATE description = description;

-- Sample courses
INSERT INTO courses (title, short_description, description, category, duration, price, image_url, requirements, outcomes, intake_dates) VALUES
(
    'Customer Care Service',
    'Build excellent communication and customer service skills for the hospitality and service industry.',
    'Our short training equips learners with essential customer service and front office skills, focusing on communication, professionalism, and client handling to enhance workplace excellence. Students will gain practical experience through role-playing scenarios and real-world case studies. The program covers telephone etiquette, complaint handling, body language, and professional presentation.',
    'short_course', '4 weeks', 1500.00,
    'assets/images/20250715_130414.jpg',
    'No formal education required. Basic reading and writing skills. Minimum age 18.',
    'Professional customer service skills, Telephone and email etiquette, Complaint resolution techniques, Front office management, Certificate of completion',
    'January, April, July, October'
),
(
    'Air Conditioner Repair & Installation',
    'Gain hands-on HVAC skills covering AC installation, maintenance and repair for modern systems.',
    'This practical short course provides hands-on skills in installing, maintaining, and repairing air conditioning systems, preparing learners for opportunities in the growing HVAC industry. Students will work on actual AC units in our well-equipped workshop, learning refrigerant handling, electrical diagnosis, and preventive maintenance.',
    'short_course', '6 weeks', 2500.00,
    'assets/images/FB_IMG_1753195579557.jpg',
    'Basic numeracy skills required. Grade 9 preferred. Minimum age 16.',
    'AC installation and commissioning, Refrigerant handling, Electrical diagnostics, Preventive maintenance, Safety procedures, Certificate of completion',
    'January, March, May, July, September, November'
),
(
    'Excavator Operations',
    'Learn professional excavator operation, safety protocols and maintenance for construction careers.',
    'Our short training equips learners with practical skills in excavator operation, safety, and maintenance, preparing them for careers in construction and earth-moving industries. The course includes both theoretical classroom instruction and extensive hands-on practice on actual excavation equipment. Students learn safety regulations, equipment inspection, and basic maintenance.',
    'short_course', '8 weeks', 3500.00,
    'assets/images/20250617_100130.jpg',
    'Minimum Grade 9 certificate. Good physical health. Clean driving record preferred. Minimum age 18.',
    'Safe excavator operation, Equipment inspection and maintenance, Site safety regulations, Grade reading and site plans, Heavy equipment logbook, Certificate of completion',
    'January, May, September'
),
(
    'Electrical Installation Level III',
    'Comprehensive TEVETA-accredited electrical program for residential, commercial & industrial systems.',
    'Comprehensive electrical installation program covering residential, commercial, and industrial electrical systems with full TEVETA accreditation. This rigorous program combines classroom theory with hands-on practical sessions in our dedicated electrical workshop. Students learn wiring, circuit installation, switchboard assembly, earthing systems, and electrical safety standards compliant with Zambian regulations.',
    'level3', '12 months', 15000.00,
    'assets/images/FB_IMG_1753195579557.jpg',
    'Grade 12 School Certificate. Minimum 5 credits including Mathematics and Science. Minimum age 16.',
    'Full TEVETA Level III Certificate, Residential and commercial wiring, Industrial panel installation, Fault finding and diagnostics, Electrical safety compliance, Job-ready practical skills',
    'January, July'
),
(
    'Plumbing and Pipe Fitting',
    'Professional 6-month plumbing programme covering water supply, drainage and modern pipe systems.',
    'Professional plumbing program covering water supply systems, drainage, and pipe fitting techniques for modern construction. Students receive comprehensive training in both hot and cold water systems, drainage design, and sanitary plumbing. The program includes practical projects where students complete real plumbing installations under supervision.',
    'skills_award', '6 months', 8000.00,
    'assets/images/20250617_113107.jpg',
    'Grade 9 School Certificate. Basic literacy and numeracy. Minimum age 15.',
    'Water supply system installation, Drainage and sanitation systems, Pipe fitting and jointing, Reading plumbing blueprints, TEVETA Skills Award Certificate',
    'January, July'
),
(
    'Metal Fabrication & Welding',
    'Master welding techniques and metal fabrication for construction and manufacturing industries.',
    'This comprehensive program covers MIG, TIG, and arc welding techniques along with metal fabrication skills. Students gain hands-on experience with modern welding equipment and learn to read engineering drawings, perform quality checks, and work safely in fabrication environments.',
    'skills_award', '6 months', 9000.00,
    'assets/images/20250617_100130.jpg',
    'Grade 9 School Certificate. Good eyesight required. Physical fitness. Minimum age 16.',
    'MIG, TIG and Arc welding proficiency, Metal cutting and bending, Engineering drawing interpretation, Quality control, Safety in welding environments, TEVETA Skills Award Certificate',
    'January, July'
),
(
    'Bricklaying & Masonry',
    'Learn professional bricklaying, block-laying and masonry for the construction industry.',
    'Build a solid foundation in construction with our comprehensive bricklaying and masonry program. Students learn traditional and modern techniques for laying bricks, blocks, and stone work. The course covers mortar preparation, levelling, setting out, and decorative finishes used in residential and commercial buildings.',
    'level3', '12 months', 14000.00,
    'assets/images/20250610_165923.jpg',
    'Grade 12 School Certificate. 5 credits including Mathematics. Physical fitness. Minimum age 16.',
    'TEVETA Level III Certificate, Brick and block laying, Mortar preparation and mixing, Setting out and levelling, Foundation work, Decorative masonry',
    'January, July'
);

-- Sample news
INSERT INTO news (title, content, excerpt, image_url, author, tags, status) VALUES
(
    'New Intake Now Open for 2025/2026 Academic Year',
    '<p>We are thrilled to announce that applications for the <strong>2025/2026 academic year</strong> are now officially open at Rising East Training Institute. This is your chance to take the first step toward a rewarding career in the technical and vocational field.</p>
    <p>We are offering a wide range of programmes including Level III Certificates, Skills Awards, and Short Courses covering fields such as electrical installation, plumbing, welding, excavator operations, and much more.</p>
    <h3>How to Apply</h3>
    <ul>
        <li>Download the application form from our website or visit our campus</li>
        <li>Complete all sections of the form accurately</li>
        <li>Attach certified copies of your academic certificates</li>
        <li>Submit your application to our admissions office</li>
    </ul>
    <p>Limited spaces are available, so we encourage all interested students to apply as early as possible. Don\'t miss this opportunity to build a successful career with RETI!</p>
    <p>For more information, contact our admissions office at <strong>0211-296071</strong> or email us at <strong>academic@reti.edu.zm</strong>.</p>',
    'Applications for the 2025/2026 academic year are now open. Limited spaces available — apply today to secure your spot!',
    'assets/images/20250610_165923.jpg',
    'RETI Admissions',
    'admissions,intake,2025,2026',
    'published'
),
(
    'RETI Achieves Full TEVETA Accreditation',
    '<p>Rising East Training Institute is proud to announce that we have successfully achieved <strong>full TEVETA accreditation</strong> for all our programmes. This is a significant milestone in our journey to provide world-class vocational training.</p>
    <p>TEVETA (Technical Education, Vocational and Entrepreneurship Training Authority) accreditation is the highest standard of quality recognition for vocational training institutions in Zambia. This accreditation means that all our certificates are nationally recognised and valued by employers across the country.</p>
    <p>"This accreditation is a testament to the dedication of our instructors, staff, and students," said the Director of RETI. "We remain committed to maintaining these high standards and continuously improving our programmes."</p>
    <p>All current and future students can be assured that their RETI certificates carry full national recognition and will be respected by employers in the construction, engineering, and technical sectors.</p>',
    'RETI has achieved full TEVETA accreditation for all programmes, ensuring nationally recognised certificates for all our students.',
    'assets/images/FB_IMG_1753195900189 - Copy.jpg',
    'RETI Management',
    'accreditation,TEVETA,quality',
    'published'
),
(
    'Graduation Ceremony 2025 — Celebrating Our Graduates',
    '<p>Rising East Training Institute held a spectacular graduation ceremony celebrating the achievements of over <strong>120 students</strong> who successfully completed their programmes in 2025. The ceremony was attended by parents, employers, and dignitaries from the education and construction sectors.</p>
    <p>Graduates received their certificates in front of proud families and were recognized for their hard work and dedication throughout their training programmes. Many graduates have already secured employment with leading companies in Zambia.</p>
    <p>The event featured inspirational speeches from industry leaders who praised RETI\'s approach to practical, industry-relevant training. Several employers present at the ceremony expressed interest in hiring RETI graduates directly.</p>
    <p>Congratulations to all our 2025 graduates! You have worked hard to earn your certificates, and we are confident that you will go on to make significant contributions to Zambia\'s construction and technical sectors.</p>',
    'Over 120 students graduated in our 2025 ceremony, with many already securing employment in their chosen fields.',
    'assets/images/FB_IMG_1753195623686.jpg',
    'RETI Events Team',
    'graduation,ceremony,2025,graduates',
    'published'
);

-- Sample testimonials
INSERT INTO testimonials (student_name, course_taken, testimonial, rating, image_url, graduation_year, current_employer, sort_order) VALUES
('James Mwansa', 'Electrical Installation Level III', 'Rising East Institute transformed my career completely. The practical training and industry connections helped me secure a job immediately after graduation. The instructors are knowledgeable and always available to help. I now work as a senior electrician with a leading construction company.', 5, 'assets/images/20250715_130414.jpg', '2024', 'Lusaka Construction Ltd', 1),
('Sarah Chanda', 'Customer Care Service', 'The customer service program at RETI was excellent. The instructors are amazing and the curriculum is industry-relevant. I felt confident and prepared when I entered the job market. I am now working at a top hotel in Lusaka thanks to the skills I gained.', 5, 'assets/images/FB_IMG_1753195623686.jpg', '2024', 'Lusaka International Hotel', 2),
('Michael Banda', 'Excavator Operations', 'Excellent facilities and hands-on training made all the difference. The job placement support was invaluable in finding my first position. I appreciate how RETI prepared me for real work situations. The safety training especially has been crucial in my career.', 5, 'assets/images/20250610_165925.jpg', '2023', 'Zambia National Roads Authority', 3);

-- Sample gallery
INSERT INTO gallery (title, description, image_url, category, sort_order) VALUES
('Training Facility', 'Our modern training facility equipped with the latest tools and equipment', 'assets/images/20250610_165923.jpg', 'facilities', 1),
('Student Workshop Session', 'Students engaged in practical workshop training', 'assets/images/20250617_100130.jpg', 'training', 2),
('Electrical Training Lab', 'Hands-on electrical installation training', 'assets/images/FB_IMG_1753195579557.jpg', 'training', 3),
('Campus Environment', 'The welcoming campus environment at RETI', 'assets/images/20250617_113107.jpg', 'campus', 4),
('Graduation Day', 'Proud graduates at the 2025 graduation ceremony', 'assets/images/FB_IMG_1753195623686.jpg', 'events', 5);
