<?php
ob_start();

$title = 'Modifier un joueur';
?>

<form method="POST" class="container w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900">
  <h1>
    Modifier un joueur
  </h1>
  <input type="hidden" name="id" />
  <div class='grid grid-cols-1 md:grid-cols-2 gap-4' id="firstRow"></div>
  <div class='grid grid-cols-1 md:grid-cols-2 gap-4' id="secondRow"></div>
  <div class='grid grid-cols-1 md:grid-cols-2 gap-4' id="thirdRow"></div>
  <span id="submit"></span>
</form>

<script type="module">
  import { httpRequest }  from '/vue/js/http.js';
  import { renderTemplate }  from '/vue/js/html.js';
  import Components from '/vue/js/components.js';
  import { BASE_GESTION_API_URL } from '/vue/js/constants.js';

  const PLAYER_ID = new URLSearchParams(window.location.search).get('id');

  function buildUI() {
    Components.render('#firstRow', Components.Input({
      id: 'nom',
      label: 'Nom',
      required: true
    }));

    Components.render('#firstRow', Components.Input({
      id: 'prenom',
      label: 'Prénom',
      required: true
    }));

    Components.render('#secondRow', Components.Input({
      id: 'licence',
      label: 'Numéro de licence',
      required: true
    }));

    Components.render('#secondRow', Components.Input({
      id: 'date_de_naissance',
      label: 'Date de naissance',
      type: 'date',
      required: true
    }));

    Components.render('#thirdRow', Components.Input({
      id: 'taille',
      label: 'Taille',
      type: 'number',
      required: true,
      min: 0,
      step: 0.01,
      max: 3
    }));

    Components.render('#thirdRow', Components.Input({
      id: 'poids',
      label: 'Poids',
      type: 'number',
      required: true,
      min: 0,
      step: 0.01,
      max: 300
    }));

    Components.render('#submit', Components.Button({
      label: 'Modifier',
      type: 'submit',
      class: 'ml-auto'
    }), true);
  }

  async function fillFields() {
    const res = await httpRequest('GET', `${BASE_GESTION_API_URL}/joueur/index.php?id=${PLAYER_ID}`);
    const player = res.data;

    $('#id').val(player.id);
    $('#nom').val(player.nom);
    $('#prenom').val(player.prenom);
    $('#licence').val(player.numero_licence);
    $('#date_de_naissance').val(player.date_naissance);
    $('#taille').val(player.taille);
    $('#poids').val(player.poids);
  }

  function handleFormSubmit(e) {
    e.preventDefault();
    const data = new FormData(e.target);
    const playerData = Object.fromEntries(data.entries());
    playerData.id = PLAYER_ID;

    httpRequest('PATCH', `${BASE_GESTION_API_URL}/joueur/index.php`, playerData)
      .then(() => window.location.href = '/vue/dashboard/joueur.php?id=' + PLAYER_ID);
  }

  $(document).ready(() => {
    // Redirect if no player id is provided, go back
    if(!PLAYER_ID) window.history.back();
    buildUI();

    fillFields();
    $('form').on('submit', handleFormSubmit);
  });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
