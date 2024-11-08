<?php
session_start();
require_once '../controleur/ListerToutesLesRencontres.php';
require_once '../../lib/components.php';
require_once '../../lib/jwt.php';
require_once '../../lib/cookies.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = 'Dashboard entraîneur';
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
?>


<div class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border border-neutral-300/50">
	<h2>Bienvenue <?php echo $payload['username']; ?></h2>
  <?php
  $rencontres = new ToutesLesRencontres();
  $rencontres = $rencontres->execute();
  if(count($rencontres['next']) > 0) {
    echo "<h1>Rencontres à venir</h1>";
    echo "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4'>";
    foreach ($rencontres as $rencontre) {
      echo "<div class='bg-white p-4 rounded-lg border border-neutral-300/50'>
        <h2>$rencontre</h2>
      </div>";
    }
    echo "</div>";
  }


  if(count($rencontres['previous']) > 0) {
    echo "<h1>Rencontres passées</h1>";
    echo "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4'>";
    foreach ($rencontres as $rencontre) {
      echo "<div class='bg-white p-4 rounded-lg border border-neutral-300/50'>
        <h2>$rencontre</h2>
      </div>";
    }
    echo "</div>";
  }
  ?>
</div>

<?php
$content = ob_get_clean();
require_once './layout.php';


?>
