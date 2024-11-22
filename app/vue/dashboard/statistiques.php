<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/formatters.php';
require_once __DIR__ . '/../../lib/error.php';
require_once __DIR__ . '/../../controleur/RecupererStatistiquesClub.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

$title = 'Statistiques';

try {
  $statistiques = (new RecupererStatistiquesClub())->execute();
  $clubStats = $statistiques['club'];
  $playersStats = $statistiques['joueurs'];
} catch (Exception $e) {
  ErrorHandling::setFatalError($e->getMessage());
}
?>


<div class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-6 border-neutral-300/50 dark:border-neutral-900">
  <h1>Statistiques</h1>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-neutral-100 dark:bg-neutral-900 transition-colors flex flex-row justify-between p-4 rounded-lg border border-neutral-300/50 dark:border-neutral-900">
      <div class="flex flex-col">
        <h4>Globales</h4>
        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Taux de victoire: <span class='text-neutral-900 dark:text-neutral-100'><?php echo ($clubStats[
          'nbMatchGagnes'
        ] /
          $clubStats['nbMatchTotal']) *
          100; ?>%</span></p>
        <div class="flex flex-col mt-4">
          <p class="text-neutral-600 dark:text-neutral-400">Total</p>
          <p class="text-3xl font-bold font-mono"><?php echo $clubStats[
            'nbMatchTotal'
          ]; ?></p>
        </div>
      </div>
      <div class="flex flex-col items-end justify-between">
        <button class="px-3 py-1 rounded bg-green-400 dark:bg-green-600 font-mono group flex flex-row text-start">
          <span class="w-0 overflow-hidden group-hover:w-24 group-focus:w-24 transition-all block">Victoire</span>
          <span><?php echo $clubStats['nbMatchGagnes']; ?></span>
        </button>
        <button class="px-3 py-1 rounded bg-red-400 dark:bg-red-600 font-mono group flex flex-row text-start">
          <span class="w-0 overflow-hidden group-hover:w-24 group-focus:w-24 transition-all block">Défaites</span>
          <span><?php echo $clubStats['nbMatchPerdus']; ?></span>
        </button>
        <button class="px-3 py-1 rounded bg-gray-400 dark:bg-gray-600 font-mono group flex flex-row text-start">
          <span class="w-0 overflow-hidden group-hover:w-24 group-focus:w-24 transition-all block">Nul</span>
          <span><?php echo $clubStats['nbMatchNuls']; ?></span>
        </button>
      </div>
    </div>
  </div>

  <div class="w-full overflow-auto max-h-[500px]">
    <table class='w-full table-auto text-sm rounded-lg overflow-hidden'>
      <thead class='sticky top-0 bg-neutral-100 dark:bg-neutral-900'>
        <tr>
          <td scope='col' class='sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600 dark:text-neutral-400'>Joueur</td>
          <td scope='col' class='sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600 dark:text-neutral-400'>Meilleur poste</td>
          <td scope='col' class='sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600 dark:text-neutral-400'>Titularisations</td>
          <td scope='col' class='sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600 dark:text-neutral-400'>Remplacements</td>
          <td scope='col' class='sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600 dark:text-neutral-400'>Note</td>
          <td scope='col' class='sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600 dark:text-neutral-400'>% Victoire</td>
          <td scope='col' class='sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600 dark:text-neutral-400'>Nb matches consécutifs</td>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($playersStats as $joueur): ?>
          <tr class='even:bg-neutral-100 dark:even:bg-neutral-900 text-base font-base'>
            <td class='px-4 py-1 align-middle'>
              <a class='hover:underline' href='/vue/dashboard/joueur.php?id=<?php echo $joueur[
                'id'
              ]; ?>'>
                <?php echo $joueur['prenom'] . ' ' . $joueur['nom']; ?>
              </a>
            </td>
            <td class='px-4 py-1 align-middle'><?php echo $joueur[
              'poste_max_victoire'
            ]; ?></td>
            <td class='px-4 py-1 align-middle'><?
              echo $joueur['nbTitulaire'];
            ?></td>
            <td class='px-4 py-1 align-middle'><?php echo $joueur[
              'nbRemplacant'
            ]; ?></td>
            <td class='px-4 py-1 align-middle'><?php echo $joueur[
              'moyenne_notes'
            ]
              ? number_format($joueur['moyenne_notes'], 1)
              : ''; ?></td>
            <td class='px-4 py-1 align-middle'><?php echo $joueur[
              'pourcentage_victoire_titulaire'
            ]
              ? number_format($joueur['pourcentage_victoire_titulaire'], 1)
              : ''; ?></td>
            <td class='px-4 py-1 align-middle'><?php echo $joueur[
              'nbMatchConsecutifs'
            ] ?? 0; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
