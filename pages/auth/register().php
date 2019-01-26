<?php
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    if (!$username) {
        $error = "Username cannot be empty";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error = "Username is not a valid email address";
    } elseif (Auth::exists($username)) {
        $error = "Username is already taken";
    } else {
        $error = "User can not be registered";
        $userId = Auth::register($username, $password);
        if ($userId) {
            $tenantId = DB::insert('INSERT INTO `tenants` (`name`, `email`, `invoice_email`) VALUES (?, ?, ?)', $username, $username, $username); // does not need tenant_id check
            if ($tenantId) {
                $rowsAffected = DB::update('UPDATE `users` SET `tenant_id`=? WHERE `id` = ?', $tenantId, $userId);
                if ($rowsAffected) {
                    if (!Cache::get('AuthForgotten_mailto_' . $username)) {
                        Cache::set('AuthForgotten_mailto_' . $username, '1', NoPassAuth::$tokenValidity);
                        mail($username, 'Verify email ' . Router::getBaseUrl(), 'Click here: ' . Router::getBaseUrl() . "auth/reset/$token");
                    }
                    Router::redirect("auth/sent");
                }
            }
        }
    }
}
