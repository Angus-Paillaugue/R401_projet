<?php
session_start();
ob_start();

$title = 'Statistiques';
?>


<div class="container w-full mx-auto p-4 rounded-xl border space-y-6 border-neutral-300/50 dark:border-neutral-900">
  <h1>Statistiques</h1>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-neutral-100 dark:bg-neutral-900 transition-colors flex flex-row justify-between p-4 rounded-lg border border-neutral-300/50 dark:border-neutral-900">
      <div class="flex flex-col">
        <h4>Globales</h4>
        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Taux de victoire: <span class='text-neutral-900 dark:text-neutral-100' id="victoryPercentage"></span></p>
        <div class="flex flex-col mt-4">
          <p class="text-neutral-600 dark:text-neutral-400">Total</p>
          <p class="text-3xl font-bold font-mono" id="nbMatchTotal"></p>
        </div>
      </div>
      <div class="flex flex-col items-end justify-between">
        <button class="px-3 py-1 rounded text-neutral-100 dark:text-neutral-900 bg-green-600 dark:bg-green-600 font-mono group flex flex-row text-start">
          <span class="w-0 overflow-hidden group-hover:w-24 group-focus:w-24 transition-all block">Victoire</span>
          <span id="nbMatchGagnes"></span>
        </button>
        <button class="px-3 py-1 rounded text-neutral-100 dark:text-neutral-900 bg-red-600 dark:bg-red-600 font-mono group flex flex-row text-start">
          <span class="w-0 overflow-hidden group-hover:w-24 group-focus:w-24 transition-all block">Défaites</span>
          <span id="nbMatchPerdus"></span>
        </button>
        <button class="px-3 py-1 rounded text-neutral-100 dark:text-neutral-900 bg-neutral-600 font-mono group flex flex-row text-start">
          <span class="w-0 overflow-hidden group-hover:w-24 group-focus:w-24 transition-all block">Nul</span>
          <span id="nbMatchNuls"></span>
        </button>
      </div>
    </div>
  </div>

  <div class="w-full overflow-auto max-h-[500px]">
    <table class='w-full table-auto text-sm rounded-lg overflow-hidden' id="playerStatsTable">
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
        <!-- Players stats rows goes here -->
      </tbody>
    </table>
  </div>
</div>

<script type="module">
  import { httpRequest } from '/vue/js/http.js';
  import { renderTemplate } from '/vue/js/html.js';
  import Components from '/vue/js/components.js';

  // TODO: Error handling
  const stats = (await httpRequest('GET', '/api/stats/club')).data;
  console.log(stats)

  // Set club stats
  const victoryPercentage = ((stats.club.nbMatchGagnes / stats.club.nbMatchTotal) * 100).toFixed(2);
  $('#victoryPercentage').text(victoryPercentage + '%');
  $('#nbMatchTotal').text(stats.club.nbMatchTotal);
  $('#nbMatchGagnes').text(stats.club.nbMatchGagnes);
  $('#nbMatchPerdus').text(stats.club.nbMatchPerdus);
  $('#nbMatchNuls').text(stats.club.nbMatchNuls);

  // Set players stats
  const tableRowTemplate = `
    <tr class='even:bg-neutral-100 dark:even:bg-neutral-900 text-base font-base'>
      <td class='px-4 py-1 align-middle'>
        <a class='hover:underline' href='/vue/dashboard/joueur.php?id={{id}}'>{{prenom}} {{nom}}</a>
      </td>
      <td class='px-4 py-1 align-middle'>{{poste_max_victoire}}</td>
      <td class='px-4 py-1 align-middle'>{{nbTitulaire}}</td>
      <td class='px-4 py-1 align-middle'>{{nbRemplacant}}</td>
      <td class='px-4 py-1 align-middle'>{{moyenne_notes}}</td>
      <td class='px-4 py-1 align-middle'>{{pourcentage_victoire_titulaire}}</td>
      <td class='px-4 py-1 align-middle'>{{nbMatchConsecutifs}}</td>
    </tr>
  `;

  stats.joueurs.forEach(joueur => {
    console.log(joueur)
    const row = renderTemplate(tableRowTemplate, {
      id: joueur.id,
      prenom: joueur.prenom,
      nom: joueur.nom,
      poste_max_victoire: joueur.poste_max_victoire ?? '0',
      nbTitulaire: joueur.nbTitulaire ?? '0',
      nbRemplacant: joueur.nbRemplacant ?? '0',
      moyenne_notes: joueur.moyenne_notes ? Number(joueur.moyenne_notes).toFixed(1) : ' ',
      pourcentage_victoire_titulaire: joueur.pourcentage_victoire_titulaire ? Number(joueur.pourcentage_victoire_titulaire).toFixed(1) : ' ',
      nbMatchConsecutifs: joueur.nbMatchConsecutifs ?? '0',
    })
    $('#playerStatsTable tbody').append(row);
  });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
