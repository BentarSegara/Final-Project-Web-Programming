<?php 
    require __DIR__ . '/../config/database.php';
    require __DIR__ . '/../model/data_penghuni_model.php';

    if (isset($_GET['tenant_id'])){
        $tenants_id = $_GET['tenant_id'];
        update_tenant_payment_status($tenants_id, $conn);
        header("Location: ../view/admin/manage-dpk.php");
        exit();
    }
    else {
        echo "Gagal memperbarui pembayaran";
    }
?>