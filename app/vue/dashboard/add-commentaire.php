<?php
session_start();
ob_start();

$title = 'Ajouter un commentaire';
?>

<form class="container w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900">
  <h1>
    Ajouter un commentaire
  </h1>
  <input type="hidden" name="id">
  <textarea name="commentaire" class="w-full h-32 border border-neutral-300/50 dark:border-neutral-900 rounded-lg p-2" placeholder="Entrez votre commentaire ici"></textarea>
</form>

<script type="module">
  import { httpRequest } from '/vue/js/http.js';
  import Components from '/vue/js/components.js';
  import { BASE_GESTION_API_URL } from '/vue/js/constants.js';

  const playerId = new URL(window.location).searchParams.get('id');
  $('[name="id"]').val(playerId);

  $('form').append(Components.Button({
    label: 'Ajouter',
  }));

  function setFormError(text) {
    if($('#formError')) {
      $('#formError').remove();
    }
    const error = Components.Alert({ text, id: 'formError' });
    $("form").append(error);
  }

  $('form').on('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    if(!data.commentaire) {
      setFormError('Le commentaire ne peut pas être vide');
      return;
    }
    const response = await httpRequest("POST", BASE_GESTION_API_URL+'/commentaire/index.php', {
      contenu: data.commentaire,
      id_joueur: data.id,
    });

    if(response.status_code === 201) {
      window.location.href = '/vue/dashboard/joueur.php?id=' + playerId;
    } else {
      setFormError(response.data);
    }
  });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
