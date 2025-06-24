<?php 
    require __DIR__ . '/../config/database.php';
    require __DIR__ . '/../model/pendaftaran_model.php';

    session_start();
    $id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    $room_list = get_rooms($id, $conn);
?>