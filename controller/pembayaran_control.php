<?php 
    require __DIR__. '/../config/database.php';
    require __DIR__.'/../model/pembayaran_model.php';
    require __DIR__.'/../model/tagihan_model.php';
    
    
    session_start();
    $id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $bills = get_bills($id, $conn);
    $payment_wallets = get_payments_wallets($id, $conn);
?>