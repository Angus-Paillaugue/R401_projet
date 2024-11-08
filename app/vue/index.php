<?php
require_once '../../lib/jwt.php';
require_once '../../lib/cookies.php';

$jwt = Cookies::getCookie('token');

if ($jwt) {
  $payload = JWT::validateJWT($jwt);
  if ($payload) {
    header('Location: dashboard.php', true, 303);
  }else {
    header('Location: log-in.php', true, 303);
  }
} else {
  header('Location: log-in.php', true, 303);
}
?>
