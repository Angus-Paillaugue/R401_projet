<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/formatters.php';
require_once __DIR__ . '/../../lib/error.php';
require_once __DIR__ . '/../../controleur/ListerToutesLesRencontres.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

$title = 'Rencontres';

try {
  $rencontres = (new ToutesLesRencontres(null))->execute();
} catch (Exception $e) {
  ErrorHandling::setFatalError($e->getMessage());
}

$next = isset($_GET['next']) ?? false;
$previous = isset($_GET['previous']) ?? false;

function displayRencontres($r)
{
  echo "<div class='grid grid-cols-1 lg:grid-cols-2 gap-4'>";
  foreach ($r as $rencontre) {
    echo "
    <a href='/vue/dashboard/rencontre.php?id=" .
      $rencontre->getId() .
      "' class='bg-neutral-900 transition-colors hover:bg-neutral-900 p-4 rounded-lg border border-neutral-300/50 dark:border-neutral-900'>
      <div class='flex flex-row justify-between items-center'>
        <h4 class='text-2xl font-semibold'>" .
      $rencontre->getEquipeAdverse() .
      "</h4>
        <time class='text-base text-neutral-600 dark:text-neutral-400 font-base'>" .
      Formatters::formatDateTime($rencontre->getDateHeure()) .
      "</time>
      </div>
      <p class='text-neutral-600 dark:text-neutral-400 text-lg font-semibold'>" .
      $rencontre->getLieu() .
      "</p>
    </a>";
  }
  echo '</div>';
}
echo "<div class='max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900'>";
echo "<div class='flex flex-row items-center justify-between'>";
echo '<h2>Toutes les rencontres</h2>';
echo "<div class='flex flex-row gap-2 items-center'>";
Components::Link([
  'href' => '/vue/dashboard/rencontres.php?previous',
  'label' => 'Précédentes',
  'disabled' => $previous,
]);
Components::Link([
  'href' => '/vue/dashboard/rencontres.php',
  'label' => 'Toutes',
  'disabled' => !$next && !$previous,
]);
Components::Link([
  'href' => '/vue/dashboard/rencontres.php?next',
  'label' => 'Prochaines',
  'disabled' => $next,
]);
echo '</div>';
echo '</div>';
if ($next) {
  displayRencontres($rencontres['next']);
} elseif ($previous) {
  displayRencontres($rencontres['previous']);
} else {
  displayRencontres($rencontres['next']);
  displayRencontres($rencontres['previous']);
}

echo '</div></div>';
?>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
