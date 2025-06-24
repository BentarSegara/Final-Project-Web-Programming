<?php 
    include_once('../../controller/data_kamar_control.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="view-transition" content="same-origin">
    <link rel="stylesheet" href="../../css/general.css">
    <title>Sistem Kosan [Admin]</title>
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/divs-manage.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="./dashboard.php"><i class="fas fa-home"></i> Kosan <strong>[Admin]</strong></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-user me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage-dpk.php">
                           <i class="fa-sharp-duotone fa-solid fa-gears me-1"></i>Manage
                        </a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-circle me-1"></i>
                        <a href="profile.php" class="text-decoration-none"><?= $username ?></a>
                    </span>
                    <a class="nav-link" href="#" onclick="logout()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div class="navbar-collapse" id="navbar-manage">
                <ul class="navbar-manage me-auto">
                    <li class="nav-man-item-manage">
                        <a class="nav-link" href="manage-dpk.php">
                            <i class="fas fa-user me-3"></i>Data Penyewa Kos
                        </a>
                    </li>
                    <li class="nav-man-item-manage">
                        <a class="nav-link active" href="manage-dk.php">
                           <i class="fa-solid fa-bed me-3"></i>Data Kamar
                        </a>
                    </li>
                    <li class="nav-man-item-manage">
                        <a class="nav-link" href="manage-pd.php">
                           <i class="fa-solid fa-clipboard me-3"></i>Pendaftaran
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
  
    <table class="table table-striped mt-5 mx-auto" style="width: 80%">
        <thead class="table table-dark text-center">
            <tr>
                <th>No Kamar</th>
                <th>Harga sewa</th>
                <th>Penghuni saat ini</th>
            </tr>
        </thead>
        <tbody class="text-center" id="tbody">
            <!-- table content -->
        </tbody>
    </table>

    <script>
        let rooms_data = [];

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
        }

        function loadData() {
        const savedData = <?= json_encode($rooms_data) ?>;
        if (savedData) rooms_data = savedData;
        }

        function loadDataTable(params) {
            const body = document.getElementById('tbody');        
            body.innerHTML = '';
                if (rooms_data.length === 0){
                    body.innerHTML = `
                    <tr>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                    </tr>
                    `;
                    return;
                }

                rooms_data.forEach(room => {                
                    body.innerHTML += `
                        <tr>
                            <td>${room.room_number}</td>
                            <td>${formatCurrency(room.price)}</td>
                            <td>${room.tenant}</td>
                        </tr>
                    `;

                });
        }

        function logout() {
            localStorage.clear();
            alert('Anda telah logout.');
            window.location.href = '../login.php'; 
        }

        document.addEventListener('DOMContentLoaded', function() {
        loadData();
        loadDataTable();
        });
    </script>


  </body>
</html>