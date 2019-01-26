<?php 
if (!empty($_POST)) {
	try {
		$rows = DB::delete('DELETE FROM `users` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
		if ($rows) {
			Flash::set('success','User deleted');
			Router::redirect('users/index');
		}
		$error = 'User not deleted';
	} catch (DBError $e) {
		$error = 'User not saved: '.$e->getMessage();
	}
}