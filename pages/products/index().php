<?php 
$data = DB::select('select * from `products` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
