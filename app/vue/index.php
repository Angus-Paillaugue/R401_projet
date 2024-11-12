<?php
require_once __DIR__ . '/../lib/jwt.php';
require_once __DIR__ . '/../lib/cookies.php';

$jwt = Cookies::getCookie('token');

if ($jwt) {
  $payload = JWT::validateJWT($jwt);
  if ($payload) {
    header('Location: /dashboard', true, 303);
  } else {
    header('Location: log-in.php', true, 303);
  }
} else {
  header('Location: log-in.php', true, 303);
}
?>
