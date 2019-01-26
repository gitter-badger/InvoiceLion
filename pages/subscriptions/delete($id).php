<?php 
if (!empty($_POST)) {
	try {
		$rows = DB::delete('DELETE FROM `subscriptions` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
		if ($rows) {
			Flash::set('success','Subscription deleted');
			Router::redirect('subscriptions/index');
		}
		$error = 'Subscription not deleted';
	} catch (DBError $e) {
		$error = 'Subscription not saved: '.$e->getMessage();
	}
}