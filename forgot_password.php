<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Kata Sandi</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/forgot-password.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="text-center mb-4">
                        <h2 class="card-title">Ubah Kata Sandi</h2>
                        <p class="card-subtitle">Masukkan kata sandi baru Anda</p>
                    </div>

                    <form action="./controller/change_pass_control.php" id="changePasswordForm" method="POST">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height:20px;">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                                <input type="email" name="email" class="form-input" value="" id="email" placeholder="Masukkan email Anda">
                            </div>
                        </div>                         
        
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Kata Sandi Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="newPassword" name="new-password"
                                       placeholder="Masukkan kata sandi baru" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('newPassword')">
                                    <i class="bi bi-eye" id="newPasswordIcon"></i>
                                </button>
                            </div>
                
                            <div id="passwordStrength" class="password-strength text-secondary ms-3">
                                Kata Sandi Minimal Harus Memiliki 8 Karakter
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="confirmPassword" class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" 
                                       placeholder="Konfirmasi kata sandi baru" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                                    <i class="bi bi-eye" id="confirmPasswordIcon"></i>
                                </button>
                            </div>
                            <div id="passwordMatch" class="form-text"></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-lock me-2"></i>
                            Ubah Kata Sandi
                        </button>

                        <?php if (isset($_SESSION['change_pass_message'])): ?>
                            <div class="notif text-center">
                                <?php if ($_SESSION['pass_changed']): ?>
                                    <span class="text-success">
                                        <?= htmlspecialchars($_SESSION['change_pass_message']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-danger">
                                        <?= htmlspecialchars($_SESSION['change_pass_message']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php 
                            unset($_SESSION['change_pass_message']);
                            unset($_SESSION['pass_changed']);
                            ?>
                        <?php endif; ?>
                        <div class="text-center">
                            <a href="login.php" class="link-primary">Kembali ke Login Page</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        function checkPasswordMatch() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const matchDiv = document.getElementById('passwordMatch');

            if (confirmPassword === '') {
                matchDiv.innerHTML = '';
                return;
            }

            if (newPassword === confirmPassword) {
                matchDiv.innerHTML = '<span class="text-success">✓ Kata sandi cocok</span>';
            } else {
                matchDiv.innerHTML = '<span class="text-danger">✗ Kata sandi tidak cocok</span>';
            }
        }

        document.getElementById('confirmPassword').addEventListener('input', checkPasswordMatch);

        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Validasi
            if (newPassword !== confirmPassword) {
                alert('Kata sandi baru dan konfirmasi tidak cocok!');
                return;
            }

            if (newPassword.length < 8) {
                alert('Kata sandi baru harus minimal 8 karakter!');
                return;
            }

            
            // Simulasi proses ubah kata sandi
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';
            submitBtn.disabled = true;
            this.submit();
           
        });
    </script>
</body>
</html>