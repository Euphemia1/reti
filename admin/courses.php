<?php
require_once '../includes/functions.php';
requireAdminLogin();

// Handle course operations
$action = $_GET['action'] ?? '';
$course_id = $_GET['id'] ?? '';

if ($action === 'delete' && $course_id) {
    try {
        $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$course_id]);
        setFlashMessage('success', 'Course deleted successfully!');
        header('Location: courses.php');
        exit();
    } catch(PDOException $e) {
        setFlashMessage('error', 'Error deleting course: ' . $e->getMessage());
        header('Location: courses.php');
        exit();
    }
}

// Get courses
try {
    $stmt = $conn->query("SELECT * FROM courses ORDER BY created_at DESC");
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $courses = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Management - Admin Panel</title>
    
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
        
        .btn-add {
            background: linear-gradient(135deg, var(--secondary-color), #059669);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .courses-table {
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        .table th {
            background: var(--bg-light);
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .table tbody tr:hover {
            background: var(--bg-light);
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-active {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .category-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            background: #e0e7ff;
            color: #3730a3;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-action {
            padding: 0.5rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }
        
        .btn-edit {
            background: #3b82f6;
            color: white;
        }
        
        .btn-edit:hover {
            background: #2563eb;
        }
        
        .btn-delete {
            background: #ef4444;
            color: white;
        }
        
        .btn-delete:hover {
            background: #dc2626;
        }
        
        .course-image-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 0.25rem;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-content {
                margin-left: 0;
            }
            
            .table {
                font-size: 0.875rem;
            }
            
            .table th,
            .table td {
                padding: 0.5rem;
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
                    <a href="index.php" style="display: block; padding: 0.75rem 1.5rem; color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: background 0.3s;">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="courses.php" style="display: block; padding: 0.75rem 1.5rem; color: white; text-decoration: none; background: var(--primary-color);">
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
                <h1>Courses Management</h1>
                <a href="course-form.php" class="btn-add">
                    <i class="fas fa-plus"></i> Add New Course
                </a>
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
            
            <div class="courses-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Course Title</th>
                            <th>Category</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($courses) > 0): ?>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td>
                                        <?php if ($course['image_url']): ?>
                                            <img src="<?php echo htmlspecialchars($course['image_url']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="course-image-thumb">
                                        <?php else: ?>
                                            <div style="width: 50px; height: 50px; background: var(--bg-light); border-radius: 0.25rem; display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($course['title']); ?></strong>
                                        <br>
                                        <small style="color: var(--text-light);"><?php echo truncateText(htmlspecialchars($course['description']), 50); ?></small>
                                    </td>
                                    <td>
                                        <span class="category-badge">
                                            <?php echo ucfirst(str_replace('_', ' ', $course['category'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($course['duration']); ?></td>
                                    <td>
                                        <?php if ($course['price'] > 0): ?>
                                            <?php echo formatCurrency($course['price']); ?>
                                        <?php else: ?>
                                            <span style="color: var(--text-light);">Contact for Price</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo $course['status']; ?>">
                                            <?php echo ucfirst($course['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="course-form.php?id=<?php echo $course['id']; ?>" class="btn-action btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="courses.php?action=delete&id=<?php echo $course['id']; ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this course?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-light);">
                                    No courses found. <a href="course-form.php">Add your first course</a>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/script.js"></script>
</body>
</html>
