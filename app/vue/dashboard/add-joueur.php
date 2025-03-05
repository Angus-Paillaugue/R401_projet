<?php
ob_start();

$title = 'Ajouter un joueur';
?>

<form method="POST" class="container w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900">
  <h1>
    Ajouter un joueur
  </h1>
  <div class='grid grid-cols-1 md:grid-cols-2 gap-4' id="firstRow"></div>
  <div class='grid grid-cols-1 md:grid-cols-2 gap-4' id="secondRow"></div>
  <div class='grid grid-cols-1 md:grid-cols-2 gap-4' id="thirdRow"></div>
  <div id="alertContainer"></div>
  <div id="addButton"></div>
</form>

<script type="module">
  import { httpRequest }  from '/vue/js/http.js';
  import { renderTemplate }  from '/vue/js/html.js';
  import Components from '/vue/js/components.js';
  import { BASE_GESTION_API_URL } from '/vue/js/constants.js';

  function buildUI() {
    Components.render('#addButton', Components.Button({
      label: 'Ajouter',
      type: 'submit',
      class: 'ml-auto',
    }));
    Components.render('#firstRow', Components.Input({
      id: 'nom',
      label: 'Nom',
      required: true,
    }));
    Components.render('#firstRow', Components.Input({
      id: 'prenom',
      label: 'Prénom',
      required: true,
    }));
    Components.render('#secondRow', Components.Input({
      id: 'numero_licence',
      label: 'Numéro de licence',
      required: true,
    }));
    Components.render('#secondRow', Components.Input({
      id: 'date_naissance',
      label: 'Date de naissance',
      type: 'date',
      required: true,
    }));
    Components.render('#thirdRow', Components.Input({
      id: 'taille',
      label: 'Taille',
      type: 'number',
      required: true,
      min: 0,
      step: 0.01,
      max: 3,
    }));
    Components.render('#thirdRow', Components.Input({
      id: 'poid',
      label: 'Poids',
      type: 'number',
      required: true,
      min: 0,
      step: 0.01,
      max: 300,
    }));

  }

  const setAlert = (message) => {
    removeAlert();
    Components.render('#alertContainer', Components.Alert({
      text: message,
      class: 'md:col-span-2 m-0',
      id:"alert"
    }));
  }
  const removeAlert = () => {
    $('#alert').remove();
  }

  async function handleSubmit(e) {
    e.preventDefault();
    removeAlert();

    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    try {
      const res = await httpRequest("POST", `${BASE_GESTION_API_URL}/joueur/index.php`, data);
      // window.location.href = '/vue/dashboard/joueur.php?id=' + res.data.id;
    } catch (e) {
      setAlert(e.message);
    }
  }

  $(document).ready(() => {
    buildUI();
    $('form').submit(handleSubmit);
  })
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
