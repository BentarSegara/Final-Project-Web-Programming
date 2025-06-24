<?php 
    require __DIR__ .'/../config/database.php';
    require __DIR__ .'/../model/data_penghuni_model.php';

    session_start();
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    $tenants_data = get_tenants_data($user_id, $conn);
?>