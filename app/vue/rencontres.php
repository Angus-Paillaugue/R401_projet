<?php
session_start();
require_once '../../lib/components.php';
require_once '../../lib/jwt.php';
require_once '../../lib/cookies.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

$jwt = Cookies::getCookie('token');
$payload = null;

if ($jwt) {
  $payload = JWT::validateJWT($jwt);
  if (!$payload) {
    header('Location: log-in.php', true, 303);
  }
} else {
  header('Location: log-in.php', true, 303);
}
$title = 'Rencontres';
?>

<?php
$content = ob_get_clean();
require_once './layout.php';


?>
