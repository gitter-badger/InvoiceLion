<?php 
$subscriptiontypes = DB::selectPairs('select `id`,`name` from `subscriptiontypes` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$projects = DB::select('select `id`,`name`,`customer_id` from `projects` WHERE `tenant_id` = ? and `active` ORDER BY name', $_SESSION['user']['tenant_id']);
$customers = DB::selectPairs('select `id`,`name` from `customers`  WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($subscriptiontypes[$data['subscriptions']['subscriptiontype_id']])) $errors['subscriptions[subscription_id]']='Abonnementtype not found';
	if (!isset($customers[$data['subscriptions']['customer_id']])) $errors['subscriptions[customer_id]']='Customer not found';
	if (!isset($errors)) {
		try {
			$id = DB::insert('INSERT INTO `subscriptions` (
				`tenant_id`, 
				`fee`, 
				`vat_percentage`, 
				`months`, 
				`name`, 
				`from`, 
				`canceled`, 
				`comment`, 
				`subscriptiontype_id`, 
				`customer_id`, 
				`project_id`
			) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
				$_SESSION['user']['tenant_id'], 
				$data['subscriptions']['fee'], 
				$data['subscriptions']['vat_percentage'], 
				$data['subscriptions']['months'], 
				$data['subscriptions']['name'], 
				$data['subscriptions']['from'], 
				$data['subscriptions']['canceled'], 
				$data['subscriptions']['comment'], 
				$data['subscriptions']['subscriptiontype_id'], 
				$data['subscriptions']['customer_id'], 
				$data['subscriptions']['project_id']
			);
			if ($id) {
				Flash::set('success','Subscription saved');
				Router::redirect('subscriptions/index');
			}
			$error = 'Subscription not saved';
		} catch (DBError $e) {
			$error = 'Subscription not saved: '.$e->getMessage();
		}
	}	
} else {
	$data = array('subscriptions'=>array(
		'fee'=>NULL, 
		'vat_percentage'=>$tenant['tenants']['default_vat_percentage'], 
		'months'=>NULL, 
		'name'=>NULL, 
		'from'=>NULL, 
		'canceled'=>NULL, 
		'comment'=>NULL, 
		'subscriptiontype_id'=>NULL, 
		'customer_id'=>NULL, 
		'project_id'=>NULL
	));
}