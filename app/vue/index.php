<?php
require_once dirname(__DIR__) . 'app/lib/jwt.php';
require_once dirname(__DIR__) . 'app/lib/cookies.php';

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
