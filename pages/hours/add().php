<?php 
$projects = DB::select('select `id`,`name`,`customer_id` from `projects` WHERE `tenant_id` = ? and `active` ORDER BY name', $_SESSION['user']['tenant_id']);
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ? ORDER BY name', $_SESSION['user']['tenant_id']);
$hourtypes = DB::selectPairs('select `id`,`name` from `hourtypes` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;

	if (!isset($data['hours']['project_id']) || !$data['hours']['project_id']) $data['hours']['project_id']=NULL;
	if (!isset($data['hours']['comment']) || $data['hours']['comment']) $data['hours']['comment']=NULL;
	if (!isset($data['hours']['type']) || !$data['hours']['type']) $data['hours']['type']=NULL;
	if (!isset($data['hours']['date']) || !$data['hours']['date']) $errors['hours[date]']='Date not set';	
	if (!isset($data['hours']['customer_id']) || !$data['hours']['customer_id']) $errors['hours[customer_id]']='Customer not set';	
	
	//set vat_percentage to NULL if the customer has vat_reverse_charge
	$vat_reverse_charge = DB::selectValue('select `vat_reverse_charge` from `customers` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $data['hours']['customer_id']);
	if ($vat_reverse_charge) $data['hours']['vat_percentage']=NULL;
	
	if (!isset($errors)) {
		try {
			$subtotal = $data['hours']['hours_worked']*$data['hours']['hourly_fee'];
			if($data['hours']['vat_percentage']) $total = $subtotal*((100+$data['hours']['vat_percentage'])/100); 
			else $total = $subtotal;

			$invoiceline_id = DB::insert('INSERT INTO `invoicelines` (
				`tenant_id`, 
				`customer_id`, 
				`name`, 
				`subtotal`, 
				`vat`,
				`vat_percentage`,
				`total`
			) VALUES (?, ?, ?, ?, ?, ?, ?)', 
				$_SESSION['user']['tenant_id'], 
				$data['hours']['customer_id'], 
				$data['hours']['name'], 
				$subtotal, 
				($total - $subtotal),
				$data['hours']['vat_percentage'],
				$total
			);

			$hour_id = DB::insert('INSERT INTO `hours` (
				`tenant_id`, 
				`customer_id`, 
				`project_id`, 
				`date`, 
				`name`,
				`hours_worked`, 
				`hourly_fee`,
				`subtotal`, 
				`vat_percentage`,
				`type`, 
				`comment`,
				`invoiceline_id`
			) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
				$_SESSION['user']['tenant_id'],
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
				$invoiceline_id
			);

			if ($invoiceline_id && $hour_id) {
				Flash::set('success','Hours saved');
				Router::redirect('hours/index');
			}
			$error = 'Hours not saved';
		} catch (DBError $e) {
			$error = 'Hours not saved: '.$e->getMessage();
		}
	}	
} else {
	$data = array('hours'=>array(
		'customer_id'=>NULL, 
		'project_id'=>NULL, 
		'date'=>Date("Y-m-d"), 
		'name'=>NULL, 
		'hours_worked'=>NULL, 
		'hourly_fee'=>$tenant['tenants']['default_hourly_fee'], 
		'subtotal'=>NULL, 
		'vat_percentage'=>$tenant['tenants']['default_vat_percentage'], 
		'type'=>NULL, 
		'comment'=>NULL,
		'invoiceline_id'=>NULL));
}