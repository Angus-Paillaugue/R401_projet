<?php
ob_start();

$title = 'Joueurs';
?>

<div class="container w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900">
  <h1>Joueurs</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gris-cols-3" id="playersList">
    <!-- Players list goes here -->
  </div>
</div>

<script type="module">
  import { httpRequest } from '/vue/js/http.js';
  import { renderTemplate }  from '/vue/js/html.js';
  import { BASE_GESTION_API_URL } from '/vue/js/constants.js';

  // TODO: Error handling
  const players = (await httpRequest('GET', BASE_GESTION_API_URL+'/joueur/index.php')).data;
  const template = `
    <a href='/vue/dashboard/joueur.php?id={{id}}' class="bg-neutral-100 dark:bg-neutral-900 border border-neutral-300/50 dark:border-neutral-900 p-4 rounded-xl">
      <h4 class="text-2xl font-semibold">{{prenom}} {{nom}}</h4>
      <time class="text-base text-neutral-600 dark:text-neutral-400 font-base">{{date_naissance}}</time>
    </a>
  `;

  players.forEach(player => {
    $('#playersList').append(renderTemplate(template, player));
  });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
