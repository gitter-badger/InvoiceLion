<?php 
$projects = DB::select('select `id`,`name`,`customer_id` from `projects` WHERE `tenant_id` = ? and `active` ORDER BY name', $_SESSION['user']['tenant_id']);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ? ORDER BY name', $_SESSION['user']['tenant_id']);

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$delivery = DB::selectOne('select * from `deliveries` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
	$data = $_POST;

	if (!$data['deliveries']['project_id']) $data['deliveries']['project_id']=NULL;
	if (!$data['deliveries']['comment']) $data['deliveries']['comment']=NULL;
	if (!isset($data['deliveries']['date'])) $errors['deliveries[date]']='Date not set';

	//set vat_percentage to NULL if the customer has vat_reverse_charge
	$vat_reverse_charge = DB::selectValue('select `vat_reverse_charge` from `customers` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $data['deliveries']['customer_id']);
	if ($vat_reverse_charge) $data['deliveries']['vat_percentage']=NULL;

	if (!isset($errors)) {
		try {

			if($data['deliveries']['vat_percentage']) $total = $data['deliveries']['subtotal']*((100+$data['deliveries']['vat_percentage'])/100); 
			else $total = $data['deliveries']['subtotal'];

			$rowsAffected = DB::update('UPDATE `deliveries` SET 
				`customer_id`=?, 
				`project_id`=?, 
				`date`=?, 
				`name`=?, 
				`subtotal`=?, 
				`vat_percentage`=?,
				`comment`=?
			WHERE `tenant_id` = ? AND `id` = ?', 
				$data['deliveries']['customer_id'], 
				$data['deliveries']['project_id'], 
				$data['deliveries']['date'], 
				$data['deliveries']['name'], 
				$data['deliveries']['subtotal'], 
				$data['deliveries']['vat_percentage'], 
				$data['deliveries']['comment'],
			$_SESSION['user']['tenant_id'], $id);
			
			//only update invoicelines without invoice(_id)
			$optional = DB::update('UPDATE `invoicelines` SET 
				`customer_id`=?, 
				`name`=?, 
				`subtotal`=?, 
				`vat`=?,
				`vat_percentage`=?,
				`total`=? 
			WHERE `tenant_id` = ? AND `id` = ? AND `invoice_id` IS NULL', 
				$data['deliveries']['customer_id'], 
				$data['deliveries']['name'], 
				$data['deliveries']['subtotal'], 
				($total - $data['deliveries']['subtotal']),
				$data['deliveries']['vat_percentage'], 
				$total,
			$_SESSION['user']['tenant_id'], $delivery['deliveries']['invoiceline_id']);

			if ($rowsAffected!==false) {
				Flash::set('success','deliveries saved');
				Router::redirect('deliveries/view/'.$id);
			}
			$error = 'deliveries not saved';
		} catch (DBError $e) {
			$error = 'deliveries not saved: '.$e->getMessage();
		}
	}	
} else {
	$data = DB::selectOne('SELECT * from `deliveries` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
}