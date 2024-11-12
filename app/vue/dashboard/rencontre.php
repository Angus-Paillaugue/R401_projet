<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/formatters.php';
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
  throw new Exception('ID de la rencontre non fourni');
}
$rencontre = new RecupererUneRencontre($_GET['id']);
$rencontre = $rencontre->execute();
$isInPast = new DateTime($rencontre->getDateHeure()) < new DateTime();
?>

<div class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50">
  <div class="flex flex-row items-center justify-between">
    <h2>Rencontre</h2>
    <?php if ($isInPast) {
      Components::Button([
        'label' => 'Modifier',
        'class' => 'bg-primary-500 hover:bg-primary-600 text-white',
        'href' => '/dashboard/edit-rencontre.php?id=' . $rencontre->getId(),
      ]);
    } ?>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="bg-neutral-50 p-4 rounded-xl border border-neutral-300/50">
      <h4 class="text-2xl font-semibold"><?php echo $rencontre->getEquipeAdverse(); ?></h4>
      <time class="text-base text-neutral-600 font-base"><?php echo Formatters::formatDateTime(
        $rencontre->getDateHeure()
      ); ?></time>
      <div class="text-neutral-600 text-lg font-semibold flex flex-row items-center gap-2">
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
                echo '<div class="bg-neutral-50 p-4 lg:col-span-2 rounded-xl border border-neutral-300/50">
        <h4 class="text-2xl font-semibold">Joueurs</h4>
        <div class="border border-neutral-300/50 block rounded-xl max-h-[500px] overflow-auto">
          <table class="w-full table-auto text-sm">
            <thead class="border-b sticky top-0 border-neutral-300/50 bg-white">
              <tr>
                <th scope="col" class="sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600">
                  Joueur
                </th>
                <th scope="col" class="sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600">
                  Role
                </th>
              </tr>
            </thead>
            <tbody class="[&amp;_tr:last-child]:border-0">';
                // Si la date de la rencontre est dans le futur, on affiche les joueurs de l'équipe
                foreach ($rencontre->getFeuilleMatch() as $feuille_match) {
                  $joueur = $feuille_match->getJoueur();
                  echo "
            <tr class='border-b border-border transition-colors even:bg-white'>
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
                echo '<div class="bg-neutral-50 p-4 lg:col-span-2 space-y-2 rounded-xl border border-neutral-300/50">
                  <h4 class="text-2xl font-semibold">Feuille de match</h4>
                  <p class="text-lg text-neutral-600">Aucune feuille de match n\'a été renseignée pour cette rencontre.</p>';
                Components::Button([
                  'label' => 'Ajouter une feuille de match',
                  'class' => 'bg-primary-500 hover:bg-primary-600 text-white',
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
