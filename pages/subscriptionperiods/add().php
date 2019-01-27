<?php 
$subscriptions = DB::selectPairs('select `id`,`name` from `subscriptions` WHERE `tenant_id` = ? order by name', $_SESSION['user']['tenant_id']);
$invoices = DB::selectPairs('select `id`,`name` from `invoices` WHERE not `sent` AND `tenant_id` = ? order by number desc', $_SESSION['user']['tenant_id']);
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	$subscription = DB::selectOne('select * from `subscriptions` WHERE `tenant_id` = ? and `id` = ?', $_SESSION['user']['tenant_id'], $data['subscriptionperiods']['subscription_id']);
	if (!isset($subscriptions[$data['subscriptionperiods']['subscription_id']])) $errors['subscriptionperiods[subscription_id]']='subscriptions not found';
	if (!isset($data['subscriptionperiods']['comment'])) $data['subscriptionperiods']['comment']=NULL;
	if (!isset($invoices[$data['subscriptionperiods']['invoice_id']])) $data['subscriptionperiods']['invoice_id']=NULL;
	if (!isset($errors)) {
		try {
			$subscriptionperiod_id = DB::insert('INSERT INTO `subscriptionperiods` (`tenant_id`, `from`, `name`, `invoice_id`, `subscription_id`, `comment`) VALUES (?, ?, ?, ?, ?, ?)', $_SESSION['user']['tenant_id'], $data['subscriptionperiods']['from'], $data['subscriptionperiods']['name'], $data['subscriptionperiods']['invoice_id'], $data['subscriptionperiods']['subscription_id'], $data['subscriptionperiods']['comment']);
			
			$invoicelines_id = DB::insert('INSERT INTO `invoicelines` (`tenant_id`, `project_id`, `name`, `subtotal`, `hour_id`, `invoice_id`) VALUES (?, ?, ?, ?, ?, ?)', $_SESSION['user']['tenant_id'], $subscription['subscriptions']['project_id'], $data['subscriptionperiods']['name'], $subscription['subscriptions']['fee'], $subscriptionperiod_id, $data['subscriptionperiods']['invoice_id']);
			
			if ($subscriptionperiod_id) {
				Flash::set('success','Subscription period saved');
				Router::redirect('subscriptionperiods/index');
			}
			$error = 'Subscription period not saved';
		} catch (DBError $e) {
			$error = 'Subscription period not saved: '.$e->getMessage();
		}
	}
} else {
	$data = array('subscriptionperiods'=>array('from'=>NULL, 'name'=>NULL, 'invoice_id'=>NULL, 'subscription_id'=>NULL, 'comment'=>NULL));
}