<?php
if (empty($_SESSION['user']['tenant_id'])) {
    if (explode('/',Router::getView()?:'/')[1]!='auth') {
        Router::redirect('auth/login');
    }
}
