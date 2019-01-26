<?php 
$data = DB::selectOne('select * from `hourtypes` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
