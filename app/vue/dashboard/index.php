<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/formatters.php';
require_once __DIR__ . '/../../lib/error.php';
require_once __DIR__ . '/../../controleur/ListerToutesLesRencontres.php';
require_once __DIR__ . '/../../controleur/ListerTousLesJoueurs.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

// Cookie exists and is valid because it's been already validated in the ../layout.php
$jwt = Cookies::getCookie('token');
$token = $_COOKIE['token'];
$payload = JWT::validateJWT($token);
$title = 'Dashboard ' . $payload['username'];

try {
  $rencontres = (new ToutesLesRencontres(4))->execute();
  $joueurs = (new ListerTousLesJoueurs())->execute();
} catch (Exception $e) {
  ErrorHandling::setFatalError($e->getMessage());
}
?>


<div class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-6 border-neutral-900">
	<h1>Bienvenue <?php echo $payload['username']; ?></h1>
  <?php
  echo "<div class='grid grid-cols-2 gap-8'><div class='flex flex-row items-start justify-start gap-4 flex-wrap'>";
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
  Components::Button([
    'label' => 'Statistiques',
    'variant' => 'primary',
    'href' => '/dashboard/statistiques.php',
    'icon' => 'chart',
  ]);
  echo '</div>';

  // Liste des joueurs
  echo "<div><h2>Joueurs</h2><div class='!m-0 max-h-[300px] overflow-y-auto rounded-lg'><table class='w-full table-auto text-sm'><thead class='sticky top-0 bg-neutral-900'><tr><td scope='col' class='sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-400'>Nom</td><td scope='col' class='sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-400'>Prénom</td></tr></thead><tbody>";
  foreach ($joueurs as $joueur) {
    echo "<tr class='even:bg-neutral-900'><td class='px-4 py-1 align-middle'><a class='hover:underline' href='/dashboard/joueur.php?id=" .
      $joueur->getId() .
      "'>" .
      $joueur->getNom() .
      "</a></td><td class='px-4 py-1 align-middle'>" .
      $joueur->getPrenom() .
      '</td></tr>';
  }
  echo '</tbody></table></div></div></div>';

  // Rencontres à venir
  if (count($rencontres['next']) > 0) {
    echo "<div class='flex flex-row items-center justify-between'><h2>Rencontres à venir</h2>";
    Components::Button([
      'label' => 'Voir tout',
      'variant' => 'primary',
      'href' => '/dashboard/rencontres.php?next',
      'icon' => 'plus',
    ]);
    echo "</div><div class='grid grid-cols-1 lg:grid-cols-2 gap-4'>";
    foreach ($rencontres['next'] as $rencontre) {
      echo "
      <a href='/dashboard/rencontre.php?id=" .
        $rencontre->getId() .
        "' class='bg-neutral-900 hover:bg-neutral-900 p-4 rounded-lg border border-neutral-900'>
        <div class='flex flex-row justify-between items-center'>
          <h4 class='text-2xl font-semibold'>" .
        $rencontre->getEquipeAdverse() .
        "</h4>
          <time class='text-base text-neutral-400 font-base'>" .
        Formatters::formatDateTime($rencontre->getDateHeure()) .
        "</time>
        </div>
        <p class='text-neutral-400 text-lg font-semibold'>" .
        $rencontre->getLieu() .
        "</p>
      </a>";
    }
    echo '</div>';
  }

  // Rencontres passées
  if (count($rencontres['previous']) > 0) {
    echo "<div class='flex flex-row items-center justify-between'><h2>Rencontres passées</h2>";
    Components::Button([
      'label' => 'Voir tout',
      'variant' => 'primary',
      'href' => '/dashboard/rencontres.php?previous',
      'icon' => 'plus',
    ]);
    echo "</div><div class='grid grid-cols-1 lg:grid-cols-2 gap-4'>";
    foreach ($rencontres['previous'] as $rencontre) {
      $pillBg =
        $rencontre->getResultat() === 'Nul'
          ? 'bg-neutral-400'
          : ($rencontre->getResultat() == 'Victoire'
            ? 'bg-green-600'
            : 'bg-red-600');
      $pill = $rencontre->getResultat()
        ? "<div class='px-2 py-1 text-base rounded-full text-neutral-100 font-semibold " .
          $pillBg .
          "'>" .
          $rencontre->getResultat() .
          '</div>'
        : '';
      echo "
      <a href='/dashboard/rencontre.php?id=" .
        $rencontre->getId() .
        "' class='bg-neutral-900 transition-colors hover:bg-neutral-900 p-4 rounded-lg border border-neutral-900'>
        <div class='flex flex-row justify-between items-center'>
          <h4 class='text-2xl font-semibold'>" .
        $rencontre->getEquipeAdverse() .
        "</h4>
          <time class='text-base text-neutral-400 font-base'>" .
        Formatters::formatDateTime($rencontre->getDateHeure()) .
        "</time>
        </div>
        <div class='flex flex-row items-end justify-between'>
          <p class='text-neutral-400 text-lg font-semibold'>" .
        $rencontre->getLieu() .
        "</p>
          $pill
        </div>
      </a>";
    }
    echo '</div>';
  }
  ?>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
