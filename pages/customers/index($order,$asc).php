<?php 
if(!isset($order)) $order = 'name';
if(!isset($asc)) $asc = 'asc';
$data = DB::select('select * from `customers` WHERE `tenant_id` = ? order by `'.$order.'` '.$asc, $_SESSION['user']['tenant_id']);