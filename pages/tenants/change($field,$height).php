<?php
if (!in_array($field,array('logo_image','signature_image'))) {
    Router::redirect('subscriptions');
}
$height += 0;

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $data = $_FILES;
    if ($_FILES['image']['error'] != UPLOAD_ERR_OK) $errors['image']='Image change failed';
    if (!is_uploaded_file($_FILES['image']['tmp_name'])) $errors['image']='Image change invalid';
    if (!isset($errors)) {
        try {
            $rowsAffected = DB::update('UPDATE `tenants` SET '.$field.'=? WHERE `id` = ?', file_get_contents($_FILES['image']['tmp_name']), $_SESSION['user']['tenant_id']);
            if ($rowsAffected!==false) {
                Flash::set('success','Image saved');
                Router::redirect("tenants/change/$field/$height");
            }
            $error = 'Image not saved';
        } catch (DBError $e) {
			$error = 'Image not saved: '.$e->getMessage();
		}
	}
} else {
	$data = DB::selectOne('SELECT '.$field.' from `tenants` WHERE `id` = ?', $_SESSION['user']['tenant_id']);
}