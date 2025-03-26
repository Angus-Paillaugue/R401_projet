<?php
ob_start();

$title = 'Dashboard';
?>


<div class="container w-full mx-auto p-4 rounded-xl border space-y-6 border-neutral-300/50 dark:border-neutral-900" id="main">
	<h1>Bienvenue <span id="heading-username"></span></h1>
  <div class='flex max-md:flex-col md:grid grid-cols-2 gap-8'>
    <div class='flex flex-col gap-4' id="navButtons"></div>

    <!-- Players list -->
    <div>
      <h2>Joueurs</h2>
      <div class='m-0! max-h-[300px] overflow-y-auto rounded-lg'>
        <table class='w-full table-auto text-sm' id="players-table">
          <thead class='sticky top-0 bg-neutral-100 dark:bg-neutral-900'>
            <tr>
              <td scope='col' class='sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600 dark:text-neutral-400'>Nom</td>
              <td scope='col' class='sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600 dark:text-neutral-400'>Prénom</td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>


  <!-- Rencontres -->
  <section id="rencontres" class="space-y-6">
    <div class="next">
      <div class='flex flex-row items-center justify-between'>
        <h2>Rencontres à venir</h2>
        <!-- Voir tout button -->
      </div>
      <div class='grid grid-cols-1 lg:grid-cols-2 gap-4 list'></div>
    </div>

    <div class="previous">
      <div class='flex flex-row items-center justify-between'>
        <h2>Rencontres passées</h2>
        <!-- Voir tout button -->
      </div>
      <div class='grid grid-cols-1 lg:grid-cols-2 gap-4 list'></div>
    </div>
  </section>
</div>


<script type="module">
  import { httpRequest }  from '/vue/js/http.js';
  import { renderTemplate }  from '/vue/js/html.js';
  import Components from '/vue/js/components.js';
  import { BASE_GESTION_API_URL, BASE_AUTH_API_URL } from '/vue/js/constants.js';

  async function buildUI() {
    Components.render('#navButtons', Components.Button({
      'label': 'Ajouter une rencontre',
      'href': '/vue/dashboard/add-rencontre.php',
      'icon': 'plus'
    }));

    Components.render('#navButtons', Components.Button({
      'label': 'Ajouter un joueur',
      'href': '/vue/dashboard/add-joueur.php',
      'icon': 'plus'
    }));

    Components.render('#navButtons', Components.Button({
      'label': 'Ajouter un joueur',
      'href': '/vue/dashboard/add-joueur.php',
      'icon': 'plus'
    }));

    Components.render('#navButtons', Components.Button({
      'label': 'Statistiques',
      'href': '/vue/dashboard/statistiques.php',
      'icon': 'chart'
    }));

    const userData = (await httpRequest('GET', BASE_AUTH_API_URL + '/index.php')).data;
    $('#heading-username').text(userData.username);
  }

  async function addPlayers() {
    const players = await httpRequest('GET', BASE_GESTION_API_URL+'/joueur/index.php');

    // Add players list
    const playersTable = $('#players-table tbody');
    const rowHTML = `<tr class='even:bg-neutral-100 dark:even:bg-neutral-900'><td class='px-4 py-1 align-middle'><a class='hover:underline' href='/vue/dashboard/joueur.php?id={{id}}'>{{nom}}</a></td><td class='px-4 py-1 align-middle'>{{prenom}}</td></tr>`;
    for(const player of players.data) {
      $(playersTable).append(renderTemplate(rowHTML, {
        id: player.id,
        nom: player.nom,
        prenom: player.prenom
      }));
    }
  }

  async function addRencontres() {
    const rencontres = (await httpRequest('GET', BASE_GESTION_API_URL+'/rencontre/index.php')).data;

    const previousTemplate = `
      <a href='/vue/dashboard/rencontre.php?id={{id}}' class='bg-neutral-100 dark:bg-neutral-900 p-4 rounded-lg border border-neutral-300/50 dark:border-neutral-900'>
        <div class='flex flex-row justify-between items-center'>
          <h4 class='text-2xl font-semibold'>{{equipe_adverse}}</h4>
          <time class='text-base text-neutral-600 dark:text-neutral-400 font-base'>{{date_heure}}</time>
        </div>
        <div class='flex flex-row items-end justify-between'>
          <p class='text-neutral-600 dark:text-neutral-400 text-lg font-semibold'>{{lieu}}</p>
          {{pill}}
        </div>
      </a>`;
    for(const previous of rencontres.previous) {
      const pillBg =
        previous.resultat === 'Nul'
          ? 'bg-neutral-600'
          : (previous.resultat == 'Victoire'
            ? 'bg-green-600'
            : 'bg-red-600');
      // Affichage du badge si le résultat est défini
      const pill = previous.resultat ? `<div class='px-2 py-1 text-base rounded-full text-neutral-100 font-semibold ${pillBg}'>${previous.resultat}</div>` : ' ';
      $("section#rencontres > .previous > .list").append(renderTemplate(previousTemplate, {
        id: previous.id,
        equipe_adverse: previous.equipe_adverse,
        date_heure: new Date(previous.date_heure).toLocaleString(),
        lieu: previous.lieu,
        pill
      }));
    }

    const nextTemplate = `
      <a href='/vue/dashboard/rencontre.php?id={{id}}' class='bg-neutral-100 dark:bg-neutral-900 p-4 rounded-lg border border-neutral-300/50 dark:border-neutral-900'>
        <div class='flex flex-row justify-between items-center'>
          <h4 class='text-2xl font-semibold'>{{equipe_adverse}}</h4>
          <time class='text-base text-neutral-600 dark:text-neutral-400 font-base'>{{date_heure}}</time>
        </div>
        <p class='text-neutral-600 dark:text-neutral-400 text-lg font-semibold'>{{lieu}}</p>
      </a>`;
    for(const next of rencontres.next) {
      $("section#rencontres > .next > .list").append(renderTemplate(nextTemplate, {
        id: next.id,
        equipe_adverse: next.equipe_adverse,
        date_heure: new Date(next.date_heure).toLocaleString(),
        lieu: next.lieu
      }));
    }

    if(rencontres.previous.length > 0) {
      Components.render("section#rencontres > .previous > div", Components.Button({
        'label': 'Voir tout',
        'variant': 'primary',
        'href': '/vue/dashboard/rencontres.php?previous',
        'icon': 'plus'
      }));
    }

    if(rencontres.next.length > 0) {
      Components.render("section#rencontres > .next > div", Components.Button({
        'label': 'Voir tout',
        'variant': 'primary',
        'href': '/vue/dashboard/rencontres.php?next',
        'icon': 'plus'
      }));
    }
  }


  // Main function to populate the page
  function main() {
    Promise.all([buildUI(), addPlayers(), addRencontres()]); // Could just call buildUI(), addPlayers() and addRencontres() but this is more explicit
  }

  $(document).ready(main);
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
