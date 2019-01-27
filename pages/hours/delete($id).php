<?php 
if (!empty($_POST)) {
	$hour = DB::selectOne('select * from `hours` WHERE `id` = ?', $id);
	try {
		$rows = DB::delete('DELETE FROM `hours` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
		
		if($hour['hours']['invoiceline_id']) {
			//delete the invoiceline if it is not attached to an invoice_id
			$optional = DB::delete('DELETE FROM `invoicelines` WHERE `tenant_id` = ? AND `id`= ? AND `invoice_id` IS NULL', $_SESSION['user']['tenant_id'], $hour['hours']['invoiceline_id']);
		}
		
		if ($rows) {
			Flash::set('success','Hours deleted');
			Router::redirect('hours/index');
		}
		$error = 'Hours not deleted';
	} catch (DBError $e) {
		$error = 'Hours not deleted: '.$e->getMessage();
	}
}