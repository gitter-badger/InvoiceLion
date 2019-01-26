<?php
if (isset($_POST['username'])) {
  $username = $_POST['username'];
  $token = NoPassAuth::token($username);
  $error = 'Not found';
  if ($token) {
    if (!Cache::get('AuthForgotten_mailto_'.$username)) {
      Cache::set('AuthForgotten_mailto_'.$username,'1',NoPassAuth::$tokenValidity);
      mail($username,'Login to '.Router::getBaseUrl(),'Click here: '.Router::getBaseUrl()."auth/reset/$token");
    }
    Router::redirect("auth/sent");
  }
}