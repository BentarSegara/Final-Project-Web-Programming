<?php 
    include_once('../../controller/pembayaran_control.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Sistem Kos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/pembayaran-style.css">
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
                        <a class="nav-link" href="beranda.php">
                            <i class="fas fa-user me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tagihan.php">
                            <i class="fas fa-file-invoice me-1"></i>Tagihan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="">
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
                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-credit-card fa-lg text-white"></i>
                    </div>
                    <div>
                        <h3 class="mb-1">Pembayaran</h3>
                        <p class="text-muted mb-0">Pilih metode pembayaran</p>
                    </div>
                    <div class="ms-auto">
                        <a href="beranda.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info" id="activePaymentAlert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Tagihan Aktif:</strong> <span id="activeBillText">Memuat informasi tagihan...</span>
                        </div>
                    </div>
                </div>

                <div id="paymentMethodsContainer" class="mb-4">
                    <!-- Payment methods will be loaded here -->
                </div>

                <div id="paymentSuccessAlert" class="alert alert-success d-none mb-4">
                    <i class="fas fa-check-circle me-2"></i>
                    <span id="successMessage"></span>
                </div>

                <div class="text-center mt-4">
                    <a class="btn btn-primary btn-lg text-decoration-none" id="payButton" disabled>
                        <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedPaymentMethod = null;
        let billsData = null;
        let payment_wallets = [];

        function loadBillsData() {
            const storedBills = <?= json_encode($bills) ?>;    
            if (storedBills) {
                billsData = storedBills.filter(bill => bill.status === 'unpaid')[0];
            }
        }
        
        function loadPaymentWallets(){
            const wallets = <?= json_encode($payment_wallets) ?>;
            
            if (wallets) {
                payment_wallets = wallets;
            }
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

        function updatePaymentStatus() {               
            const activeBillText = document.getElementById('activeBillText');
            const payButton = document.getElementById('payButton');
            const paymentMethodsContainer = document.getElementById('paymentMethodsContainer');

            if (billsData) {                
                activeBillText.innerHTML = `<strong>${billsData.month}</strong> - ${formatCurrency(billsData.amount)}, Jatuh tempo: ${formatDisplayDate(billsData.due_date)}`;
                loadPaymentMethods();
                payButton.disabled = selectedPaymentMethod === null;
            } else {
                activeBillText.textContent = 'Tidak ada tagihan yang belum dibayar saat ini.';
                paymentMethodsContainer.innerHTML = '<div class="text-center"><p class="text-muted">Semua tagihan sudah lunas.</p><a href="riwayat.php" class="btn btn-primary">Lihat Riwayat Pembayaran</a></div>';
                payButton.disabled = true;
                selectedPaymentMethod = null;
            }
        }

        function getPaymentDetail(payment, wallet_name){
            const thisPayment = payment_wallets.find(wallet => wallet.wallet_name === wallet_name);

            if(thisPayment){
                const paymentId = thisPayment.id;
                const paymentName = thisPayment.wallet_name;
                const paymentIcon = (thisPayment.wallet_type === 'transfer_bank' ? 'fa-university' : thisPayment.wallet_type === 'dompet_digital' ? 'fa-mobile-alt' : thisPayment.wallet_type === 'qris' ? 'fa-wallet' : 'fa-credit-card');
                const paymentDetails = [thisPayment.account_name, thisPayment.account_number].join("/");
    
                const paymentType = thisPayment.wallet_type;
    
                return {
                    id : paymentId,
                    name : paymentName,
                    icon : paymentIcon,
                    details : paymentDetails,
                    type : paymentType
                }
            }
            return null;
        }

        function loadPaymentMethods() {
            const container = document.getElementById('paymentMethodsContainer');
            container.innerHTML = '';
            const payButton = document.getElementById('payButton');

            const methods = [
                getPaymentDetail(payment_wallets, 'Transfer Bank BRI') ?? {id: null, name: 'Transfer Bank', icon: 'fa-university', details: 'belum tersedia' , type: '' },
                getPaymentDetail(payment_wallets, 'Dana') ?? {id: null, name: 'DANA', icon: 'fa-mobile-alt', details: 'belum tersedia', type: '' },
                getPaymentDetail(payment_wallets, 'GoPay') ?? {id: null, name: 'GoPay', icon: 'fa-mobile-alt', details: 'Belum tersedia', type: '' },
                getPaymentDetail(payment_wallets, 'OVO') ?? {id: null, name: 'OVO', icon: 'fa-mobile-alt', details: 'Belum tersedia' , type: ''},
                getPaymentDetail(payment_wallets, 'Qris') ?? {id: null, name: 'QRIS', icon: 'fa-wallet', details: '', type: 'belum tersedia' },
                getPaymentDetail(payment_wallets, 'Virtual Account') ?? {id: null, name: 'Virtual Account Mandiri', icon: 'fa-credit-card', details: 'Belum tersedia', type: '' },
            ];

            const row = document.createElement('div');
            row.className = 'row';

            methods.forEach(method => {                
                const col = document.createElement('div');
                col.className = 'col-md-6 col-lg-4';
                const optionDiv = document.createElement('div');
                optionDiv.className = 'payment-option text-center';
                optionDiv.innerHTML = `
                    <h5><i class="fas ${method.icon} me-2"></i>${method.name}</h5>
                    <p class="small text-muted mb-0">${method.details}</p>
                `;
                optionDiv.onclick = () => {
                    document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
                    optionDiv.classList.add('selected');
                    selectedPaymentMethod = method.name;
                    payButton.disabled = false;
                    payButton.href = `./transfer.php?wallet_id=${method.id}`;
                };
                col.appendChild(optionDiv);
                row.appendChild(col);
            });
            container.appendChild(row);
        }

        function logout() {
            localStorage.removeItem('userLoggedIn');
            localStorage.removeItem('userType');
            alert('Anda telah logout.');
            window.location.href = '../../login.php';
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadBillsData();
            loadPaymentWallets();
            updatePaymentStatus();
        });
    </script>
</body>
</html>