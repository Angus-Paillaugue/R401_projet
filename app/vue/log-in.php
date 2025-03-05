<?php
ob_start();

$title = 'Log-in';
?>

<div class="max-w-xl mx-auto w-full p-4">
	<form class="p-4 space-y-4 flex flex-col rounded-xl w-full bg-neutral-100 dark:bg-neutral-900 border border-neutral-300/50 dark:border-neutral-900">
		<h2 class="m-0">Se connecter</h2>
	</form>
</div>

<script type="module">
  import { httpRequest }  from './js/http.js';
  import Components from './js/components.js';
  import { BASE_AUTH_API_URL } from './js/constants.js';

  function buildUI() {
    const components = [
      Components.Input({
        id: 'username',
        label: 'Nom d\'utilisateur',
        placeholder: "Votre nom d'utilisateur",
      }),
      Components.Input({
        id: 'password',
        label: 'Mot de passe',
        placeholder: 'Votre mot de passe',
        type: 'password',
      }),
      Components.Button({
        label: 'Se connecter',
        variant: 'primary',
      }),
    ]

    $("form").append(components);
  }

  function setFormError(text) {
    if($('#formError')) {
      $('#formError').remove();
    }
    const error = Components.Alert({ text, id: 'formError' });
    $("form").append(error);
  }

  async function handleFormSubmit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const { username, password } = Object.fromEntries(formData.entries());
    try {
      const res = await httpRequest("POST", BASE_AUTH_API_URL + '/index.php', { username, password });
      if (res.status_code === 200) {
        window.location.href = '/vue/dashboard';
      } else {
        setFormError(res.data);
      }
    } catch (error) {
      setFormError(error);
    }
  }

  function main() {
    buildUI();
    $("form").on('submit', handleFormSubmit);
  }

  $(document).ready(main);
</script>

<?php
$content = ob_get_clean();
require_once './layout.php';


?>
