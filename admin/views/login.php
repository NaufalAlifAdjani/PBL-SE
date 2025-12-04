<?php
session_start();

// Ambil pesan error jika ada
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/login.css">
    
    <style>

    </style>
</head>

<body class="body-login">

<div class="login-container">
    
    <div class="left-side">
        <a href="../../index.php" class="nav-back">
            &larr; Back to Dashboard
        </a>

        <div class="brand-logo">Software<span>Engineering</span></div>

        <div class="login-content">
            <h1>Halo Admin!</h1>
            <p class="subtitle">Masukkan Username dan Password Admin Anda</p>

            <form action="../controllers/proses_login.php" method="POST">
                
                <?php if ($error): ?>
                    <div class="alert alert-danger py-2 text-center" style="font-size: 13px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <input 
                        type="text" 
                        name="username" 
                        class="custom-input" 
                        placeholder="Username atau Email"
                        value="<?php echo isset($_COOKIE['ingat_username']) ? htmlspecialchars($_COOKIE['ingat_username']) : ''; ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <input 
                        type="password" 
                        name="password" 
                        class="custom-input" 
                        placeholder="Password"
                        required
                    >
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <label style="font-size: 13px; color: #666; cursor: pointer;">
                        <input type="checkbox" name="ingat_saya" <?php echo isset($_COOKIE['ingat_username']) ? 'checked' : ''; ?>> 
                        Remember Me
                    </label>

                    
                </div>

                <button type="submit" class="btn-black">Login</button>

                <div class="footer-contact">
                    <p>Jangan ragu untuk menghubungi kami<br>
                    <a href="mailto:support@bonsante.com">support@PBL.com</a></p>
                </div>
            </form>
        </div>
        
        <div style="position: absolute; bottom: 20px; left: 0; right: 0; text-align: center; font-size: 11px; color: #ccc;">
           Copyright © 2025 Lab SE. All Rights Reserved.
        </div>
    </div>

    <div class="right-side">
        <div class="glass-card">
            <div style="font-size: 24px; margin-bottom: 15px;">&#9696;</div> 
            
            <h3>Administrator Panel – Software Engineering</h3>
            <p>
                Selamat datang di halaman Administrator Sistem Software Engineering.
Area ini dirancang khusus untuk pengelola yang bertanggung jawab dalam memantau, mengatur, dan memastikan seluruh proses operasional berjalan dengan baik.
Silakan masuk menggunakan akun resmi Anda untuk mengelola data pengguna, memverifikasi pendaftaran, memperbarui konten, dan mengawasi performa sistem. Akses ini bersifat terbatas dan diawasi demi menjaga keamanan serta integritas data.
            </p>
        </div>
    </div>

</div>

</body>
</html>