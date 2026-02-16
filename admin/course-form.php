<?php
require_once '../includes/functions.php';
requireAdminLogin();

$course_id = $_GET['id'] ?? '';
$course = null;

// Edit mode - get course data
if ($course_id) {
    try {
        $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$course_id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$course) {
            setFlashMessage('error', 'Course not found!');
            header('Location: courses.php');
            exit();
        }
    } catch(PDOException $e) {
        setFlashMessage('error', 'Error loading course: ' . $e->getMessage());
        header('Location: courses.php');
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = cleanInput($_POST['title'] ?? '');
    $description = cleanInput($_POST['description'] ?? '');
    $category = cleanInput($_POST['category'] ?? '');
    $duration = cleanInput($_POST['duration'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $status = cleanInput($_POST['status'] ?? 'active');
    
    $errors = [];
    
    // Validation
    if (empty($title)) {
        $errors[] = 'Course title is required';
    }
    
    if (empty($description)) {
        $errors[] = 'Course description is required';
    }
    
    if (empty($category)) {
        $errors[] = 'Course category is required';
    }
    
    if (empty($duration)) {
        $errors[] = 'Course duration is required';
    }
    
    // Handle image upload
    $image_url = $course['image_url'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_result = uploadImage($_FILES['image'], '../uploads/courses/');
        if ($upload_result['success']) {
            $image_url = 'uploads/courses/' . $upload_result['filename'];
        } else {
            $errors[] = $upload_result['message'];
        }
    }
    
    if (empty($errors)) {
        try {
            if ($course_id) {
                // Update existing course
                $stmt = $conn->prepare("UPDATE courses SET title = ?, description = ?, category = ?, duration = ?, price = ?, image_url = ?, status = ? WHERE id = ?");
                $stmt->execute([$title, $description, $category, $duration, $price, $image_url, $status, $course_id]);
                setFlashMessage('success', 'Course updated successfully!');
            } else {
                // Insert new course
                $stmt = $conn->prepare("INSERT INTO courses (title, description, category, duration, price, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $description, $category, $duration, $price, $image_url, $status]);
                setFlashMessage('success', 'Course added successfully!');
            }
            
            header('Location: courses.php');
            exit();
            
        } catch(PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
    
    if (!empty($errors)) {
        setFlashMessage('error', implode('<br>', $errors));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $course_id ? 'Edit Course' : 'Add New Course'; ?> - Admin Panel</title>
    
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
        
        .form-container {
            background: white;
            border-radius: 0.5rem;
            padding: 2rem;
            box-shadow: var(--shadow-md);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .image-upload {
            border: 2px dashed var(--border-color);
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            transition: var(--transition);
        }
        
        .image-upload:hover {
            border-color: var(--primary-color);
            background: var(--bg-light);
        }
        
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin: 1rem auto;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-content {
                margin-left: 0;
            }
            
            .form-row {
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
                <h1><?php echo $course_id ? 'Edit Course' : 'Add New Course'; ?></h1>
                <a href="courses.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Courses
                </a>
            </div>
            
            <?php if (hasFlashMessage('error')): ?>
                <div class="alert alert-error">
                    <?php echo getFlashMessage('error'); ?>
                </div>
            <?php endif; ?>
            
            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">Course Title *</label>
                            <input type="text" id="title" name="title" class="form-control" required
                                   value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ($course['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select id="category" name="category" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="level3" <?php echo (isset($_POST['category']) && $_POST['category'] === 'level3') || ($course['category'] === 'level3') ? 'selected' : ''; ?>>Level III Programmes</option>
                                <option value="skills_award" <?php echo (isset($_POST['category']) && $_POST['category'] === 'skills_award') || ($course['category'] === 'skills_award') ? 'selected' : ''; ?>>Skills Award Programmes</option>
                                <option value="short_course" <?php echo (isset($_POST['category']) && $_POST['category'] === 'short_course') || ($course['category'] === 'short_course') ? 'selected' : ''; ?>>Short Courses</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Course Description *</label>
                        <textarea id="description" name="description" class="form-control" rows="5" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ($course['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="duration">Duration *</label>
                            <input type="text" id="duration" name="duration" class="form-control" required
                                   placeholder="e.g., 6 weeks, 3 months"
                                   value="<?php echo isset($_POST['duration']) ? htmlspecialchars($_POST['duration']) : ($course['duration'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price (ZMW)</label>
                            <input type="number" id="price" name="price" class="form-control" step="0.01" min="0"
                                   placeholder="0.00"
                                   value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ($course['price'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="active" <?php echo (isset($_POST['status']) && $_POST['status'] === 'active') || ($course['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo (isset($_POST['status']) && $_POST['status'] === 'inactive') || ($course['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Course Image</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                            <small style="color: var(--text-light);">Maximum file size: 5MB. Allowed formats: JPG, PNG, GIF, WebP</small>
                        </div>
                    </div>
                    
                    <?php if ($course && $course['image_url']): ?>
                        <div class="form-group">
                            <label>Current Image</label>
                            <div class="image-preview">
                                <img src="../<?php echo htmlspecialchars($course['image_url']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                        <a href="courses.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $course_id ? 'Update Course' : 'Add Course'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/script.js"></script>
    
    <script>
        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.querySelector('.image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.className = 'image-preview';
                        document.getElementById('image').parentNode.appendChild(preview);
                    }
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
