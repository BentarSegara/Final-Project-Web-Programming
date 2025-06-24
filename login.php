<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Kos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/login-form.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <div class="login-header">
                <h2>Masuk</h2>
                <p>Silakan masuk ke akun Anda</p>
            </div>
            
            <form action="./controller/login_control.php" method="post">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        <input type="email" name="email" class="form-input" value="" id="email" placeholder="Masukkan email Anda">
                    </div>
                </div>    
                
                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-group">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18,8h-1V6c0-2.76-2.24-5-5-5S7,3.24,7,6v2H6c-1.1,0-2,0.9-2,2v10c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V10C20,8.9,19.1,8,18,8z M12,17c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S13.1,17,12,17z M15.1,8H8.9V6c0-1.71,1.39-3.1,3.1-3.1s3.1,1.39,3.1,3.1V8z"/>
                        </svg>
                        <input type="password" name="password" class="form-input" id="password" placeholder="Masukkan kata sandi">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="userType" class="form-label">Tipe User</label>
                    <select class="form-select" name="tipe_user" id="userType", required>
                        <option value="">Pilih Tipe User</option>
                        <option value="tenant">Penghuni Kos</option>
                        <option value="owner">Administrator</option>
                    </select>
                </div>
                
                <input type="submit" name="submit" value="Masuk" class="btn btn-primary w-100">
            </form>
            
            <div class="form-links">                
                <p><a href="forgot_password.php">Lupa kata sandi?</a></p>
            </div>

            <div class="form-links">                
                <p><a href="signup.php">Daftar sebagai pemilik kost</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>