<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($errors)) {
		try {
			$rowsAffected = DB::update('UPDATE `subscriptiontypes` SET `name`=?, `comment`=? WHERE `tenant_id` = ? AND `id` = ?', $data['subscriptiontypes']['name'], $data['subscriptiontypes']['comment'], $_SESSION['user']['tenant_id'], $id);
			if ($rowsAffected!==false) {
				Flash::set('success','Subscription type saved');
				Router::redirect('subscriptiontypes/view/'.$id);
			}
			$error = 'Subscription type not saved';
		} catch (DBError $e) {
			$error = 'Subscription type not saved: '.$e->getMessage();
		}
	}
} else {
	$data = DB::selectOne('SELECT * from `subscriptiontypes` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
}