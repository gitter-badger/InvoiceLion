<?php 
$data = DB::select('select * from `subscriptiontypes` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
