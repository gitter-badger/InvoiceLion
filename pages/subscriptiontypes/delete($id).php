<?php 
if (!empty($_POST)) {
	try {
		$rows = DB::delete('DELETE FROM `subscriptiontypes` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
		if ($rows) {
			Flash::set('success','Subscription type deleted');
			Router::redirect('subscriptiontypes/index');
		}
		$error = 'Subscription type not deleted';
	} catch (DBError $e) {
		$error = 'Subscription type not saved: '.$e->getMessage();
	}
}