<?php 
$customers = DB::selectPairs('select `id`,`name` from `customers` WHERE `tenant_id` = ? order by name', $_SESSION['user']['tenant_id']);
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($customers[$data['projects']['customer_id']])) $errors['projects[customer_id]']='Customer not found';
	if (!isset($errors)) {
		try {
			$rowsAffected = DB::update('UPDATE `projects` SET `name`=?, `customer_id`=?, `active`=? WHERE `tenant_id` = ? AND `id` = ?', $data['projects']['name'], $data['projects']['customer_id'], $data['projects']['active'], $_SESSION['user']['tenant_id'], $id);
			if ($rowsAffected!==false) {
				Flash::set('success','Project saved');
				Router::redirect('projects/view/'.$id);
			}
			$error = 'Project not saved';
		} catch (DBError $e) {
			$error = 'Project not saved: '.$e->getMessage();
		}
	}
} else {
	$data = DB::selectOne('SELECT * from `projects` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
}