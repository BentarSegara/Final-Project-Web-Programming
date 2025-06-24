<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Pengguna Baru - Kos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/signup-form.css">
</head>
<body>
    <div class="signup-container">
        <div class="signup-card">
            <div class="signup-header">
                <i class="fas fa-user-plus"></i>
                <h2>Pendaftaran Pengguna Baru</h2>
                <p>Daftar untuk mengakses layanan kos terbaik</p>
            </div>
            
            <form action="../controller/signup_owner_control.php" method="POST" id="signupForm" novalidate>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" class="form-control" id="nama" name="name" required>
                            <div class="invalid-feedback">
                                Nama lengkap wajib diisi
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="required">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" required>
                            <div class="invalid-feedback">
                                Username wajib diisi
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="required">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">
                                Email yang valid wajib diisi
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">No. Telepon <span class="required">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone_number" placeholder="08xxxxxxxxxx" required>
                            <div class="invalid-feedback">
                                Nomor telepon wajib diisi
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="required">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                    <div class="invalid-feedback">
                        Password minimal 6 karakter
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="namaKost" class="form-label">Nama Kost <span class="required">*</span></label>
                    <input type="text" class="form-control" id="namaKost" name="kost_name" placeholder="Masukkan nama kost" required>
                    <div class="invalid-feedback">
                        Nama kost wajib diisi
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="alamatKost" class="form-label">Alamat Kost <span class="required">*</span></label>
                    <textarea class="form-control" id="alamatKost" name="address" rows="3" placeholder="Masukkan alamat lengkap kost" required></textarea>
                    <div class="invalid-feedback">
                        Alamat kost wajib diisi
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </form>
            
            <div class="login-link">
                <p class="mb-0">Sudah punya akun? <a href="./login.php">Masuk di sini</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.getElementById('signupForm').addEventListener('submit', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            const form = this;
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let isValid = true;
            
            // Reset previous validation states
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
            
            // Validate each required field
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    // Special validation for email
                    if (input.type === 'email') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(input.value)) {
                            input.classList.add('is-invalid');
                            isValid = false;
                        }
                    }
                    
                    // Special validation for phone
                    if (input.type === 'tel') {
                        const phoneRegex = /^[0-9]{10,13}$/;
                        if (!phoneRegex.test(input.value.replace(/\s/g, ''))) {
                            input.classList.add('is-invalid');
                            isValid = false;
                        }
                    }
                    
                    // Special validation for password
                    if (input.type === 'password') {
                        if (input.value.length < 6) {
                            input.classList.add('is-invalid');
                            isValid = false;
                        }
                    }
                }
            });
            
            if (isValid) {
                this.submit();
                console.log('Form data:', Object.fromEntries(new FormData(form)));
            } else {
                alert('Mohon lengkapi semua field yang wajib diisi dengan benar.');
            }
        });
        
        // Real-time validation feedback
        document.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
        
        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 13) {
                value = value.slice(0, 13);
            }
            e.target.value = value;
        });
    </script>
</body>
</html>