<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($errors)) {
		try {
			$rowsAffected = DB::update('UPDATE `tenants` SET `subscriptions_active`=?, `hours_active`=?, `name`=?, `address`=?, `email`=?, `invoice_email`=?, `phone`=?, `bank_account_number`=?, `bank_account_name`=?, `bank_name`=?, `bank_bic`=?, `bank_city`=?, `coc_number`=?, `vat_number`=?, `payment_period`=?, `reminder_period`=?, `invoice_styles`=?, `invoice_template`=?, `invoice_page_number`=? WHERE `id` = ?', $data['tenants']['subscriptions_active'], $data['tenants']['hours_active'], $data['tenants']['name'], $data['tenants']['address'], $data['tenants']['email'], $data['tenants']['invoice_email'], $data['tenants']['phone'], $data['tenants']['bank_account_number'], $data['tenants']['bank_account_name'], $data['tenants']['bank_name'], $data['tenants']['bank_bic'], $data['tenants']['bank_city'], $data['tenants']['coc_number'], $data['tenants']['vat_number'], $data['tenants']['payment_period'], $data['tenants']['reminder_period'], $data['tenants']['invoice_styles'], $data['tenants']['invoice_template'], $data['tenants']['invoice_page_number'], $_SESSION['user']['tenant_id']);
			if ($rowsAffected!==false) {
				Flash::set('success','Tenant saved');
				Router::redirect('tenants/view');
			}
			$error = 'Tenant not saved';
		} catch (DBError $e) {
			$error = 'Tenant not saved: '.$e->getMessage();
		}
	}
} else {
	$data = DB::selectOne('SELECT * from `tenants` WHERE `id` = ?', $_SESSION['user']['tenant_id']);
	$data['tenants']['timetracking']=1;
}