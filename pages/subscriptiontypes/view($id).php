<?php 
$data = DB::selectOne('select * from `subscriptiontypes` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
$subscriptions = DB::select('select * from `subscriptions` where `tenant_id` = ? AND `subscriptiontype_id`=?', $_SESSION['user']['tenant_id'],$id);