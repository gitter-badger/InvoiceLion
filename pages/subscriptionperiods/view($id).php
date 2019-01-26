<?php 
$data = DB::selectOne('select * from `subscriptionperiods` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
$subscriptions = DB::selectPairs('select `id`,`name` from `subscriptions` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$invoices = DB::selectPairs('select `id`,`name` from `invoices` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$newer_period_exists = DB::selectValue('SELECT count(*) as total from `subscriptionperiods` where `tenant_id` = ? AND `subscription_id`=? AND `from` > ?', $_SESSION['user']['tenant_id'], $data['subscriptionperiods']['subscription_id'], $data['subscriptionperiods']['from']);