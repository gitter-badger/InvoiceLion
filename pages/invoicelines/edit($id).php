<?php 
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ? ORDER BY name', $_SESSION['user']['tenant_id']);

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;

    if (!$data['invoicelines']['invoice_id']) $data['invoicelines']['invoice_id']=NULL;

	//set vat_percentage to NULL if the customer has vat_reverse_charge
	$vat_reverse_charge = DB::selectValue('select `vat_reverse_charge` from `customers` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $data['invoicelines']['customer_id']);
	if ($vat_reverse_charge) $data['invoicelines']['vat_percentage']=NULL;

	if (!isset($errors)) {
		try {

			$subtotal = $data['invoicelines']['subtotal'];
			if($data['invoicelines']['vat_percentage']) {
                $total = $subtotal*((100+$data['invoicelines']['vat_percentage'])/100); 
            }
			else $total = $subtotal;
            $vat = $total - $subtotal;

			$rowsAffected = DB::update('UPDATE `invoicelines` SET 
				`customer_id`=?, 
				`invoice_id`=?, 
				`name`=?, 
				`subtotal`=?, 
				`vat`=?,
                `vat_percentage`=?,
				`total`=?
			WHERE `tenant_id` = ? AND `id` = ? AND invoice_id IS NULL', 
				$data['invoicelines']['customer_id'], 
				$data['invoicelines']['invoice_id'], 
				$data['invoicelines']['name'], 
                $subtotal, 
                $vat,
				$data['invoicelines']['vat_percentage'], 
				$total,
			$_SESSION['user']['tenant_id'], $id);
			
			if ($rowsAffected!==false) {
				Flash::set('success','Invoiceline saved');
				Router::redirect('invoicelines/view/'.$id);
			}
			$error = 'Invoiceline not saved';
		} catch (DBError $e) {
			$error = 'Invoiceline not saved: '.$e->getMessage();
		}
	}	
} else {
	$data = DB::selectOne('SELECT * from `invoicelines` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
}