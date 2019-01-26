<?php 
$data = DB::selectOne('select * from `subscriptions` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
$subscriptiontypes = DB::selectPairs('select `id`,`name` from `subscriptiontypes` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$projects = DB::selectPairs('select `id`,`name` from `projects` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);

$invoiceperiods = DB::select('SELECT * FROM subscriptionperiods WHERE `tenant_id` = ? AND subscription_id=? ORDER BY `from` DESC', $_SESSION['user']['tenant_id'],$data['subscriptions']['id']);