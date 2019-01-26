<?php 
if (!empty($_POST)) {
	$hour = DB::selectOne('select * from `deliveries` WHERE `id` = ?', $id);
	try {
		$rows = DB::delete('DELETE FROM `deliveries` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
		
		if($hour['deliveries']['invoiceline_id']) {
			//delete the invoiceline if it is not attached to an invoice_id
			$optional = DB::delete('DELETE FROM `invoicelines` WHERE `tenant_id` = ? AND `id`= ? AND `invoice_id` IS NULL', $_SESSION['user']['tenant_id'], $hour['deliveries']['invoiceline_id'],$id);
		}
		
		if ($rows) {
			Flash::set('success','deliveries deleted');
			Router::redirect('deliveries/index');
		}
		$error = 'deliveries not deleted';
	} catch (DBError $e) {
		$error = 'deliveries not deleted: '.$e->getMessage();
	}
}