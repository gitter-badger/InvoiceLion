<?php
$parts = explode('.', $token);
$claims = isset($parts[1]) ? json_decode(base64_decode($parts[1]), true) : false;
$username = isset($claims['user']) ? $claims['user'] : false;

if (isset($_POST['token'])) {
  $token = $_POST['token'];
  $password = $_POST['password'];
  $password2 = $_POST['password2'];
  if (!$password) {
    $error = "Password cannot be empty";
  } elseif ($password!=$password2) {
    $error = "Passwords must match"; 
  } elseif (!NoPassAuth::login($token)) {
    $error = "Token is not valid";
  } elseif ($username) {
    Auth::update($username, $password);
    Router::redirect("subscriptions");
  }
}