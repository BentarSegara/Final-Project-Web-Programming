<?php
    session_start();
    require __DIR__ . '/../config/database.php';
    require __DIR__ . '/../model/edit_profile_model.php';
    

    if(isset($_POST['username'])){
        $id = $_SESSION['user_id'];
        $data = [$_POST['name'], $_POST['username'], $_POST['phone_number'],$_POST['email'], $id];
        edit_profile( $data, $conn);
    };
?>