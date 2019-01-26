<?php 
$projects = DB::select('select `id`,`name`,`customer_id` from `projects` WHERE `tenant_id` = ? and `active` ORDER BY name', $_SESSION['user']['tenant_id']);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ? ORDER BY name', $_SESSION['user']['tenant_id']);
$hourtypes = DB::selectPairs('select `id`,`name` from `hourtypes` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$hour = DB::selectOne('select * from `hours` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
	$data = $_POST;

	if (!$data['hours']['project_id']) $data['hours']['project_id']=NULL;
	if (!$data['hours']['comment']) $data['hours']['comment']=NULL;
	if (!$data['hours']['type']) $data['hours']['type']=NULL;
	if (!isset($data['hours']['date'])) $errors['hours[date]']='Date not set';

	//set vat_percentage to NULL if the customer has vat_reverse_charge
	$vat_reverse_charge = DB::selectValue('select `vat_reverse_charge` from `customers` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $data['hours']['customer_id']);
	if ($vat_reverse_charge) $data['hours']['vat_percentage']=NULL;

	if (!isset($errors)) {
		try {

			$subtotal = $data['hours']['hours_worked']*$data['hours']['hourly_fee'];
			if($data['hours']['vat_percentage']) $total = $subtotal*((100+$data['hours']['vat_percentage'])/100); 
			else $total = $subtotal;

			$rowsAffected = DB::update('UPDATE `hours` SET 
				`customer_id`=?, 
				`project_id`=?, 
				`date`=?, 
				`name`=?, 
				`hours_worked`=?, 
				`hourly_fee`=?, 
				`subtotal`=?, 
				`vat_percentage`=?,
				`type`=?, 
				`comment`=?
			WHERE `tenant_id` = ? AND `id` = ?', 
				$data['hours']['customer_id'], 
				$data['hours']['project_id'], 
				$data['hours']['date'], 
				$data['hours']['name'], 
				$data['hours']['hours_worked'],
				$data['hours']['hourly_fee'],
				$subtotal, 
				$data['hours']['vat_percentage'], 
				$data['hours']['type'], 
				$data['hours']['comment'],
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
				$data['hours']['customer_id'], 
				$data['hours']['name'], 
				$subtotal,
				($total - $subtotal),
				$data['hours']['vat_percentage'], 
				$total,
			$_SESSION['user']['tenant_id'], $hour['hours']['invoiceline_id']);

			if ($rowsAffected!==false) {
				Flash::set('success','Hours saved');
				Router::redirect('hours/view/'.$id);
			}
			$error = 'Hours not saved';
		} catch (DBError $e) {
			$error = 'Hours not saved: '.$e->getMessage();
		}
	}	
} else {
	$data = DB::selectOne('SELECT * from `hours` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
}