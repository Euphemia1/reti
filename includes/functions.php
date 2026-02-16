<?php
/**
 * Common Functions
 * Rising East Training Institute
 */

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database configuration
require_once __DIR__ . '/../config/database.php';

// Database connection
$db = new Database();
$conn = $db->getConnection();

// Site configuration
function getSiteSetting($key) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['setting_value'] : '';
    } catch(PDOException $e) {
        return '';
    }
}

// Clean input data
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Redirect if not admin
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Format currency
function formatCurrency($amount) {
    return 'ZMW ' . number_format($amount, 2);
}

// Truncate text
function truncateText($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

// Generate SEO friendly URL
function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

// Upload image
function uploadImage($file, $target_dir = "uploads/") {
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed.'];
    }

    if ($file['size'] > 5000000) { // 5MB limit
        return ['success' => false, 'message' => 'File is too large. Maximum size is 5MB.'];
    }

    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['success' => true, 'filename' => $new_filename, 'path' => $target_file];
    } else {
        return ['success' => false, 'message' => 'Error uploading file.'];
    }
}

// Send email (basic implementation)
function sendEmail($to, $subject, $message, $from = '') {
    $headers = "From: " . ($from ? $from : getSiteSetting('contact_email')) . "\r\n";
    $headers .= "Reply-To: " . ($from ? $from : getSiteSetting('contact_email')) . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Get page title
function getPageTitle() {
    $title = getSiteSetting('site_title');
    $page = basename($_SERVER['PHP_SELF'], '.php');
    
    switch($page) {
        case 'index':
            return $title . ' - Home';
        case 'about':
            return $title . ' - About Us';
        case 'courses':
            return $title . ' - Courses';
        case 'news':
            return $title . ' - News';
        case 'contact':
            return $title . ' - Contact';
        case 'admin':
            return $title . ' - Admin Panel';
        default:
            return $title;
    }
}

// Pagination helper
function getPagination($total_records, $per_page = 10, $current_page = 1) {
    $total_pages = ceil($total_records / $per_page);
    $offset = ($current_page - 1) * $per_page;
    
    return [
        'total_records' => $total_records,
        'per_page' => $per_page,
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'offset' => $offset,
        'has_next' => $current_page < $total_pages,
        'has_prev' => $current_page > 1
    ];
}

// Flash messages
function setFlashMessage($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

function getFlashMessage($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return '';
}

function hasFlashMessage($type) {
    return isset($_SESSION['flash'][$type]);
}
?>
