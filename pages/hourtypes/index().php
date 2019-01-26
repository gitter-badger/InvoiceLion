<?php 
$data = DB::select('select * from `hourtypes` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
