<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($errors)) {
		try {
			$id = DB::insert('INSERT INTO `users` (`tenant_id`, `username`, `password`, `created`) VALUES (?, ?, ?, ?)', $_SESSION['user']['tenant_id'], $data['users']['username'], $data['users']['password'], $data['users']['created']);
			if ($id) {
				Flash::set('success','User saved');
				Router::redirect('users/index');
			}
			$error = 'User not saved';
		} catch (DBError $e) {
			$error = 'User not saved: '.$e->getMessage();
		}
	}
} else {
	$data = array('users'=>array('username'=>NULL, 'password'=>NULL, 'created'=>NULL));
}