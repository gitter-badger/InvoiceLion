<?php 
$data = DB::selectOne('select * from `projects` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ? order by name', $_SESSION['user']['tenant_id']);
$hours = DB::select('select * from `hours` where `tenant_id` = ? AND `project_id`=? ORDER BY date DESC', $_SESSION['user']['tenant_id'],$data['projects']['id']);
$subscriptionperiods = DB::select('select subscriptionperiods.*, subscriptions.fee from subscriptionperiods, subscriptions where subscriptionperiods.`tenant_id` = ? AND subscriptions.project_id=? and subscriptions.id = subscriptionperiods.subscription_id ORDER BY subscriptionperiods.from DESC', $_SESSION['user']['tenant_id'],$data['projects']['id']);