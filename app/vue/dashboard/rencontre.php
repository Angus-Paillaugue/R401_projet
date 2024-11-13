<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/formatters.php';
require_once __DIR__ . '/../../lib/error.php';
require_once __DIR__ . '/../../controleur/RecupererUneRencontre.php';
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

if (!isset($_GET['id'])) {
  ErrorHandling::setFatalError('ID de la rencontre non fourni');
}

try {
  $rencontre = new RecupererUneRencontre($_GET['id']);
  $rencontre = $rencontre->execute();
  $isInPast = new DateTime($rencontre->getDateHeure()) < new DateTime();
} catch (Exception $e) {
  ErrorHandling::setFatalError($e->getMessage());
}
?>

<div class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-900">
  <div class="flex flex-row items-center justify-between">
    <h2>Rencontre</h2>
    <?php if ($isInPast) {
      Components::Button([
        'label' => 'Modifier',
        'href' => '/dashboard/edit-rencontre.php?id=' . $rencontre->getId(),
      ]);
    } ?>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="bg-neutral-900 p-4 rounded-xl border border-neutral-900">
      <h4 class="text-2xl font-semibold"><?php echo $rencontre->getEquipeAdverse(); ?></h4>
      <time class="text-base text-neutral-400 font-base"><?php echo Formatters::formatDateTime(
        $rencontre->getDateHeure()
      ); ?></time>
      <div class="text-neutral-400 text-lg font-semibold flex flex-row items-center gap-2">
        <?php
        echo Components::Icon([
          'icon' => $rencontre->getLieu(),
          'class' => 'size-4',
        ]);
        echo $rencontre->getLieu();
        ?>
      </div>
    </div>

            <?php if ($isInPast) {
              if ($rencontre->getFeuilleMatch()) {
                echo '<div class="bg-neutral-900 p-4 lg:col-span-2 rounded-xl border border-neutral-900">
        <h4 class="text-2xl font-semibold">Joueurs</h4>
        <div class="border border-neutral-900 block rounded-xl max-h-[500px] overflow-auto">
          <table class="w-full table-auto text-sm">
            <thead class="sticky top-0 bg-neutral-800">
              <tr>
                <th scope="col" class="sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-400">
                  Joueur
                </th>
                <th scope="col" class="sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-400">
                  Role
                </th>
              </tr>
            </thead>
            <tbody class="[&amp;_tr:last-child]:border-0">';
                // Si la date de la rencontre est dans le futur, on affiche les joueurs de l'équipe
                foreach ($rencontre->getFeuilleMatch() as $feuille_match) {
                  $joueur = $feuille_match->getJoueur();
                  echo "
            <tr class='transition-colors even:bg-neutral-800'>
              <td class='w-1/12 px-4 align-middle'>
                <a href='/dashboard/joueur.php?id=" .
                    $joueur->getId() .
                    "' class='text-base font-medium'>" .
                    $joueur->getNom() .
                    ' ' .
                    $joueur->getPrenom() .
                    "</a>
              </td>
              <td class='w-1/12 px-4 align-middle'>
                <div class='flex flex-row items-center gap-2'>
                  " .
                    $feuille_match->getRoleDebut() .
                    Components::Icon([
                      'icon' => 'arrowRight',
                      'class' => 'size-4',
                    ]) .
                    $feuille_match->getRoleFin() .
                    "
                </div>
              </td>
            </tr>";
                }
                echo '</tbody></table></div>';
              } else {
                // Il n'y a pas de feuille de match
                echo '<div class="bg-neutral-900 p-4 lg:col-span-2 space-y-2 rounded-xl border border-neutral-900">
                  <h4 class="text-2xl font-semibold">Feuille de match</h4>
                  <p class="text-lg text-neutral-400">Aucune feuille de match n\'a été renseignée pour cette rencontre.</p>';
                Components::Button([
                  'label' => 'Ajouter une feuille de match',
                  'href' =>
                    '/dashboard/edit-rencontre.php?id=' . $rencontre->getId(),
                ]);
                echo '</div>';
              }
            } ?>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
