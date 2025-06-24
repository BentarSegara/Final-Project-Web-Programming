<?php 
    include_once('../../controller/profile_control.php');

?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Sistem Kos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-home"></i> Kosan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="">
                            <i class="fas fa-user me-1"></i>Beranda
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="tagihan.php">
                            <i class="fas fa-file-invoice me-1"></i>Tagihan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="pembayaran.php">
                            <i class="fas fa-credit-card me-1"></i>Pembayaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="riwayat.php">
                            <i class="fas fa-history me-1"></i>Riwayat
                        </a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-circle me-1"></i>
                        <span id="currentUser"><?= $users['username']?></span>
                    </span>
                    <a class="nav-link" href="#" onclick="logout()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container main-container">
        <div class="profile-card">
            <div class="card-body p-4">
                <div class="row align-items-center mb-4">
                    <div class="col-auto">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-white"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h3 class="mb-1">Profil Penghuni Kos</h3>
                        <p class="text-muted mb-0">Informasi data pribadi</p>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-primary" id="editProfileBtn" onclick="toggleEditProfile()">
                            <i class="fas fa-edit me-1"></i>Edit Profil
                        </button>
                        <button class="btn btn-success me-2" id="saveProfileBtn" onclick="saveProfile()" style="display: none;">
                            <i class="fas fa-save me-1"></i>Simpan
                        </button>
                        <button class="btn btn-secondary" id="cancelProfileBtn" onclick="cancelEditProfile()" style="display: none;">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                    </div>
                </div>
                
                <div id="profileViewMode">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <p class="form-control-plaintext" id="displayName"><?= $users['name'] ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Username</label>
                                <p class="form-control-plaintext" id="displayUsername"><?= $users['username'] ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nomor Kamar</label>
                                <p class="form-control-plaintext" id="displayRoom"><?= $users['room_number']?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">No. Telepon Pribadi</label>
                                <p class="form-control-plaintext" id="displayPhone"><?= $users['phone_number'] ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="form-control-plaintext" id="displayEmail"><?= $users['email'] ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Masuk</label>
                                <p class="form-control-plaintext" id="displayJoinDate"><?= $users['start_date'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="profileEditMode" style="display: none;">
                    <form id="profileForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="editName" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Username</label>
                                    <input type="text" class="form-control" id="editUsername" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">No. Telepon Pribadi</label>
                                    <input type="tel" class="form-control" id="editPhone" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" id="editEmail" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        let originalProfileData = {}; 
        let userProfile = [];

        function formatDisplayDate(dateString) {
            if (!dateString) return '-';
            const [year, month, day] = dateString.split('-');
            const dateObj = new Date(year, month - 1, day);
            return dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }

        function loadProfileData() {
            document.getElementById('currentUser').textContent = userProfile.username; 
            
            document.getElementById('displayName').textContent = userProfile.name;
            document.getElementById('displayUsername').textContent = userProfile.username;
            document.getElementById('displayRoom').textContent = userProfile.room_number;
            document.getElementById('displayPhone').textContent = userProfile.phone_number;
            document.getElementById('displayEmail').textContent = userProfile.email;
            document.getElementById('displayJoinDate').textContent = formatDisplayDate(userProfile.start_date);

            originalProfileData = JSON.parse(JSON.stringify(userProfile)); 
        }

        function toggleEditProfile() {
            const viewMode = document.getElementById('profileViewMode');
            const editMode = document.getElementById('profileEditMode');
            const editBtn = document.getElementById('editProfileBtn');
            const saveBtn = document.getElementById('saveProfileBtn');
            const cancelBtn = document.getElementById('cancelProfileBtn');

            if (viewMode.style.display !== 'none') { 
                viewMode.style.display = 'none';
                editMode.style.display = 'block';
                editBtn.style.display = 'none';
                saveBtn.style.display = 'inline-block';
                cancelBtn.style.display = 'inline-block';

                document.getElementById('editName').value = userProfile.name;
                document.getElementById('editUsername').value = userProfile.username;
                document.getElementById('editPhone').value = userProfile.phone_number;
                document.getElementById('editEmail').value = userProfile.email;
                
                
            } else { 
                viewMode.style.display = 'block';
                editMode.style.display = 'none';
                editBtn.style.display = 'inline-block';
                saveBtn.style.display = 'none';
                cancelBtn.style.display = 'none';
                loadProfileData();
            }
        }

        function saveProfile() {
            if (!document.getElementById('profileForm').checkValidity()) {
                document.getElementById('profileForm').reportValidity();
                return;
            }

            let changeData = {
                "name" : document.getElementById('editName').value,
                "username" : document.getElementById('editUsername').value,
                "phone_number" : document.getElementById('editPhone').value,
                "email" : document.getElementById('editEmail').value,
            };
            
            userProfile.name = changeData['name'];
            userProfile.username = changeData['username'];
            userProfile.phone_number = changeData['phone_number'];
            userProfile.email = changeData['email'];
            localStorage.setItem('userProfile', JSON.stringify(userProfile));

            sendData(changeData);
        }

        function sendData(changeData) {
            let formData = new FormData();
            Object.keys(changeData).forEach(key => {
                formData.append(key, changeData[key])
            });

            fetch("../../controller/edit_profile_control.php", {
                method : "POST", body : formData
            })
            .then(response => response.text())
            .then(result => console.log(result))
            .catch(error => console.error("Error: ", error));
            alert('Profil berhasil diperbarui!');
            toggleEditProfile(); 
        }
        function cancelEditProfile() {
            userProfile = JSON.parse(JSON.stringify(originalProfileData));
            toggleEditProfile();
        }

        function logout() {
            localStorage.clear();
            alert('Anda telah logout.');
            window.location.href = '../../login.php'; 
        }

        document.addEventListener('DOMContentLoaded', function() {
            const savedProfile =  <?= json_encode($users) ?>;
            if (savedProfile) {
                userProfile = savedProfile;
            }
            loadProfileData();
        });
    </script>
</body>
</html>
