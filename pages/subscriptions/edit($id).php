<?php 
$subscriptiontypes = DB::selectPairs('select `id`,`name` from `subscriptiontypes` WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
$projects = DB::select('select `id`,`name`,`customer_id` from `projects` WHERE `tenant_id` = ? and `active` ORDER BY name', $_SESSION['user']['tenant_id']);
$customers = DB::selectPairs('select `id`,`name` from `customers`  WHERE `tenant_id` = ?', $_SESSION['user']['tenant_id']);
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($data['subscriptions']['subscriptiontype_id']) || !$data['subscriptions']['subscriptiontype_id']) $data['subscriptions']['subscriptiontype_id'] = NULL;
	if (!isset($customers[$data['subscriptions']['customer_id']])) $errors['subscriptions[customer_id]']='Customer not found';
	if (!isset($customers[$data['subscriptions']['project_id']])) $data['subscriptions']['project_id'] = NULL;
	if (!isset($data['subscriptions']['canceled']) || $data['subscriptions']['canceled']=='0000-00-00') $data['subscriptions']['canceled'] = NULL;
	if (!isset($errors)) {
		try {
			$rowsAffected = DB::update('UPDATE `subscriptions` SET 
				`fee`=?, 
				`vat_percentage`=?,
				`months`=?, 
				`name`=?, 
				`from`=?, 
				`canceled`=?, 
				`comment`=?, 
				`subscriptiontype_id`=?, 
				`customer_id`=?, 
				`project_id`=? 
			WHERE `tenant_id` = ? AND `id` = ?', 
				$data['subscriptions']['fee'], 
				$data['subscriptions']['vat_percentage'], 
				$data['subscriptions']['months'], 
				$data['subscriptions']['name'], 
				$data['subscriptions']['from'], 
				$data['subscriptions']['canceled'], 
				$data['subscriptions']['comment'], 
				$data['subscriptions']['subscriptiontype_id'], 
				$data['subscriptions']['customer_id'], 
				$data['subscriptions']['project_id'], 
			$_SESSION['user']['tenant_id'], $id);
			if ($rowsAffected!==false) {
				Flash::set('success','Subscription saved');
				Router::redirect('subscriptions/view/'.$id);
			}
			$error = 'Subscription not saved';
		} catch (DBError $e) {
			$error = 'Subscription not saved: '.$e->getMessage();
		}
	}
} else {
	$data = DB::selectOne('SELECT * from `subscriptions` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
}