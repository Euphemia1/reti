<?php
require_once '../includes/functions.php';
requireAdminLogin();

// Get dashboard statistics
try {
    // Total courses
    $stmt = $conn->query("SELECT COUNT(*) as total FROM courses");
    $total_courses = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Active courses
    $stmt = $conn->query("SELECT COUNT(*) as total FROM courses WHERE status = 'active'");
    $active_courses = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Total news
    $stmt = $conn->query("SELECT COUNT(*) as total FROM news");
    $total_news = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Published news
    $stmt = $conn->query("SELECT COUNT(*) as total FROM news WHERE status = 'published'");
    $published_news = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Total messages
    $stmt = $conn->query("SELECT COUNT(*) as total FROM contact_messages");
    $total_messages = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Unread messages
    $stmt = $conn->query("SELECT COUNT(*) as total FROM contact_messages WHERE status = 'new'");
    $unread_messages = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Total testimonials
    $stmt = $conn->query("SELECT COUNT(*) as total FROM testimonials");
    $total_testimonials = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Active testimonials
    $stmt = $conn->query("SELECT COUNT(*) as total FROM testimonials WHERE status = 'active'");
    $active_testimonials = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
} catch(PDOException $e) {
    $total_courses = $active_courses = $total_news = $published_news = 0;
    $total_messages = $unread_messages = $total_testimonials = $active_testimonials = 0;
}

// Get recent messages
try {
    $stmt = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
    $recent_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $recent_messages = [];
}

// Get recent news
try {
    $stmt = $conn->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 5");
    $recent_news = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $recent_news = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        .admin-dashboard {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            width: 250px;
            background: var(--text-dark);
            color: white;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .admin-content {
            flex: 1;
            margin-left: 250px;
            padding: 2rem;
            background: var(--bg-light);
            min-height: 100vh;
        }
        
        .admin-header {
            background: white;
            padding: 1.5rem 2rem;
            margin: -2rem -2rem 2rem -2rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-header h1 {
            margin: 0;
            color: var(--text-dark);
        }
        
        .admin-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .admin-user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .stat-icon.courses {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }
        
        .stat-icon.news {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .stat-icon.messages {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        
        .stat-icon.testimonials {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }
        
        .stat-content h3 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        .stat-content p {
            margin: 0;
            color: var(--text-light);
            font-size: 0.875rem;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }
        
        .dashboard-card-header {
            background: var(--primary-color);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dashboard-card-header h3 {
            margin: 0;
            font-size: 1.125rem;
        }
        
        .dashboard-card-body {
            padding: 1.5rem;
        }
        
        .recent-item {
            display: flex;
            justify-content: space-between;
            align-items: start;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .recent-item:last-child {
            border-bottom: none;
        }
        
        .recent-item-content {
            flex: 1;
        }
        
        .recent-item-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .recent-item-meta {
            font-size: 0.875rem;
            color: var(--text-light);
        }
        
        .recent-item-status {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-new {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-read {
            background: #e0e7ff;
            color: #3730a3;
        }
        
        .status-published {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-draft {
            background: #fef3c7;
            color: #92400e;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .admin-sidebar.active {
                transform: translateX(0);
            }
            
            .admin-content {
                margin-left: 0;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-dashboard">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div style="padding: 0 1.5rem; margin-bottom: 2rem;">
                <h2 style="color: white; margin: 0;">
                    <i class="fas fa-graduation-cap"></i> RETI Admin
                </h2>
            </div>
            
            <ul style="list-style: none; padding: 0;">
                <li>
                    <a href="index.php" style="display: block; padding: 0.75rem 1.5rem; color: white; text-decoration: none; background: var(--primary-color);">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="courses.php" style="display: block; padding: 0.75rem 1.5rem; color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: background 0.3s;">
                        <i class="fas fa-book"></i> Courses
                    </a>
                </li>
                <li>
                    <a href="news.php" style="display: block; padding: 0.75rem 1.5rem; color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: background 0.3s;">
                        <i class="fas fa-newspaper"></i> News
                    </a>
                </li>
                <li>
                    <a href="messages.php" style="display: block; padding: 0.75rem 1.5rem; color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: background 0.3s;">
                        <i class="fas fa-envelope"></i> Messages
                        <?php if ($unread_messages > 0): ?>
                            <span style="background: #ef4444; color: white; padding: 0.125rem 0.5rem; border-radius: 1rem; font-size: 0.75rem; margin-left: 0.5rem;"><?php echo $unread_messages; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li>
                    <a href="testimonials.php" style="display: block; padding: 0.75rem 1.5rem; color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: background 0.3s;">
                        <i class="fas fa-quote-left"></i> Testimonials
                    </a>
                </li>
                <li>
                    <a href="gallery.php" style="display: block; padding: 0.75rem 1.5rem; color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: background 0.3s;">
                        <i class="fas fa-images"></i> Gallery
                    </a>
                </li>
                <li>
                    <a href="settings.php" style="display: block; padding: 0.75rem 1.5rem; color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: background 0.3s;">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
            </ul>
            
            <div style="position: absolute; bottom: 2rem; left: 0; right: 0; padding: 0 1.5rem;">
                <a href="logout.php" style="display: block; padding: 0.75rem 1.5rem; color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: background 0.3s;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-content">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-user">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                    <div class="admin-user-avatar">
                        <?php echo strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?>
                    </div>
                </div>
            </div>
            
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
            
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon courses">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $active_courses; ?></h3>
                        <p>Active Courses (<?php echo $total_courses; ?> total)</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon news">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $published_news; ?></h3>
                        <p>Published News (<?php echo $total_news; ?> total)</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon messages">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $unread_messages; ?></h3>
                        <p>Unread Messages (<?php echo $total_messages; ?> total)</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon testimonials">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $active_testimonials; ?></h3>
                        <p>Active Testimonials (<?php echo $total_testimonials; ?> total)</p>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h3>Recent Messages</h3>
                        <a href="messages.php" style="color: white; text-decoration: none;">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="dashboard-card-body">
                        <?php if (count($recent_messages) > 0): ?>
                            <?php foreach ($recent_messages as $message): ?>
                                <div class="recent-item">
                                    <div class="recent-item-content">
                                        <div class="recent-item-title"><?php echo htmlspecialchars($message['name']); ?></div>
                                        <div class="recent-item-meta">
                                            <?php echo htmlspecialchars($message['subject']); ?> • 
                                            <?php echo date('M j, Y', strtotime($message['created_at'])); ?>
                                        </div>
                                    </div>
                                    <span class="recent-item-status status-<?php echo $message['status']; ?>">
                                        <?php echo ucfirst($message['status']); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align: center; color: var(--text-light); padding: 2rem 0;">No messages yet</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h3>Recent News</h3>
                        <a href="news.php" style="color: white; text-decoration: none;">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="dashboard-card-body">
                        <?php if (count($recent_news) > 0): ?>
                            <?php foreach ($recent_news as $news): ?>
                                <div class="recent-item">
                                    <div class="recent-item-content">
                                        <div class="recent-item-title"><?php echo htmlspecialchars($news['title']); ?></div>
                                        <div class="recent-item-meta">
                                            <?php echo date('M j, Y', strtotime($news['created_at'])); ?>
                                        </div>
                                    </div>
                                    <span class="recent-item-status status-<?php echo $news['status']; ?>">
                                        <?php echo ucfirst($news['status']); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align: center; color: var(--text-light); padding: 2rem 0;">No news articles yet</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/script.js"></script>
</body>
</html>
