<?php 
if (!empty($_POST)) {
	try {
		$rows = DB::delete('DELETE FROM `customers` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
		if ($rows) {
			Flash::set('success','Customer deleted');
			Router::redirect('customers/index');
		}
		$error = 'Customer not deleted';
	} catch (DBError $e) {
		$error = 'Customer not saved: '.$e->getMessage();
	}
}