<?php 
$data = DB::selectOne('select * from `products` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
