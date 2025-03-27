<?php
include("php/conn.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header("Location:./index.php");
    exit;
}

if (isset($_POST['submitlogin'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_role'] = $user['role'];
                    
                    if ($user['role'] === 'admin') {
                        header("location:./dashboard.php");
                    } else {
                        header("location:./index.php?login=success");
                    }
                    exit;
                } else {
                    $login_error = 'Incorrect Password';
                }
            } else {
                $login_error = 'User does not exist';
            }
        } catch (PDOException $e) {
            $login_error = "Connection failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-color: #5a5c69;
        }
        
        body {
            background-color: var(--secondary-color);
            font-family: 'Nunito', sans-serif;
            color: var(--text-color);
            height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .auth-container {
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.35rem;
            overflow: hidden;
            background: white;
        }
        
        .auth-header {
            background: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .auth-header h2 {
            margin: 0;
            font-weight: 600;
        }
        
        .auth-tabs {
            display: flex;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .auth-tab {
            flex: 1;
            text-align: center;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            color: #6c757d;
        }
        
        .auth-tab.active {
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
        }
        
        .auth-content {
            padding: 2rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.35rem;
            margin-bottom: 1rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem;
            font-weight: 600;
            width: 100%;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .req {
            color: #e74a3b;
        }
        
        .help-block {
            font-size: 0.875rem;
            color: #e74a3b;
        }
        
        .auth-tab-content {
            display: none;
        }
        
        .auth-tab-content.active {
            display: block;
        }
        
        .alert {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h2>Welcome!</h2>
        </div>
        
        <div class="auth-tabs">
            <div class="auth-tab active" data-tab="login">Log In</div>
            <div class="auth-tab" data-tab="signup">Sign Up</div>
        </div>
        
        <div class="auth-content">
            <!-- Login Form -->
            <div id="login-form" class="auth-tab-content active">
                <?php if (!empty($login_error)): ?>
                    <div class="alert alert-danger"><?php echo $login_error; ?></div>
                <?php endif; ?>
                <form id="login" method="post">
                    <div class="form-group">
                        <label>Email<span class="req">*</span></label>
                        <input type="email" name="email" class="form-control" required autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label>Password<span class="req">*</span></label>
                        <input type="password" name="password" class="form-control" required autocomplete="off">
                    </div>
                    
                    <button type="submit" name="submitlogin" class="btn btn-primary mt-3">
                        Log In
                    </button>
                </form>
            </div>
            
            <!-- Signup Form -->
            <div id="signup-form" class="auth-tab-content">
                <form id="signup" method="post" action="./register.php">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name<span class="req">*</span></label>
                                <input type="text" name="fname" class="form-control" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name<span class="req">*</span></label>
                                <input type="text" name="lname" class="form-control" required autocomplete="off">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Email<span class="req">*</span></label>
                        <input type="email" name="email" class="form-control" required autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label>Phone<span class="req">*</span></label>
                        <input type="tel" name="phone" class="form-control" required autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label>Password<span class="req">*</span></label>
                        <input type="password" name="password" class="form-control" required autocomplete="off">
                    </div>
                    
                    <button type="submit" name="Subregister" class="btn btn-primary mt-3">
                        Sign Up
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.auth-tab');
            const tabContents = document.querySelectorAll('.auth-tab-content');
            
            // Show only the active tab content initially
            tabContents.forEach(content => {
                if (!content.classList.contains('active')) {
                    content.style.display = 'none';
                }
            });
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.style.display = 'none';
                        content.classList.remove('active');
                    });
                    
                    // Show corresponding content
                    const tabName = this.getAttribute('data-tab');
                    const activeContent = document.getElementById(`${tabName}-form`);
                    activeContent.style.display = 'block';
                    activeContent.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>