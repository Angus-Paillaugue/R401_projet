<?php
ob_start();

$title = 'Modifier un commentaire';
?>

<form method="POST" class="container w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900">
  <h1>
    Modifier un commentaire
  </h1>
  <input type="hidden" name="id_joueur" />
  <textarea name="contenu" id="contenu" class="w-full h-32 border border-neutral-300/50 dark:border-neutral-900 rounded-lg p-2" placeholder="Entrez votre commentaire ici"></textarea>

  <span id="saveButton"></span>
</form>

<script type="module">
  import { httpRequest }  from '/vue/js/http.js';
  import { renderTemplate }  from '/vue/js/html.js';
  import Components from '/vue/js/components.js';
  import { BASE_GESTION_API_URL } from '/vue/js/constants.js';

  const COMMENT_ID = new URLSearchParams(window.location.search).get('id');

  function buildUI() {
    Components.render("#saveButton", Components.Button({
      label: 'Enregistrer',
      type: 'submit'
    }));
  }

  async function populateFields() {
    const response = await httpRequest('GET', `${BASE_GESTION_API_URL}/commentaire/index.php?id=${COMMENT_ID}`);
    const comment = response.data;

    $('input[name="id_joueur"]').val(comment.id_joueur);
    $('#contenu').val(comment.contenu);
  }

  async function handleSubmit(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    data.id = COMMENT_ID;

    const res = await httpRequest('PUT', `${BASE_GESTION_API_URL}/commentaire/index.php`, data);
    window.location.href = `/vue/dashboard/joueur.php?id=${data.id_joueur}`;
  }

  $(document).ready(() => {
    // If no comment id is provided, go back to the previous page
    if(!COMMENT_ID) window.history.back();

    buildUI();
    populateFields();
    $('form').on('submit', handleSubmit);
  });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
