<?php 
$data = DB::selectOne('select * from `customers` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);

$invoices = DB::select('select * from `invoices` where `tenant_id` = ? AND `customer_id`=? order by number desc', $_SESSION['user']['tenant_id'], $id);
