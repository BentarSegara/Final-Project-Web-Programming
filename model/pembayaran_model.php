<?php 
    include_once(__DIR__ . '/../config/database.php');

    function get_owner_id($id, $conn){
        $querry = $conn->query("SELECT kosts.owner_id AS id FROM tenants
                                INNER JOIN rooms ON tenants.room_id = rooms.id
                                INNER JOIN kosts ON rooms.kost_id = kosts.id
                                WHERE tenants.id = $id");
        $id = $querry->fetch(PDO::FETCH_ASSOC);
        return $id['id'];
    }

    function get_payments_wallets($id, $conn){
        $owner_id = get_owner_id($id, $conn);
        $querry = $conn->query("SELECT id, wallet_type, wallet_name, account_number, account_name
                                FROM payment_wallets WHERE owner_id = $owner_id");

        return $querry->fetchAll(PDO::FETCH_ASSOC);
    }
?>