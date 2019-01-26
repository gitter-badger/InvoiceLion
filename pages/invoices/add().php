<?php 
$highest_invoice_number = DB::selectValue('SELECT MAX(number) FROM invoices WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ? order by name', $_SESSION['user']['tenant_id']);
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if(!isset($data['invoices']['sent'])) $data['invoices']['sent'] = 0;
	if(!isset($data['invoices']['paid'])) $data['invoices']['paid'] = 0;
	if (!isset($errors)) {
		$error = 'Invoice not saved';
		try {
			$id = DB::insert('INSERT INTO `invoices` (`tenant_id`, `number`, `name`, `date`, `sent`, `paid`, `reminder1`, `reminder2`, `customer_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', $_SESSION['user']['tenant_id'], $data['invoices']['number'], $data['invoices']['name'], $data['invoices']['date'], $data['invoices']['sent'], $data['invoices']['paid'], $data['invoices']['reminder1'], $data['invoices']['reminder2'], $data['invoices']['customer_id']);
			if ($id) {
				Flash::set('success','Invoice saved');
				Router::redirect('invoices/index');
			}
		} catch (DBError $e) {
			$error.= ': '.$e->getMessage();
		}
	}
} else {
	$data = array('invoices'=>array('number'=>($highest_invoice_number+1), 'name'=>NULL, 'date'=>Date('Y-m-d'), 'sent'=>NULL, 'paid'=>NULL, 'reminder1'=>NULL, 'reminder2'=>NULL, 'customer_id'=>NULL));
}