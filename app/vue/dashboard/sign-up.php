<?php
session_start();
ob_start();

$title = 'Créer un compte';
?>
<div class="max-w-xl mx-auto w-full p-4">
  <form class="p-4 space-y-4 flex flex-col rounded-xl w-full bg-neutral-100 dark:bg-neutral-800">
    <h2 class="m-0">Créer un compte</h2>
  </form>
</div>

<script type="module">
  import { httpRequest } from '/vue/js/http.js';
  import { renderTemplate } from '/vue/js/html.js';
  import Components from '/vue/js/components.js';

  function buildUI() {
    $('form').append([
      Components.Input({
        id: 'username',
        label: "Nom d'utilisateur",
        placeholder: "Votre nom d'utilisateur",
      }),
      Components.Input({
        id: 'password',
        label: 'Mot de passe',
        placeholder: 'Votre mot de passe',
        type: 'password',
      }),
      Components.Button({
        label: 'Créer',
        variant: 'primary',
      })
    ]);
  }

  function setFormError(text) {
    if($('#formError')) {
      $('#formError').remove();
    }
    if($('#formSuccess')) {
      $('#formSuccess').remove();
    }
    const error = Components.Alert({ text, id: 'formError' });
    $("form").append(error);
  }

  function setFormSuccess(text) {
    if($('#formSuccess')) {
      $('#formSuccess').remove();
    }
    if($('#formError')) {
      $('#formError').remove();
    }
    const success = Components.Alert({ text, id: 'formSuccess', variant: 'success' });
    $("form").append(success);
  }

  async function handleFormSubmit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const { username, password } = Object.fromEntries(formData.entries());
    if(!username || !password) {
      setFormError('Veuillez remplir tous les champs');
      return;
    }
    try {
      const res = await httpRequest("POST", "/api/utilisateur", { username, password });
      if (res.status_code === 201) {
        setFormSuccess('Compte créé avec succès');
      } else {
        setFormError(res.status_message);
      }
    } catch (error) {
      setFormError(error);
    }
  }

  $(document).ready(() =>  {
    buildUI();
    $("form").on('submit', handleFormSubmit);
  });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
