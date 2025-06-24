<?php 
    include_once('../../controller/tagihan_control.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan - Sistem Kos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/tagihan-style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-home"></i> Kosan </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="beranda.php">
                            <i class="fas fa-user me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="">
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
                        <span id="currentUser"><?= $username ?></span>
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
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-file-invoice-dollar fa-lg text-white"></i>
                    </div>
                    <div>
                        <h3 class="mb-1">Tagihan Bulanan</h3>
                        <p class="text-muted mb-0">Daftar tagihan kos Anda</p>
                    </div>
                </div>

                <div id="billsList">
                    <!-- Tagihan akan dimuat di sini -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        let billsData = [];
        let username = <?= json_encode($username) ?>;

        function getRestDayPayment(due_date) {
            const dueDate = new Date(due_date);
            const today = new Date();

            let restDay = (dueDate - today) / (1000 * 60 * 60 * 24);
            restDay = Math.floor(restDay);

            return (restDay + 1);
        }   
        
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
        }

        function formatDisplayDate(dateString) {
            if (!dateString) return '-';
            const [year, month, day] = dateString.split('-');
            const dateObj = new Date(year, month - 1, day);
            return dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }

        function loadBills() {                        
            const billsListDiv = document.getElementById('billsList');
            billsListDiv.innerHTML = ''; 

            if (billsData.length === 0) {
                billsListDiv.innerHTML = '<p class="text-center text-muted">Tidak ada data tagihan.</p>';
                return;
            }

            billsData.forEach(bill => {
                console.log(bill);                
                const billCard = document.createElement('div');
                billCard.className = `card bill-card p-3 mb-3 ${bill.status}`;
                
                let restDay = getRestDayPayment(bill.due_date);
                let statusText, statusClass = 'status-unpaid';
                
                console.log(restDay);
                

                if(restDay < 0) statusText = 'Nunggak ' + Math.abs(restDay) + ' Hari';
                else statusText = 'Tersisa ' + restDay + ' Hari';

                billCard.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Tagihan ${bill.month + " " + bill.year}</h5>
                            <p class="mb-1 text-muted">Jumlah: ${formatCurrency(bill.amount)}</p>
                            <p class="mb-0 text-muted small">Jatuh Tempo: ${formatDisplayDate(bill.due_date)}</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger">${statusText}</span>
                        </div>
                    </div>
                `;
                billsListDiv.appendChild(billCard);
            });
        }

        function logout() {
            localStorage.clear();
            alert('Anda telah logout.');
            window.location.href = '../login.php'; 
        }

        document.addEventListener('DOMContentLoaded', function() {
            const savedBills = (<?= json_encode($bills) ?>);
            if (savedBills) {
                billsData = savedBills.filter(bill => bill.status === 'unpaid');                
                document.getElementById('currentUser').textContent = username;
            }
            loadBills();
        });
    </script>
</body>
</html>