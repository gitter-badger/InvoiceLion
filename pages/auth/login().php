<?php
if (isset($_POST['username'])) {
  $error = "Username/password not valid";
  if (Auth::login($_POST['username'],$_POST['password'])) {
    Router::redirect("subscriptions");
  }
}