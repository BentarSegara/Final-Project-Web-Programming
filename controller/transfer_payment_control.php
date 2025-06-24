<?php 
    require __DIR__ . '/../model/transfer_payment_model.php';

    session_start();
    if(isset($_GET['wallet_id'])){
        $id = $_SESSION['user_id'];
        
        $wallet_id = $_GET['wallet_id'];

        $now = date('Y-m-d');
        $owner_data = get_owner_data($id, $conn);
        $room_tenant = get_room_tenant_data($id, $conn);
        $payment_data = get_wallet_details($id, $conn, $wallet_id);
    }
    else {
        echo 'Gagal melanjutkan pembayaran';
    }
?>