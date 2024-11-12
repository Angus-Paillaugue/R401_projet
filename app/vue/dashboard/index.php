<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/formatters.php';
require_once __DIR__ . '/../../controleur/ListerToutesLesRencontres.php';
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
	<h1>Bienvenue <?php echo $payload['username']; ?></h1>
  <?php
  $rencontres = (new ToutesLesRencontres(3))->execute();
  echo "<div class='flex flex-row items-center gap-4 flex-wrap'>";
  Components::Button([
    'label' => 'Ajouter une rencontre',
    'variant' => 'primary',
    'href' => '/dashboard/add-rencontre.php',
    'icon' => 'plus',
  ]);
  Components::Button([
    'label' => 'Ajouter un joueur',
    'variant' => 'primary',
    'href' => '/dashboard/add-joueur.php',
    'icon' => 'plus',
  ]);
  echo '</div>';
  if (count($rencontres['next']) > 0) {
    echo '<h2>Rencontres à venir</h2>';
    echo "<div class='grid grid-cols-1 lg:grid-cols-2 gap-4'>";
    foreach ($rencontres['next'] as $rencontre) {
      echo "
      <a href='/dashboard/rencontre.php?id=" .
        $rencontre->getId() .
        "' class='bg-neutral-50 transition-colors hover:bg-neutral-100 p-4 rounded-lg border border-neutral-300/50'>
        <div class='flex flex-row justify-between items-center'>
          <h4 class='text-2xl font-semibold'>" .
        $rencontre->getEquipeAdverse() .
        "</h4>
          <time class='text-base text-neutral-600 font-base'>" .
        Formatters::formatDateTime($rencontre->getDateHeure()) .
        "</time>
        </div>
        <p class='text-neutral-600 text-lg font-semibold'>" .
        $rencontre->getLieu() .
        "</p>
      </a>";
    }
    Components::Button([
      'label' => 'Voir plus',
      'variant' => 'primary',
      'href' => '/dashboard/rencontres.php?next',
      'icon' => 'plus',
    ]);
    echo '</div>';
  }

  if (count($rencontres['previous']) > 0) {
    echo '<h2>Rencontres passées</h2>';
    echo "<div class='grid grid-cols-1 lg:grid-cols-2 gap-4'>";
    foreach ($rencontres['previous'] as $rencontre) {
      $pillBg =
        $rencontre->getResultat() === 'Nul'
          ? 'bg-neutral-600'
          : ($rencontre->getResultat() == 'Victoire'
            ? 'bg-green-600'
            : 'bg-red-600');
      $pill = $rencontre->getResultat()
        ? "<div class='px-2 py-1 text-base rounded-full text-neutral-100 " .
          $pillBg .
          "'>" .
          $rencontre->getResultat() .
          '</div>'
        : '';
      echo "
      <a href='/dashboard/rencontre.php?id=" .
        $rencontre->getId() .
        "' class='bg-neutral-50 transition-colors hover:bg-neutral-100 p-4 rounded-lg border border-neutral-300/50'>
        <div class='flex flex-row justify-between items-center'>
          <h4 class='text-2xl font-semibold'>" .
        $rencontre->getEquipeAdverse() .
        "</h4>
          <time class='text-base text-neutral-600 font-base'>" .
        Formatters::formatDateTime($rencontre->getDateHeure()) .
        "</time>
        </div>
        <div class='flex flex-row items-end justify-between'>
          <p class='text-neutral-600 text-lg font-semibold'>" .
        $rencontre->getLieu() .
        "</p>
          $pill
        </div>
      </a>";
    }
    Components::Button([
      'label' => 'Voir plus',
      'variant' => 'primary',
      'href' => '/dashboard/rencontres.php?previous',
      'icon' => 'plus',
    ]);
    echo '</div>';
  }
  ?>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
