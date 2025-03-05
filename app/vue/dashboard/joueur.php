<?php
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
  import { renderTemplate } from '/vue/js/html.js';
  import Components from '/vue/js/components.js';
  import { BASE_GESTION_API_URL } from '/vue/js/constants.js';

  const playerId = new URLSearchParams(window.location.search).get('id');

  const playerRes = await httpRequest('GET', BASE_GESTION_API_URL+`/joueur/index.php?id=${playerId}`);
  if(playerRes.status === 404) {
    window.location.href = '/vue/dashboard/joueurs.php';
  }
  const player = playerRes.data;
  const statsRes = await httpRequest('GET', BASE_GESTION_API_URL+`/stats/player/index.php?id=${playerId}`);
  if(statsRes.status === 404) {
    window.location.href = '/vue/dashboard/joueurs.php';
  }
  const stats = statsRes.data;

  $('#playerName').text(`${player.nom} ${player.prenom}`);
  $('#playerLicenseNumber').text(player.numero_licence);
  $('#playerBirthDate').text(player.date_naissance);
  $('#playerWeight').text(`${player.poids} kg`);
  $('#playerStatus').text(player.statut);
  $('#playerPost').text(stats.poste);

  Components.render('#playerActions', Components.Button({
    label: 'Modifier',
    href: `/vue/dashboard/edit-joueur.php?id=${player.id}`,
  }));
  Components.render('#playerActions', Components.Button({
    icon: 'trash',
    variant: 'danger square',
    class: 'p-3',
    href: `/controleur/SupprimerUnJoueur.php?id=${player.id}`,
  }));
  Components.render('#birthdayIcon', Components.Icon({ icon: 'birthday' }));
  Components.render('#weightIcon', Components.Icon({ icon: 'weight' }));
  Components.render('#statusIcon', Components.Icon({ icon: 'status' }));
  Components.render('#postIcon', Components.Icon({ icon: 'poste' }));
  Components.render('#playerComments > div', Components.Button({
    icon: 'plus',
    label: 'Ajouter',
    href: `/vue/dashboard/add-commentaire.php?id=${player.id}`,
  }));

  // Add each comment to the list
  const commentTemplate = `
    <div class='bg-neutral-100 dark:bg-neutral-900 p-4 rounded-lg border border-neutral-300/50 dark:border-neutral-900 group/comment relative overflow-hidden'>
      <p class='text-base whitespace-pre-wrap'>{{contenu}}</p>
      <time class='text-sm text-neutral-600 dark:text-neutral-400'>{{date}}</time>
      <div class='opacity-0 transition-opacity group-hover/comment:opacity-100 bottom-0 absolute top-0 right-0 flex flex-col justify-between' id="comment-{{id}}"></div>
    </div>
  `;
  player.commentaires.forEach(comment => {
    $('#playerComments').append(renderTemplate(commentTemplate, comment));
    Components.render(`#comment-${comment.id}`, Components.Button({
      label: 'Modifier',
      href: `/vue/dashboard/edit-commentaire.php?id=${comment.id}`,
    }));
    Components.render(`#comment-${comment.id}`, Components.Button({
      label: 'Supprimer',
      variant: 'danger',
      class: 'delete-comment',
      id: `delete-comment-${comment.id}`,
    }));
  });

  $('.delete-comment').on('click', async function() {
    const commentId = $(this).attr('id').split('-')[2];
    if(!commentId) return;
    const res = await httpRequest('DELETE', `${BASE_GESTION_API_URL}/commentaire/index.php?id=${commentId}`);
    if(res.status_code === 200) {
      $(this).parent().parent().remove();
    }
  });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
