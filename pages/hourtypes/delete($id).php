<?php 
if (!empty($_POST)) {
	try {
		$rows = DB::delete('DELETE FROM `hourtypes` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
		if ($rows) {
			Flash::set('success','Hour type deleted');
			Router::redirect('hourtypes/index');
		}
		$error = 'Hour type not deleted';
	} catch (DBError $e) {
		$error = 'Hour type not saved: '.$e->getMessage();
	}
}