<?php 
if(!isset($order) || !in_array($order,['id','date','project_id','name','subtotal','invoice_id','type','comment'])) $order = 'date';
if(!isset($asc) || !in_array($asc,['asc','desc'])) $asc = 'desc';

$data = DB::select('select * from `deliveries` WHERE `tenant_id` = ? order by `'.$order.'` '.$asc.', `id` '.$asc.' limit 200', $_SESSION['user']['tenant_id']);

$invoices = DB::selectPairs('select `id`,`name` from `invoices` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$projects = DB::selectPairs('select `id`,`name` from `projects` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
