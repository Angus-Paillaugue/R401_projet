<?php
session_start();
require_once '../../lib/components.php';
require_once '../../lib/jwt.php';
require_once '../../lib/cookies.php';
require_once '../controleur/RecupererUneRencontre.php';
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
$title = 'Rencontre';

if(!isset($_GET['id'])) {
  throw new Exception('ID de la rencontre non fourni');
}
$rencontre = new RecupererUneRequete($_GET['id']);
$rencontre = $rencontre->execute();
var_dump($rencontre);
?>

<?php
$content = ob_get_clean();
require_once './layout.php';


?>
