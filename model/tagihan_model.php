<?php 
    include_once(__DIR__.'/../config/database.php');

    function get_bills(string $id, PDO $conn) : mixed {
        $user_bills = $conn->query("SELECT bills.id, month, year, amount, due_date, pay_date, status FROM bills
                                            INNER JOIN tenants ON bills.tenant_id = tenants.id
                                            WHERE tenants.userID = $id AND bills.tenant_id = tenants.id");
        
        return $user_bills->fetchAll(PDO::FETCH_ASSOC);
    }
?>