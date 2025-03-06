<?php
require_once __DIR__ . '/../lib/cookies.php';

$jwt = Cookies::getCookie('token');

if ($jwt) {
  header('Location: /vue/dashboard', true, 303);
  exit();
} else {
  header('Location: /vue/log-in.php', true, 303);
  exit();
}
?>
