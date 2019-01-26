<?php 
if(!isset($order) || !in_array($order,['id','number','name','date','sent','paid','reminder1','reminder2','customer_id'])) $order = 'number';
if(!isset($asc) || !in_array($asc,['asc','desc'])) $asc = 'desc';
if($order == 'paid') $order = 'sent, paid';

$data = DB::select('select * from `invoices` WHERE `tenant_id` = ? order by '.$order.' '.$asc, $_SESSION['user']['tenant_id']);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);