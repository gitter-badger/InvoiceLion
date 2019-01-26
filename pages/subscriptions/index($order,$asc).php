<?php 
if(!isset($order) || !in_array($order,['id','subtotal','months','name','from','canceled','comment','subscriptiontype_id','customer_id','project_id'])) $order = 'from';
if(!isset($asc) || !in_array($asc,['asc','desc'])) $asc = 'desc';

$data = DB::select('select * from `subscriptions` WHERE `tenant_id` = ? order by `'.$order.'` '.$asc, $_SESSION['user']['tenant_id']);
$subscriptiontypes = DB::selectPairs('select `id`,`name` from `subscriptiontypes` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$projects = DB::selectPairs('select `id`,`name` from `projects`  WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
