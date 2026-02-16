# Rising East Training Institute - PHP Website

A professional, fully-functional PHP-based website for Rising East Training Institute, featuring a modern design with animations, responsive layout, and a comprehensive admin panel for content management.

## Features

### Frontend Features
- **Modern, Professional Design**: Clean, minimal color scheme with smooth animations and interactions
- **Fully Responsive**: Works seamlessly on desktop, tablet, and mobile devices
- **Dynamic Content**: All content is managed through the database
- **Interactive Elements**: 
  - Animated hero section with typing effect
  - Smooth scroll animations
  - Interactive course cards
  - FAQ accordion
  - Newsletter subscription
  - Contact form with validation
  - Image gallery with lightbox

### Pages
1. **Homepage** (`index.php`)
   - Hero section with call-to-action
   - Featured courses showcase
   - Why choose us section
   - Latest news updates
   - Student testimonials
   - Download section for application forms

2. **Courses** (`courses.php`)
   - Categorized course listings (Level III, Skills Award, Short Courses)
   - Detailed course information
   - Admission requirements
   - Search and filter functionality

3. **About Us** (`about.php`)
   - Mission and vision
   - Core values
   - Facilities showcase
   - Statistics with animated counters
   - Management team

4. **News** (`news.php`)
   - Paginated news articles
   - Read more/less functionality
   - Newsletter subscription
   - Recent updates section

5. **Contact** (`contact.php`)
   - Contact form with validation
   - Contact information
   - Interactive map
   - FAQ section
   - Download links

### Admin Panel Features
- **Secure Login System**: Password-protected admin area
- **Dashboard**: Overview statistics and recent activity
- **Course Management**: Add, edit, delete courses with image upload
- **News Management**: Create and manage news articles
- **Message Management**: View and respond to contact form submissions
- **Testimonial Management**: Manage student testimonials
- **Gallery Management**: Upload and organize images
- **Settings**: Configure site-wide settings

## Technical Stack

### Backend
- **PHP 7.4+**: Core programming language
- **MySQL**: Database for content storage
- **PDO**: Database connectivity with prepared statements
- **Sessions**: User authentication and flash messages

### Frontend
- **HTML5**: Semantic markup
- **CSS3**: Modern styling with animations
- **JavaScript**: Interactive features and form validation
- **Font Awesome**: Icon library
- **Google Fonts**: Typography (Inter font)

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- PHP extensions: PDO, PDO_MySQL, GD (for image processing)

### Setup Instructions

1. **Database Setup**
   ```bash
   # Create database and import schema
   mysql -u root -p < database.sql
   ```

2. **Configuration**
   - Update database credentials in `config/database.php`
   - Set appropriate file permissions for uploads directory:
     ```bash
     chmod 755 uploads/
     chmod 644 uploads/*/*
     ```

3. **Web Server Configuration**
   - Point your web server document root to the project directory
   - Ensure `.htaccess` is enabled for Apache (if using)

4. **Admin Access**
   - Default admin login:
     - Username: `admin`
     - Password: `admin123`
   - **Important**: Change the default password after first login

## Directory Structure

```
risingeast/
├── admin/                  # Admin panel files
│   ├── index.php          # Dashboard
│   ├── login.php          # Admin login
│   ├── logout.php         # Admin logout
│   ├── courses.php        # Course management
│   ├── course-form.php    # Add/edit course
│   ├── news.php           # News management
│   ├── messages.php       # Contact messages
│   ├── testimonials.php   # Testimonial management
│   ├── gallery.php        # Gallery management
│   └── settings.php       # Site settings
├── assets/                # Static assets
│   ├── css/
│   │   └── style.css      # Main stylesheet
│   ├── js/
│   │   └── script.js      # Main JavaScript file
│   └── images/            # Image assets
├── config/                # Configuration files
│   └── database.php       # Database configuration
├── includes/              # PHP includes
│   └── functions.php      # Common functions
├── uploads/               # User uploaded files
│   ├── courses/           # Course images
│   ├── news/              # News images
│   └── gallery/           # Gallery images
├── index.php              # Homepage
├── about.php              # About page
├── courses.php            # Courses page
├── news.php               # News page
├── contact.php            # Contact page
├── database.sql           # Database schema
└── README.md              # This file
```

## Database Schema

The application uses the following main tables:

- `admin_users` - Administrator accounts
- `courses` - Course information and details
- `news` - News articles and updates
- `contact_messages` - Contact form submissions
- `testimonials` - Student testimonials
- `gallery` - Image gallery items
- `site_settings` - Configuration settings

## Customization

### Adding New Pages
1. Create the PHP file in the root directory
2. Include `includes/functions.php` at the top
3. Use the existing header/footer structure
4. Add the page to the navigation menu

### Modifying Styles
- Main styles are in `assets/css/style.css`
- CSS variables are defined at the top for easy customization
- Responsive breakpoints are included

### Adding New Admin Features
1. Create the admin page in the `admin/` directory
2. Add the menu item to the sidebar in all admin pages
3. Include `requireAdminLogin()` for security
4. Follow the existing patterns for CRUD operations

## Security Features

- **SQL Injection Protection**: All database queries use prepared statements
- **XSS Protection**: Input sanitization and output escaping
- **CSRF Protection**: Session-based authentication
- **File Upload Security**: File type and size validation
- **Input Validation**: Server-side validation for all forms

## Performance Optimization

- **Efficient Database Queries**: Optimized SQL with proper indexing
- **Image Optimization**: Automatic image resizing and compression
- **Lazy Loading**: Images load as needed
- **Minified Assets**: CSS and JavaScript are optimized
- **Caching**: Browser caching headers implemented

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Deployment

### Production Deployment Checklist

1. **Environment Configuration**
   - Set production database credentials
   - Configure error reporting (display_errors = Off)
   - Enable HTTPS/SSL certificate

2. **Security**
   - Change default admin password
   - Set proper file permissions
   - Configure firewall rules
   - Enable HTTPS only

3. **Performance**
   - Enable PHP OPcache
   - Configure database caching
   - Set up CDN for static assets
   - Enable GZIP compression

4. **Backup Strategy**
   - Regular database backups
   - File system backups
   - Disaster recovery plan

## Support

For technical support or questions:
- Email: academic@reti.edu.zm
- Phone: 0211-296071 | 0954-999900 | 0964-082013

## License

This project is proprietary to Rising East Training Institute. All rights reserved.

---

**Note**: This website is designed to be a comprehensive solution for Rising East Training Institute's online presence. The admin panel provides full control over content, making it easy to keep the website updated with the latest information about courses, news, and events.
