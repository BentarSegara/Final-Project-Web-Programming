<?php 
    include_once(__DIR__ . '/../config/database.php');

    function edit_profile($data, $conn){

        $querry = $conn->prepare("UPDATE users SET name = ?, username = ?, phone_number = ?, email = ?
        WHERE id = ? ");

        $querry->execute($data);
    }
?>