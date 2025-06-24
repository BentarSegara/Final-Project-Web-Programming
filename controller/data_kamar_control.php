<?php 
    require __DIR__ . '/../config/database.php';
    require __DIR__ . '/../model/data_kamar_model.php';

    session_start();
    $id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    $rooms_data = get_rooms_data($id, $conn);
?>