<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($errors)) {
		try {
			$rowsAffected = DB::update('UPDATE `customers` SET `name`=?, `email`=?, `contact`=?, `address`=?, `vat_reverse_charge`=? WHERE `tenant_id` = ? AND `id` = ?', $data['customers']['name'], $data['customers']['email'], $data['customers']['contact'], $data['customers']['address'], $data['customers']['vat_reverse_charge'], $_SESSION['user']['tenant_id'], $id);
			if ($rowsAffected!==false) {
				Flash::set('success','Customer saved');
				Router::redirect('customers/view/'.$id);
			}
			$error = 'Customer not saved';
		} catch (DBError $e) {
			$error = 'Customer not saved: '.$e->getMessage();
		}
	}	
} else {
	$data = DB::selectOne('SELECT * from `customers` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
}