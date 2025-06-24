<?php 
    include_once(__DIR__ . '/../config/database.php');

    function email_valid($email, $conn){
        $querry = $conn->query("SELECT name FROM users WHERE email = '$email'");
        
        return !empty($querry->fetch(PDO::FETCH_ASSOC)['name']);
    }

    function change_password($email, $new_password, $conn){
        $password = password_hash($new_password, PASSWORD_DEFAULT);
        $querry = $conn->prepare("UPDATE users SET password = ? WHERE email = ? ");
        $querry->execute([$password, $email]);
    }
?>