<?php 
    require __DIR__ . '/../config/database.php';
    require __DIR__ . '/../model/pendaftaran_model.php';

    session_start();
    $id = $_SESSION['user_id'];
    if (isset($_POST['submit'])){
        $data = $_POST;
        insert_new_room($id, $conn, $data);
        header("Location: ../view/admin/manage-pd.php");
        exit();
    }
    else echo "Penambahan kamar gagal";
?>