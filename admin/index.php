<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Already logged in → redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

require_once '../config/database.php';
$db   = new Database();
$conn = $db->getConnection();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? AND status = 'active' LIMIT 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_logged_in']  = true;
                $_SESSION['admin_id']         = $user['id'];
                $_SESSION['admin_username']   = $user['username'];
                $_SESSION['admin_name']       = $user['full_name'];
                $_SESSION['admin_role']       = $user['role'];
                $_SESSION['admin_last_activity'] = time();

                // Update last login
                $conn->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);

                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid username or password. Please try again.';
                // Small delay to slow brute force
                sleep(1);
            }
        } catch(PDOException $e) {
            $error = 'A database error occurred. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - RETI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    *,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
    body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#0f766e 0%,#134e4a 50%,#0f172a 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1rem;}
    .login-wrap{width:100%;max-width:420px;}
    .login-logo{text-align:center;margin-bottom:2rem;}
    .login-logo i{font-size:3rem;color:#2dd4bf;display:block;margin-bottom:0.5rem;}
    .login-logo h1{color:white;font-size:1.75rem;font-weight:800;letter-spacing:-0.02em;}
    .login-logo p{color:rgba(255,255,255,0.65);font-size:0.875rem;margin-top:0.25rem;}
    .login-card{background:white;border-radius:20px;padding:2.5rem;box-shadow:0 25px 60px rgba(0,0,0,0.3);}
    .login-card h2{font-size:1.25rem;font-weight:700;color:#0f172a;margin-bottom:0.25rem;}
    .login-card p{color:#64748b;font-size:0.875rem;margin-bottom:2rem;}
    .form-group{margin-bottom:1.25rem;}
    .form-group label{display:block;font-weight:600;font-size:0.875rem;color:#334155;margin-bottom:0.4rem;}
    .input-wrap{position:relative;}
    .input-wrap i{position:absolute;left:0.875rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:0.95rem;}
    .input-wrap input{width:100%;padding:0.75rem 1rem 0.75rem 2.5rem;border:2px solid #e2e8f0;border-radius:10px;font-size:0.95rem;outline:none;transition:border-color 0.2s,box-shadow 0.2s;font-family:inherit;}
    .input-wrap input:focus{border-color:#0f766e;box-shadow:0 0 0 3px rgba(15,118,110,0.1);}
    .toggle-password{position:absolute;right:0.875rem;top:50%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;cursor:pointer;font-size:0.95rem;padding:0;}
    .btn-login{width:100%;padding:0.9rem;background:linear-gradient(135deg,#0f766e,#14b8a6);color:white;border:none;border-radius:10px;font-size:1rem;font-weight:700;cursor:pointer;transition:all 0.2s;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:0.5rem;}
    .btn-login:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(15,118,110,0.35);}
    .btn-login:disabled{opacity:0.7;cursor:not-allowed;transform:none;}
    .alert-error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;border-radius:10px;padding:0.875rem 1rem;margin-bottom:1.25rem;font-size:0.875rem;display:flex;align-items:center;gap:0.5rem;}
    .alert-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0;border-radius:10px;padding:0.875rem 1rem;margin-bottom:1.25rem;font-size:0.875rem;display:flex;align-items:center;gap:0.5rem;}
    .back-link{text-align:center;margin-top:1.5rem;}
    .back-link a{color:rgba(255,255,255,0.7);font-size:0.875rem;text-decoration:none;}
    .back-link a:hover{color:white;}
    .credentials-hint{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:0.875rem;margin-top:1.25rem;font-size:0.8rem;color:#166534;}
    .credentials-hint strong{display:block;margin-bottom:0.25rem;}
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-logo">
        <i class="fas fa-graduation-cap"></i>
        <h1>RETI Admin</h1>
        <p>Rising East Training Institute</p>
    </div>
    <div class="login-card">
        <h2>Welcome back</h2>
        <p>Sign in to your admin account to continue</p>

        <?php if (!empty($error)): ?>
        <div class="alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['logout'])): ?>
        <div class="alert-success"><i class="fas fa-check-circle"></i> You have been logged out successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['timeout'])): ?>
        <div class="alert-error"><i class="fas fa-clock"></i> Your session has expired. Please log in again.</div>
        <?php endif; ?>

        <form method="POST" action="index.php" id="loginForm">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" required autocomplete="username"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" placeholder="Enter username">
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Enter password">
                    <button type="button" class="toggle-password" onclick="togglePw()"><i class="fas fa-eye" id="eyeIcon"></i></button>
                </div>
            </div>
            <button type="submit" class="btn-login" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="credentials-hint">
            <strong><i class="fas fa-info-circle"></i> Default Credentials:</strong>
            Username: <strong>admin</strong> &nbsp;|&nbsp; Password: <strong>admin123</strong>
        </div>
    </div>
    <div class="back-link"><a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Website</a></div>
</div>
<script>
function togglePw(){
    const pw=document.getElementById('password'),icon=document.getElementById('eyeIcon');
    if(pw.type==='password'){pw.type='text';icon.classList.replace('fa-eye','fa-eye-slash');}
    else{pw.type='password';icon.classList.replace('fa-eye-slash','fa-eye');}
}
document.getElementById('loginForm').addEventListener('submit',function(){
    const btn=document.getElementById('loginBtn');
    btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Signing In...';
    btn.disabled=true;
});
</script>
</body>
</html>
