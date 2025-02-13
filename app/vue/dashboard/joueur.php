<?php
session_start();
ob_start();

$title = 'Joueur';

if (!isset($_GET['id'])) {
  throw new Exception('ID du joueur non fourni');
}
?>

<div class="container w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900">
  <div class="flex flex-row items-center justify-between">
    <h1 id="playerName"></h1>
    <div class="flex flex-row gap-4" id="playerActions"></div>
  </div>
  <p class="text-neutral-600 dark:text-neutral-400 text-base">
    Licence : <span class="font-semibold font-mono" id="playerLicenseNumber"></span>
  </p>
  <div class="flex flex-row gap-8 flex-wrap">
    <!-- Anniversaire -->
    <div class="flex flex-row gap-2 items-center px-4 py-2 border rounded-lg w-fit border-neutral-300/50 dark:border-neutral-900">
      <span class="text-neutral-600 dark:text-neutral-400" id="birthdayIcon"></span>
      <time id="playerBirthDate"></time>
    </div>

    <!-- Poids -->
    <div class="flex flex-row gap-2 items-center px-4 py-2 border rounded-lg w-fit border-neutral-300/50 dark:border-neutral-900">
      <span class="text-neutral-600 dark:text-neutral-400" id="weightIcon"></span>
      <span id="playerWeight"></span>
    </div>

    <!-- Status -->
    <div class="flex flex-row gap-2 items-center px-4 py-2 border rounded-lg w-fit border-neutral-300/50 dark:border-neutral-900">
      <span class="text-neutral-600 dark:text-neutral-400" id="statusIcon"></span>
      <span id="playerStatus"></span>
    </div>

    <!-- Poste -->
    <div class="flex flex-row gap-2 items-center px-4 py-2 border rounded-lg w-fit border-neutral-300/50 dark:border-neutral-900">
      <span class="text-neutral-600 dark:text-neutral-400" id="postIcon"></span>
      <span id="playerPost"></span>
    </div>
  </div>

  <div class="flex flex-col gap-4" id="playerComments">
    <div class="flex flex-row justify-between items-center">
      <h3>Commentaires</h3>
    </div>
    <!-- Comments geos here -->
  </div>
</div>


<script type="module">
  import { httpRequest } from '/vue/js/http.js';
  import Components from '/vue/js/components.js';

  const playerId = new URLSearchParams(window.location.search).get('id');

  const playerRes = await httpRequest('GET', `/api/joueur?id=${playerId}`);
  if(playerRes.status === 404) {
    window.location.href = '/vue/dashboard/joueurs.php';
  }
  const player = playerRes.data;
  const statsRes = await httpRequest('GET', `/api/stats/player?id=${playerId}`);
  if(statsRes.status === 404) {
    window.location.href = '/vue/dashboard/joueurs.php';
  }
  const stats = statsRes.data;


  $('#playerName').text(`${player.nom} ${player.prenom}`);

  $('#playerActions').append([
    Components.Button({
      label: 'Modifier',
      href: `/vue/dashboard/edit-joueur.php?id=${player.id}`,
    }),
    Components.Button({
      icon: 'trash',
      variant: 'danger square',
      class: 'p-3',
      href: `/controleur/SupprimerUnJoueur.php?id=${player.id}`,
    }),
  ]);

  $('#playerLicenseNumber').text(player.numero_licence);

  $('#birthdayIcon').append(Components.Icon({ icon: 'birthday' }));

  $('#playerBirthDate').text(player.date_naissance);

  $('#weightIcon').append(Components.Icon({ icon: 'weight' }));

  $('#playerWeight').text(`${player.poids} kg`);

  $('#statusIcon').append(Components.Icon({ icon: 'status' }));

  $('#playerStatus').text(player.statut);

  $('#postIcon').append(Components.Icon({ icon: 'poste' }));

  $('#playerPost').text(stats.poste);

  $('#playerComments > div').append(Components.Button({
    icon: 'plus',
    label: 'Ajouter',
    href: `/vue/dashboard/add-commentaire.php?id=${player.id}`,
  }));

  if(player.commentaires.length === 0) {

  } else {
    // Add each comment to the list
    player.commentaires.forEach(comment => {
      $('#playerComments').append(`
        <div class='bg-neutral-100 dark:bg-neutral-900 p-4 rounded-lg border border-neutral-300/50 dark:border-neutral-900 group/comment relative overflow-hidden'>
          <p class='text-base whitespace-pre-wrap'>${comment.contenu}</p>
          <time class='text-sm text-neutral-600 dark:text-neutral-400'>
            ${comment.date}
          </time>
          <div class='opacity-0 transition-opacity group-hover/comment:opacity-100 bottom-0 absolute top-0 right-0 flex flex-col justify-between' id="comment-${comment.id}"></div>
        </div>
      `);
      $(`#comment-${comment.id}`).append([
        Components.Button({
          label: 'Modifier',
          href: `/vue/dashboard/edit-commentaire.php?id=${comment.id}`,
        }),
        Components.Button({
          label: 'Supprimer',
          variant: 'danger',
          href: `/controleur/SupprimerUnCommentaire.php?id=${comment.id}&redirect=${encodeURIComponent(window.location.pathname)}`,
        })
      ]);
    });
  }
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
