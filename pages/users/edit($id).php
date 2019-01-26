<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($errors)) {
		try {
			$rowsAffected = DB::update('UPDATE `users` SET `username`=?, `password`=?, `created`=? WHERE `tenant_id` = ? AND `id` = ?', $data['users']['username'], $data['users']['password'], $data['users']['created'], $_SESSION['user']['tenant_id'], $id);
			if ($rowsAffected!==false) {
				Flash::set('success','User saved');
				Router::redirect('users/view/'.$id);
			}
			$error = 'User not saved';
		} catch (DBError $e) {
			$error = 'User not saved: '.$e->getMessage();
		}
	}
} else {
	$data = DB::selectOne('SELECT * from `users` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
}