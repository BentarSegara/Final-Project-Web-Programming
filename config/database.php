<?php 
    $dbservername = 'your_server';
    $dbusername = 'your_username';
    $dbpassword = 'your_password';
    $dbname = 'your_database_name_for_this_project';

    try{
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $err){
        echo "Failed to Connect to Database Server: " . $err->getMessage();
    }
?>
