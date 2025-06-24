<?php
    session_start();
    include_once('../config/database.php');

    if(isset($_POST['username'])){
        $id = $_SESSION['user_id'];
        $name = $_POST['name'];
        $username = $_POST['username'];
        $phone_number = $_POST['phone_number'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password']);

        $querry = $conn->query("UPDATE users SET name = '$name', username = '$username', phone_number = '$phone_number', email = '$email', password = '$password'
                   WHERE id = $id");
    };
?>