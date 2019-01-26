<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {
	$data = $_POST;
	if (!isset($errors)) {
		try {
			$id = DB::insert('INSERT INTO `products` (`tenant_id`, `name`) VALUES (?, ?)', $_SESSION['user']['tenant_id'], $data['products']['name']);
			if ($id) {
				Flash::set('success','Product saved');
				Router::redirect('products/index');
			}
			$error = 'Product not saved';
		} catch (DBError $e) {
			$error = 'Product not saved: '.$e->getMessage();
		}
	}
	
} else {
	$data = array('products'=>array('name'=>NULL));
}