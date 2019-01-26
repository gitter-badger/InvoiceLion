<?php
$data = DB::selectOne('select * from `subscriptionperiods` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
$abonnement = DB::selectOne('select * from `subscriptions` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'],$data['subscriptionperiods']['subscription_id']);

$period = 12/$abonnement['subscriptions']['months'];
$modificator = ' +'.$period.' months';
$renewalDate = date('Y-m-d', strtotime($data['subscriptionperiods']['from'] . $modificator));

$insertid = DB::insert('INSERT INTO `subscriptionperiods` (`tenant_id`, `from`, `name`, `invoice_id`, `subscription_id`, `comment`) VALUES (?, ?, ?, ?, ?, ?)', $_SESSION['user']['tenant_id'], $renewalDate, $data['subscriptionperiods']['name'], NULL, $data['subscriptionperiods']['subscription_id'], NULL);
if ($insertid) {
	Flash::set('success','Abonnementperiode saved');
	Router::redirect('subscriptionperiods/index');
}