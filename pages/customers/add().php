<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($errors)) {
		try {
			$id = DB::insert('INSERT INTO `customers` (`tenant_id`, `name`, `email`, `contact`, `address`, `vat_reverse_charge`) VALUES (?, ?, ?, ?, ?, ?)', $_SESSION['user']['tenant_id'], $data['customers']['name'], $data['customers']['email'], $data['customers']['contact'], $data['customers']['address'], $data['customers']['vat_reverse_charge']);
			if ($id) {
				Flash::set('success','Customer saved');
				Router::redirect('customers/index');
			}
			$error = 'Customer not saved';
		} catch (DBError $e) {
			$error = 'Customer not saved: '.$e->getMessage();
		}
	}	
} else {
	$data = array('customers'=>array('name'=>NULL, 'email'=>NULL, 'contact'=>NULL, 'address'=>NULL, 'vat_reverse_charge'=>NULL));
}