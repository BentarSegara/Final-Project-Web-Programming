<?php 
    require __DIR__. '/../config/database.php';
    require __DIR__.'/../model/tagihan_model.php';
    
    
    session_start();
    $id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $bills = get_bills($id, $conn);
?>