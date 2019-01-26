<?php 
if (!empty($_POST)) {
	$error = 'Invoice not deleted';
	try {
		$rows = DB::delete('DELETE FROM `invoices` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
		if ($rows) {
			Flash::set('success','Invoice deleted');
			Router::redirect('invoices/index');
		}
	} catch (DBError $e) {
		$error.= ': '.$e->getMessage();
	}
}