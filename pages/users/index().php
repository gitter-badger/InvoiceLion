<?php 
$data = DB::select('select * from `users` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
