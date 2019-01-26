<?php 
if (!empty($_POST)) {
	try {
    	//delete the invoiceline if it is not attached to an invoice_id
		$rows = DB::delete('DELETE FROM `invoicelines` WHERE `tenant_id` = ? AND `id`= ? AND `invoice_id` IS NULL', $_SESSION['user']['tenant_id'],$id);
		
		if ($rows) {
			Flash::set('success','Invoiceline deleted');
			Router::redirect('invoicelines/index');
		}
		$error = 'Invoiceline not deleted';
	} catch (DBError $e) {
		$error = 'Invoiceline not deleted: '.$e->getMessage();
	}
}