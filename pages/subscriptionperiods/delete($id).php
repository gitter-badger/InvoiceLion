<?php 
if (!empty($_POST)) {
	try {
		$rows = DB::delete('DELETE FROM `subscriptionperiods` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
		if ($rows) {
			Flash::set('success','Subscription period deleted');
			Router::redirect('subscriptionperiods/index');
		}
		$error = 'Subscription period not deleted';
	} catch (DBError $e) {
		$error = 'Subscription period not saved: '.$e->getMessage();
	}
}