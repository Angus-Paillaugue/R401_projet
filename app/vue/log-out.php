<?php
require_once __DIR__ . '/../lib/cookies.php';
Cookies::deleteCookie('token');
header('Location: log-in.php', true, 303);
?>
