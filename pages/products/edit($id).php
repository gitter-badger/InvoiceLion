<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($errors)) {
		try {
			$rowsAffected = DB::update('UPDATE `products` SET `name`=? WHERE `tenant_id` = ? AND `id` = ?', $data['products']['name'], $_SESSION['user']['tenant_id'], $id);
			if ($rowsAffected!==false) {
				Flash::set('success','Product saved');
				Router::redirect('products/view/'.$id);
			}
			$error = 'Product not saved';
		} catch (DBError $e) {
			$error = 'Product not saved: '.$e->getMessage();
		}
	}
} else {
	$data = DB::selectOne('SELECT * from `products` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
}