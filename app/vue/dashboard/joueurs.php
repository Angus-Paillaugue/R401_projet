<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/error.php';
require_once __DIR__ . '/../../controleur/ListerTousLesJoueurs.php';
require_once __DIR__ . '/../../lib/formatters.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

$title = 'Joueurs';

try {
  $joueurs = (new ListerTousLesJoueurs())->execute();
} catch (Exception $e) {
  ErrorHandling::setFatalError($e->getMessage());
}
?>

<div class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900">
  <h1>
    Joueurs
  </h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gris-cols-3">
    <?php foreach ($joueurs as $joueur) {
      echo "<a href='/vue/dashboard/joueur.php?id=" .
        $joueur->getId() .
        "' class='bg-neutral-100 dark:bg-neutral-900 border border-neutral-300/50 dark:border-neutral-900 p-4 rounded-xl'>";
      echo "<h4 class='text-2xl font-semibold'>{$joueur->getPrenom()} {$joueur->getNom()}</h4>";
      echo "<time class='text-base text-neutral-600 dark:text-neutral-400 font-base'>{$joueur->getDateNaissance()}</time>";
      echo '</a>';
    } ?>
  </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
