<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;

	if (!isset($data['subscriptiontypes']['comment'])) $data['subscriptiontypes']['comment']=NULL;

	if (!isset($errors)) {
		try {
			$id = DB::insert('INSERT INTO `subscriptiontypes` (`tenant_id`, `name`, `comment`) VALUES (?, ?, ?)', $_SESSION['user']['tenant_id'], $data['subscriptiontypes']['name'], $data['subscriptiontypes']['comment']);
			if ($id) {
				Flash::set('success','Subscription type saved');
				Router::redirect('subscriptiontypes/index');
			}
			$error = 'Subscription type not saved';
		} catch (DBError $e) {
			$error = 'Subscription type not saved: '.$e->getMessage();
		}
	}
} else {
	$data = array('subscriptiontypes'=>array('name'=>NULL, 'comment'=>NULL));
}