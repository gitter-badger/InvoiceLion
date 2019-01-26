<?php
$data = DB::selectOne('select * from `invoicelines` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$invoices = DB::selectPairs('select `id`,`name` from `invoices` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$hourtypes = DB::selectPairs('select `id`,`name` from `hourtypes` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);