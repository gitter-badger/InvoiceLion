<?php 
if (!empty($_POST)) {
	try {
		$rows = DB::delete('DELETE FROM `projects` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
		if ($rows) {
			Flash::set('success','Project deleted');
			Router::redirect('projects/index');
		}
		$error = 'Project not deleted';
	} catch (DBError $e) {
		$error = 'Project not saved: '.$e->getMessage();
	}
}