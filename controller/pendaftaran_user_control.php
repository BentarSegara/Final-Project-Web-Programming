<?php 
    require __DIR__ . '/../config/database.php';
    require __DIR__ . '/../model/pendaftaran_model.php';

    if (isset($_POST['submit'])){
        $data = $_POST;
        insert_new_tenant($conn, $data);
        header("Location: ../view/admin/manage-pd.php");
        exit();
    }
    else {
        echo 'Pendaftaran gagal';
    }

?>