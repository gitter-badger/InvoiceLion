<?php 

if(!isset($order)) $order = 'active';
if(!isset($asc)) $asc = 'desc';
$data = DB::select('select * from `projects` WHERE `tenant_id` = ? order by `'.$order.'` '.$asc, $_SESSION['user']['tenant_id']);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ? order by name', $_SESSION['user']['tenant_id']);
