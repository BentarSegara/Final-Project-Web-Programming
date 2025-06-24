<?php 
    include_once(__DIR__ . '/../config/database.php');

    function get_room_tenant_data($id, $conn){
        $querry = $conn->query("SELECT  bills.id AS bill_id, users.name AS tenant, rooms.room_number AS room_number, bills.amount AS price
                                FROM tenants
                                INNER JOIN bills ON tenants.id = bills.tenant_id
                                INNER JOIN users ON tenants.userID = users.id
                                INNER JOIN rooms ON tenants.room_id = rooms.id
                                WHERE userID = $id AND status = 'unpaid'");
        
        return $querry->fetch(PDO::FETCH_ASSOC);
    }

    function get_owner_id($id, $conn){
        $querry = $conn->query("SELECT kosts.owner_id AS id
                                FROM tenants
                                INNER JOIN rooms ON tenants.room_id = rooms.id
                                INNER JOIN kosts ON rooms.kost_id = kosts.id
                                WHERE tenants.userID = $id");
        $id = $querry->fetch(PDO::FETCH_ASSOC);
        return $id['id'];
    }
    
    function get_owner_data($id, $conn){
        $owner_id = get_owner_id($id, $conn);

        $querry = $conn->query("SELECT users.name, users.phone_number FROM users WHERE id = $owner_id");
        return $querry->fetch(PDO::FETCH_ASSOC);
    }


    function get_wallet_details($id, $conn, $wallet_id){
            $owner_id = get_owner_id($id, $conn);
            $querry = $conn->query("SELECT rek.wallet_name AS bank, rek.account_name AS name, rek.account_number AS number
                                     FROM payment_wallets AS rek
                                     WHERE rek.id = $wallet_id AND rek.owner_id = $owner_id");
            
            return $querry->fetch(PDO::FETCH_ASSOC);
    }
?>