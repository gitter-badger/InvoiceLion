<?php 
$data = DB::selectOne('select * from `invoices` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$invoicelines = DB::select('select * from `invoicelines` where `tenant_id` = ? AND `invoice_id`=?', $_SESSION['user']['tenant_id'],$data['invoices']['id']);