<?php
session_start();
require_once '../controleur/ListerToutesLesRencontres.php';
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
$title = 'Dashboard ' . $payload['username'];
?>


<div class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50">
	<h2>Bienvenue <?php echo $payload['username']; ?></h2>
  <?php
  $rencontres = new ToutesLesRencontres();
  $rencontres = $rencontres->execute();
  echo "<div class='flex flex-row items-center gap-4 flex-wrap'>";
  Components::Button([
    'label' => 'Gérer les rencontre',
    'variant' => 'primary',
    'href' => 'rencontres.php'
  ]);
  Components::Button([
    'label' => 'Gérer un joueur',
    'variant' => 'primary'
  ]);
  echo "</div>";
  if(count($rencontres['next']) > 0) {
    echo "<h2>Rencontres à venir</h2>";
    echo "<div class='grid grid-cols-1 lg:grid-cols-2 gap-4'>";
    foreach ($rencontres['next'] as $rencontre) {
      echo "
      <a href='rencontre.php?id=".$rencontre->getId()."' class='bg-neutral-50 transition-colors hover:bg-neutral-100 p-4 rounded-lg border border-neutral-300/50'>
        <div class='flex flex-row justify-between items-center'>
          <h4 class='text-2xl font-semibold'>".$rencontre->getEquipeAdverse()."</h4>
          <time class='text-base text-neutral-600 font-base'>".$rencontre->getDateHeure()."</time>
        </div>
        <p class='text-neutral-600 text-lg font-semibold'>".$rencontre->getLieu()."</p>
      </a>";
    }
    echo "</div>";
  }


  if(count($rencontres['previous']) > 0) {
    echo "<h2>Rencontres passées</h2>";
    echo "<div class='grid grid-cols-1 lg:grid-cols-2 gap-4'>";
    foreach ($rencontres['previous'] as $rencontre) {
      $pillBg = $rencontre->getResultat() === 'Nul' ? 'bg-neutral-600' : ($rencontre->getResultat() == 'Victoire' ? 'bg-green-600' : 'bg-red-600');
      $pill = $rencontre->getResultat()  ? "<div class='px-2 py-1 text-base rounded-full text-neutral-100 ".$pillBg."'>".$rencontre->getResultat()."</div>" : '';
      echo "
      <a href='rencontre.php?id=".$rencontre->getId()."' class='bg-neutral-50 transition-colors hover:bg-neutral-100 p-4 rounded-lg border border-neutral-300/50'>
        <div class='flex flex-row justify-between items-center'>
          <h4 class='text-2xl font-semibold'>".$rencontre->getEquipeAdverse()."</h4>
          <time class='text-base text-neutral-600 font-base'>".$rencontre->getDateHeure()."</time>
        </div>
        <div class='flex flex-row items-end justify-between'>
          <p class='text-neutral-600 text-lg font-semibold'>".$rencontre->getLieu()."</p>
          $pill
        </div>
      </a>";
    }
    echo "</div>";
  }
  ?>
</div>

<?php
$content = ob_get_clean();
require_once './layout.php';


?>
