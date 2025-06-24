<?php 

    session_start();
    require __DIR__ . '/../config/database.php';
    require __DIR__ . '/../model/change_pass_model.php';

    $_SESSION['pass_changed'] = false;
    $_SESSION['change_pass_message'] = '';

    if (!empty($_POST)){
        $email = $_POST['email'];
        $password = $_POST['new-password'];

        if (!email_valid($email, $conn)){
            $_SESSION['change_pass_message'] = 'email tidak ditemukan / belum terdaftar';
        } 
        else {
            change_password($email, $password, $conn);
            $_SESSION['change_pass_message'] = 'kata sandi berhasil diubah';
            $_SESSION['pass_changed'] = true;
        }
        header('Location: ../forgot_password.php');
        exit();
    }
?>
