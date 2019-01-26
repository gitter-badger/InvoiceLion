<?php 
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ? order by name', $_SESSION['user']['tenant_id']);
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($customers[$data['projects']['customer_id']])) $errors['projects[customer_id]']='Customer not found';
	if (!isset($errors)) {
		try {
			$id = DB::insert('INSERT INTO `projects` (`tenant_id`, `name`, `customer_id`, `active`) VALUES (?, ?, ?, ?)', $_SESSION['user']['tenant_id'], $data['projects']['name'], $data['projects']['customer_id'], $data['projects']['active']);
			if ($id) {
				Flash::set('success','Project saved');
				Router::redirect('projects/index');
			}
			$error = 'Project not saved';
		} catch (DBError $e) {
			$error = 'Project not saved: '.$e->getMessage();
		}
	}
} else {
	$data = array('projects'=>array('name'=>NULL, 'customer_id'=>NULL, 'active'=>NULL));
}