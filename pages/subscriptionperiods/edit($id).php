<?php 
$subscriptions = DB::selectPairs('select `id`,`name` from `subscriptions` WHERE `tenant_id` = ? order by name', $_SESSION['user']['tenant_id']);
$invoices = DB::selectPairs('select `id`,`name` from `invoices` WHERE `tenant_id` = ? order by number desc', $_SESSION['user']['tenant_id']);

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	$subscription = DB::selectOne('select * from `subscriptions` WHERE `tenant_id` = ? and `id` = ?', $_SESSION['user']['tenant_id'], $data['subscriptionperiods']['subscription_id']);
	if (!isset($invoices[$data['subscriptionperiods']['invoice_id']])) $data['subscriptionperiods']['invoice_id']=NULL;
	if (!isset($subscription)) $errors['subscriptionperiods[subscription_id]']='subscriptions not found';
	if (!isset($errors)) {
		try {
			$rowsAffected = DB::update('UPDATE `subscriptionperiods` SET `from`=?, `name`=?, `invoice_id`=?, `subscription_id`=?, `comment`=? WHERE `tenant_id` = ? AND `id` = ?', $data['subscriptionperiods']['from'], $data['subscriptionperiods']['name'], $data['subscriptionperiods']['invoice_id'], $data['subscriptionperiods']['subscription_id'], $data['subscriptionperiods']['comment'], $_SESSION['user']['tenant_id'], $id);

			$optional = DB::update('UPDATE `invoicelines` SET `project_id`=?, `name`=?, `subtotal`=?, `invoice_id`=? WHERE `tenant_id` = ? AND `subscriptionperiod_id` = ?', $subscription['subscriptions']['project_id'], $data['subscriptionperiods']['name'], $subscription['subscriptions']['fee'], $data['subscriptionperiods']['invoice_id'], $_SESSION['user']['tenant_id'], $id);

			if ($rowsAffected!==false) {
				Flash::set('success','Subscription period saved');
				Router::redirect('subscriptionperiods/view/'.$id);
			}
			$error = 'Subscription period not saved';
		} catch (DBError $e) {
			$error = 'Subscription period not saved: '.$e->getMessage();
		}
	}
} else {
	$data = DB::selectOne('SELECT * from `subscriptionperiods` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
}