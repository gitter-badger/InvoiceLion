<?php 
$data = DB::select('select * from `subscriptionperiods` WHERE `tenant_id` = ? order by `from` DESC', $_SESSION['user']['tenant_id']);
$subscriptions = DB::selectPairs('select `id`,`name` from `subscriptions` WHERE `tenant_id` = ? order by name', $_SESSION['user']['tenant_id']);
$invoices = DB::selectPairs('select `id`,`name` from `invoices` WHERE `tenant_id` = ? order by number desc', $_SESSION['user']['tenant_id']);
