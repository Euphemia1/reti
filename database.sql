-- Rising East Training Institute Database Schema
-- Created for PHP-based website with admin panel

CREATE DATABASE IF NOT EXISTS risingeast;
USE risingeast;

-- Admin users table
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Courses table
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('level3', 'skills_award', 'short_course') NOT NULL,
    duration VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) DEFAULT 0.00,
    image_url VARCHAR(500),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- News/Events table
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    excerpt VARCHAR(300),
    image_url VARCHAR(500),
    status ENUM('published', 'draft') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Site settings table
CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Gallery images table
CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    description TEXT,
    image_url VARCHAR(500) NOT NULL,
    category VARCHAR(50) DEFAULT 'general',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testimonials table
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    course_taken VARCHAR(200),
    testimonial TEXT NOT NULL,
    rating INT DEFAULT 5,
    image_url VARCHAR(500),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email, full_name) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@reti.edu.zm', 'Administrator');

-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value, description) VALUES 
('site_title', 'Rising East Training Institute', 'Website title'),
('site_description', 'STRIVING TO REDUCE THE SKILLS GAP IN THE CONSTRUCTION INDUSTRY', 'Site description'),
('contact_email', 'academic@reti.edu.zm', 'Main contact email'),
('contact_phone', '0211-296071 | 0954-999900 | 0964-082013', 'Contact phone numbers'),
('contact_address', 'EAST CAMPUS, PLOT A150, OFF MUNALI ROAD, P.O BOX 33381, LUSAKA, ZAMBIA', 'Physical address'),
('application_form_url', '/downloads/RISING%20EAST%20TRAINING%20INSTITUTE%20APPLICATION%20FORM%202025-2026.pdf', 'Application form download link'),
('course_list_url', '/downloads/RETI_Courses_2025_2026.pdf', 'Course list download link');

-- Insert sample courses
INSERT INTO courses (title, description, category, duration, price, image_url) VALUES 
('Customer Care Service', 'Our short training equips learners with essential customer service and front office skills, focusing on communication, professionalism, and client handling to enhance workplace excellence.', 'short_course', '4 weeks', 1500.00, '/images/customer-care.jpg'),
('Air Conditioner Repair & Installation', 'This practical short course provides hands-on skills in installing, maintaining, and repairing air conditioning systems, preparing learners for opportunities in the growing HVAC industry.', 'short_course', '6 weeks', 2500.00, '/images/ac-repair.jpg'),
('Excavator Operations', 'Our short training equips learners with practical skills in excavator operation, safety, and maintenance, preparing them for careers in construction and earth-moving industries.', 'short_course', '8 weeks', 3500.00, '/images/excavator.jpg'),
('Electrical Installation Level III', 'Comprehensive electrical installation program covering residential, commercial, and industrial electrical systems with TEVETA accreditation.', 'level3', '12 months', 15000.00, '/images/electrical.jpg'),
('Plumbing and Pipe Fitting', 'Professional plumbing program covering water supply systems, drainage, and pipe fitting techniques for modern construction.', 'skills_award', '6 months', 8000.00, '/images/plumbing.jpg');

-- Insert sample news
INSERT INTO news (title, content, excerpt, status) VALUES 
('New Intake Now Open for 2025/2026', 'We are pleased to announce that applications for the 2025/2026 academic year are now open. Join us to acquire practical skills that will transform your career.', 'Applications for the new academic year are now open', 'published'),
('TEVETA Accreditation Achieved', 'Rising East Training Institute has successfully achieved full TEVETA accreditation for all our programs, ensuring quality and recognition.', 'We are now fully TEVETA accredited', 'published'),
('Graduation Ceremony Success', 'Our recent graduation ceremony saw over 100 students successfully complete their programs and enter the job market with practical skills.', 'Celebrating our graduates success', 'published');

-- Insert sample testimonials
INSERT INTO testimonials (student_name, course_taken, testimonial, rating) VALUES 
('James Banda', 'Electrical Installation Level III', 'The practical training I received at RETI was exceptional. I got a job immediately after completing my program.', 5),
('Mary Phiri', 'Customer Care Service', 'The customer service program helped me secure a position at a leading hotel chain. Thank you RETI!', 5),
('John Mwila', 'Excavator Operations', 'The hands-on experience with modern equipment made me job-ready. I now work with a major construction company.', 5);
