<?php 
if(!isset($order) || !in_array($order,['name','subtotal','invoice_id','type','comment'])) $order = 'id';
if(!isset($asc) || !in_array($asc,['asc','desc'])) $asc = 'desc';

$data = DB::select('select * from `invoicelines` WHERE `tenant_id` = ? order by `'.$order.'` '.$asc.', `id` '.$asc.' limit 200', $_SESSION['user']['tenant_id']);

$invoices = DB::selectPairs('select `id`,`name` from `invoices` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);