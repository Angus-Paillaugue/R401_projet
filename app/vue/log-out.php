<?php
require_once '../../lib/cookies.php';
Cookies::deleteCookie('token');
header('Location: log-in.php', true, 303);
?>
