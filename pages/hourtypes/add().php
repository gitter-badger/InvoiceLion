<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($errors)) {
		try {
			$id = DB::insert('INSERT INTO `hourtypes` (`tenant_id`, `name`) VALUES (?, ?)', $_SESSION['user']['tenant_id'], $data['hourtypes']['name']);
			if ($id) {
				Flash::set('success','Hour type saved');
				Router::redirect('hourtypes/index');
			}
			$error = 'Hour type not saved';
		} catch (DBError $e) {
			$error = 'Hour type not saved: '.$e->getMessage();
		}
	}
} else {
	$data = array('hourtypes'=>array('name'=>NULL));
}