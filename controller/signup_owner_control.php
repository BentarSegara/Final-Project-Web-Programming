<?php 
    require __DIR__ . '/../config/database.php';
    require __DIR__ . '/../model/signup_owner_model.php';
    
    if (!empty($_POST)){
        $owner = array_slice($_POST, 0, 5);
        $kost = array_slice($_POST, 5, sizeof($_POST));

        insert_new_owner($owner, $conn);
        insert_new_kost($kost, $owner['username'], $conn);

        header('Location: ../login.php');
        exit();
    }
?>