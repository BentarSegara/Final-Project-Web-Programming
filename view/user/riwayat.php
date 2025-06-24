<?php 
    include_once('../../controller/tagihan_control.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembayaran - Sistem Kos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/riwayat-style.css">
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
                        <a class="nav-link" href="pembayaran.php">
                            <i class="fas fa-credit-card me-1"></i>Pembayaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="">
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
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="summary-card">
                    <h4 id="totalPayments">0</h4>
                    <p>Total Pembayaran</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <h4 id="onTimePayment">0</h4>
                    <p>Tagihan Tepat Waktu</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <h4 id="latePayment">0</h4>
                    <p>Tagihan Terlambat</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card">
                    <h4 id="totalAmount">Rp 0</h4>
                    <p>Total Dibayar</p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-white">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="all">Semua Status</option>
                        <option value="paid">Tepat Waktu</option>
                        <option value="unpaid">Terlambat</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white">Tahun</label>
                    <select class="form-select" id="yearFilter">
                        <option value="all">Semua Tahun</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white">Bulan</label>
                    <select class="form-select" id="monthFilter">
                        <option value="all">Semua Bulan</option>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-primary text-light" onclick="resetFilters()">
                        <i class="fas fa-refresh me-1"></i>Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- History Content -->
        <div class="profile-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-history fa-lg text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-1">Riwayat Tagihan</h3>
                        <p class="text-muted mb-0">Riwayat lengkap pembayaran kos</p>
                    </div>
                    <div>
                        <button class="btn btn-primary" onclick="exportHistory()">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                    </div>
                </div>

                <div id="paymentHistoryList">
                    <!-- History items will be loaded here -->
                </div>

                <!-- Pagination -->
                <nav aria-label="History pagination" id="paginationNav" style="display: none;">
                    <ul class="pagination justify-content-center" id="paginationList">
                        <!-- Pagination items will be generated here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        let allBillsData = [];

        let filteredData = [];
        let currentPage = 1;
        const itemsPerPage = 5;

        function getDateDifference(due_date, payment_date) {
            const dueDate = new Date(due_date);
            const paymentDate = new Date(payment_date);

            let diff = (paymentDate - dueDate) / (1000 * 60 * 60 * 24);
            diff = Math.floor(diff);

            return (diff);
        }

        function getPaymentStatus(due_date, payment_date){
            const restDay = getDateDifference(due_date, payment_date);
            if (restDay <= 0) return 'tepat waktu';
            else return 'terlambat';
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

        function loadData() {
            const savedData = <?= json_encode($bills) ?>;
                if (savedData) {
                    allBillsData = savedData.filter(bills => bills.status === 'paid');
                }
        }

        function updateSummaryCards() {
            const totalPayments = allBillsData.length;
            const onTimePayment = allBillsData.filter(bill => getPaymentStatus(bill.due_date, bill.pay_date) === 'tepat waktu').length;
            const latePayment = allBillsData.filter(bill => getPaymentStatus(bill.due_date, bill.pay_date) === 'terlambat').length;
            const totalAmount = allBillsData
                                .reduce((sum, bill) => sum + bill.amount, 0);

            document.getElementById('totalPayments').textContent = totalPayments;
            document.getElementById('onTimePayment').textContent = onTimePayment;
            document.getElementById('latePayment').textContent = latePayment;
            document.getElementById('totalAmount').textContent = formatCurrency(totalAmount);
        }

        function loadYearFilter() {
            const yearSet = new Set();
            allBillsData.forEach(bill => {
                const year = bill.year;
                yearSet.add(year);
            });

            const yearFilter = document.getElementById('yearFilter');
            yearSet.forEach(year => {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearFilter.appendChild(option);
            });
        }

        function applyFilters() {
            const statusFilter = document.getElementById('statusFilter').value;
            const yearFilter = document.getElementById('yearFilter').value;
            const monthFilter = document.getElementById('monthFilter').value;

            filteredData = allBillsData.filter(bill => {
                let passStatus = statusFilter === 'all' || getPaymentStatus(bill.due_date, bill.pay_date) === statusFilter.toLowerCase();
                let passYear = yearFilter === 'all' || bill.due_date.startsWith(yearFilter);
                let passMonth = monthFilter === 'all' || bill.due_date.includes(`-${monthFilter}-`);
                
                return passStatus && passYear && passMonth;
            });

            filteredData.sort((a, b) => {
                const dateA = new Date(a.pay_date || a.due_date);
                const dateB = new Date(b.pay_date || b.due_date);
                return dateB - dateA;
            });

            currentPage = 1;
            loadPaymentHistory();
        }

        function resetFilters() {
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('yearFilter').value = 'all';
            document.getElementById('monthFilter').value = 'all';
            applyFilters();
        }

        function loadPaymentHistory() {
            const historyListDiv = document.getElementById('paymentHistoryList');
            historyListDiv.innerHTML = '';

            if (filteredData.length === 0) {
                historyListDiv.innerHTML = '<p class="text-center text-muted">Belum ada data riwayat pembayaran.</p>';
                document.getElementById('paginationNav').style.display = 'none';
                return;
            }

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredData.slice(startIndex, endIndex);

            pageData.forEach(item => {
                const historyItemDiv = document.createElement('div');
                historyItemDiv.className = `history-item ${getPaymentStatus(item.due_date) === 'terlambat' ? 'terlambat' : ''}`;
                
                let statusBadge;
                let paymentDetails = '';

                if (getPaymentStatus(item.due_date, item.pay_date) === 'tepat waktu') {
                    statusBadge = `<span class="badge bg-success">TEPAT WAKTU</span>`;
                    paymentDetails = `<p class="small text-muted mt-1 mb-0">Dibayar pada: ${formatDisplayDate(item.pay_date)}</p>`;
                } else {
                    statusBadge = `<span class="badge bg-danger">TERLAMBAT</span>`;
                    paymentDetails = `<p class="small text-muted mt-1 mb-0">Dibayar pada: ${formatDisplayDate(item.pay_date)} (telat ${getDateDifference(item.due_date, item.pay_date)} hari)</p>`;
                }

                historyItemDiv.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Tagihan ${item.month}</h5>
                            <p class="mb-1 text-muted">Jumlah: ${formatCurrency(item.amount)}</p>
                            ${paymentDetails}
                        </div>
                        <div class="text-end">
                            ${statusBadge}
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewDetails(${item.id})">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                historyListDiv.appendChild(historyItemDiv);
            });

            updatePagination();
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            const paginationNav = document.getElementById('paginationNav');
            const paginationList = document.getElementById('paginationList');

            if (totalPages <= 1) {
                paginationNav.style.display = 'none';
                return;
            }

            paginationNav.style.display = 'block';
            paginationList.innerHTML = '';

            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Previous</a>`;
            paginationList.appendChild(prevLi);

            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    const pageLi = document.createElement('li');
                    pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    pageLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i})">${i}</a>`;
                    paginationList.appendChild(pageLi);
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    const ellipsisLi = document.createElement('li');
                    ellipsisLi.className = 'page-item disabled';
                    ellipsisLi.innerHTML = '<a class="page-link" href="#">...</a>';
                    paginationList.appendChild(ellipsisLi);
                }
            }

            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Next</a>`;
            paginationList.appendChild(nextLi);
        }

        function changePage(page) {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                loadPaymentHistory();
            }
        }

        function viewDetails(billId) {
            const bill = allBillsData.find(b => b.id === billId);
            if (bill) {
                const modalContent = `
                    <div class="modal fade" id="detailModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Tagihan ${bill.month}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table">
                                        <tr>
                                            <td><strong>Periode</strong></td>
                                            <td>${bill.month}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jumlah</strong></td>
                                            <td>${formatCurrency(bill.amount)}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jatuh Tempo</strong></td>
                                            <td>${formatDisplayDate(bill.due_date)}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Bayar</strong></td>
                                            <td>${formatDisplayDate(bill.pay_date)}</td>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td><span class="badge ${getPaymentStatus(bill.due_date, bill.pay_date) === 'tepat waktu' ? 'bg-success' : 'bg-danger'}">${getPaymentStatus(bill.due_date, bill.pay_date) === 'tepat waktu' ? 'TEPAT WAKTU' : 'TERLAMBAT'}</span></td>
                                        </tr>
                                        </tr>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                const existingModal = document.getElementById('detailModal');
                if (existingModal) {
                    existingModal.remove();
                }
                
                document.body.insertAdjacentHTML('beforeend', modalContent);
                const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                modal.show();
            }
        }

        function exportHistory() {
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Periode,Jumlah,Jatuh Tempo,Status,Tanggal Bayar\n";
            
            filteredData.forEach(bill => {
                const row = [
                    bill.month,
                    bill.amount,
                    bill.due_date,
                    getPaymentStatus(bill.due_date, bill.pay_date) === 'tepat waktu' ? 'TEPAT WAKTU' : 'TERLAMBAT',
                    bill.paidDate || '-'
                ].join(',');
                csvContent += row + "\n";
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "riwayat_pembayaran.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function logout() {
            alert('Anda telah logout.');
            window.location.href = '../login.php';
        }

        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('yearFilter').addEventListener('change', applyFilters);
        document.getElementById('monthFilter').addEventListener('change', applyFilters);

        document.addEventListener('DOMContentLoaded', function() {
            loadData();
            updateSummaryCards();
            loadYearFilter();
            applyFilters();  
        });
    </script>
</body>
</html>