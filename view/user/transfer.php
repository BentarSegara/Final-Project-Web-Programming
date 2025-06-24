<?php 
    include_once('../../controller/transfer_payment_control.php');  
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran Transfer Bank</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/confirm-payment-tf.css">
    <style>
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="payment-card">
                    <div class="card-header">
                        <div class="icon-payment">
                            <i class="fas fa-university"></i>
                        </div>
                        <h4>Pembayaran Transfer Bank</h4>
                        <small class="opacity-75">Pembayaran Sewa Kamar</small>
                    </div>
                    
                    <div class="card-body">
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-hashtag me-2 text-primary"></i>ID Pembayaran
                            </span>
                            <span class="info-value" id="bill_id"> - </span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-user me-2 text-primary"></i>Nama Penghuni
                            </span>
                            <span class="info-value" id="tenant"> - </span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-door-open me-2 text-primary"></i>No Kamar
                            </span>
                            <span class="info-value" id="room_number"> - </span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-money-bill-wave me-2 text-primary"></i>Jumlah Bayar
                            </span>
                            <span class="info-value amount-highlight" id="price"> - </span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>Tanggal Bayar
                            </span>
                            <span class="info-value" id="pay_date"> - </span>
                        </div>
                        
                        <div class="alert-custom">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Penting:</strong> Silakan transfer jumlah pembayaran ke rekening berikut:
                        </div>
                        
                        <div class="bank-section">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bank-logo">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 text-primary">Metode Pembayaran - <?= $payment_data['bank'] ?></h5>
                                    <small class="text-muted">Transfer ke rekening tujuan</small>
                                </div>
                            </div>
                            
                            <div class="bank-info">
                                <div class="bank-row">
                                    <span class="bank-label">Nama Rekening:</span>
                                    <span class="bank-value" id="rek-name">
                                        <?= $payment_data['name'] ?>
                                        <button class="copy-btn" onclick="copyToClipboard(<?= $payment_data['name'] ?>, this)">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="bank-row">
                                    <span class="bank-label">No. Rekening:</span>
                                    <span class="bank-value" id="rek-number">
                                        <?= $payment_data['number'] ?>
                                        <button class="copy-btn" onclick="copyToClipboard(<?= $payment_data['number'] ?>, this)">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="bank-row">
                                    <span class="bank-label">Jumlah Transfer:</span>
                                    <span class="bank-value amount-highlight" id="amount">
                                        Rp. <?= $room_tenant['price'] ?>
                                        <button class="copy-btn" onclick="copyToClipboard(<?= $room_tenant['price'] ?>, this)">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="instruction-text">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            <strong>Catatan:</strong> Mohon transfer sesuai jumlah yang tertera untuk memudahkan proses verifikasi pembayaran.
                        </div>
                        
                        <div class="text-center">
                            <p class="text-muted mb-3">
                                Setelah melakukan transfer, klik tombol di bawah ini untuk mengirim bukti transfer ke Admin WhatsApp.
                            </p>
                            
                            <button class="btn btn-whatsapp">
                                <i class="fab fa-whatsapp me-2"></i>
                                Kirim Bukti Transfer ke WhatsApp
                            </button>
                            
                            <button class="btn btn-back">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali ke Beranda
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        let billsData = [];
        let ownerData =  <?= json_encode($owner_data) ?> ?? ['admin', '082138536551'];
        function formatDisplayDate(dateString) {
            if (!dateString) return '-';
            const [year, month, day] = dateString.split('-');
            const dateObj = new Date(year, month - 1, day);
            return dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
        }

        function loadBillsData(){
            const bills = <?= json_encode($room_tenant) ?>;
            const pay_date = <?= json_encode($now) ?>;
            if (bills && pay_date){
                document.getElementById('bill_id').textContent = bills.bill_id;
                document.getElementById('tenant').textContent = bills.tenant;
                document.getElementById('room_number').textContent = bills.room_number;
                document.getElementById('price').textContent = formatCurrency(bills.price);
                document.getElementById('pay_date').textContent = formatDisplayDate(pay_date);

                billsData = bills;
            }
        }

        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(function() {
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.classList.add('copy-success');
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('copy-success');
                }, 2000);
            }).catch(function(err) {
                console.error('Gagal menyalin: ', err);
                // Fallback untuk browser yang tidak mendukung clipboard API
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.classList.add('copy-success');
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('copy-success');
                }, 2000);
            });
        }

        function sendConfirmMessage(){}
        
        document.addEventListener('DOMContentLoaded', function() {
            loadBillsData();
            document.querySelector('.btn-whatsapp').addEventListener('click', function() {
                const message = encodeURIComponent(
                    `Halo ${ownerData.name}, saya telah melakukan pembayaran sewa kamar dengan detail:\n\n` +
                    `ID Pembayaran: ${billsData.bill_id}\n` +
                    `Nama: ${billsData.tenant}\n` +
                    `No Kamar: ${billsData.room_number}\n` +
                    `Jumlah: ${billsData.price}\n` +
                    `Tanggal: ${formatDisplayDate(<?= json_encode($now) ?>)}\n` +
                    `Metode: ${<?= json_encode(value: $payment_data['bank']) ?>}\n` +
                    `Rekening Tujuan: ${<?= json_encode(value: $payment_data['number']) ?>}\n\n` +
                    `Mohon untuk dikonfirmasi. Terima kasih.`
                );
                
                window.open(`https://wa.me/+6282229330509?text=${message}`, '_blank');
                setTimeout(window.location.href = 'tagihan.php', (1000 * 60));
            });
            
            document.querySelector('.btn-back').addEventListener('click', function() {
                window.history.back();
            });
        });
    </script>
</body>
</html>