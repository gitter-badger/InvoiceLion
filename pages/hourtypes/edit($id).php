<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($errors)) {
		try {
			$rowsAffected = DB::update('UPDATE `hourtypes` SET `name`=? WHERE `tenant_id` = ? AND `id` = ?', $data['hourtypes']['name'], $_SESSION['user']['tenant_id'], $id);
			if ($rowsAffected!==false) {
				Flash::set('success','Hour type saved');
				Router::redirect('hourtypes/view/'.$id);
			}
			$error = 'Hour type not saved';
		} catch (DBError $e) {
			$error = 'Hour type not saved: '.$e->getMessage();
		}
	}
	
} else {
	$data = DB::selectOne('SELECT * from `hourtypes` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
}