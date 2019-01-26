<?php 
if (!empty($_POST)) {
	try {
		$rows = DB::delete('DELETE FROM `products` WHERE `tenant_id` = ? AND `id` = ?', $_SESSION['user']['tenant_id'], $id);
		if ($rows) {
			Flash::set('success','Product deleted');
			Router::redirect('products/index');
		}
		$error = 'Product not deleted';
	} catch (DBError $e) {
		$error = 'Product not saved: '.$e->getMessage();
	}
}