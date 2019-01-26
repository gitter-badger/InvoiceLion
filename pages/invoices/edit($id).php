<?php 
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ? order by name', $_SESSION['user']['tenant_id']);
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($customers[$data['invoices']['customer_id']])) $errors['invoices[customer_id]']='Customer not found';
	if (!isset($errors)) {
		try {
			$rowsAffected = DB::update('UPDATE `invoices` SET `number`=?, `name`=?, `date`=?, `sent`=?, `paid`=?, `reminder1`=?, `reminder2`=?, `customer_id`=? WHERE `tenant_id` = ? AND `id` = ?', $data['invoices']['number'], $data['invoices']['name'], $data['invoices']['date'], $data['invoices']['sent'], $data['invoices']['paid'], $data['invoices']['reminder1'], $data['invoices']['reminder2'], $data['invoices']['customer_id'], $_SESSION['user']['tenant_id'], $id);
			if ($rowsAffected!==false) {
				Flash::set('success','Invoices saved');
				Router::redirect('invoices/view/'.$id);
			}
			$error = 'Invoice not saved';
		} catch (DBError $e) {
			$error = 'Invoice not saved: '.$e->getMessage();
		}
	}
} else {
	$data = DB::selectOne('SELECT * from `invoices` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
}