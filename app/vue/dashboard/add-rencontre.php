<?php
ob_start();

$title = 'Ajouter une rencontre';
?>

<form method="POST" class="container w-full mx-auto p-4 rounded-xl border space-y-6 border-neutral-300/50 dark:border-neutral-900">
  <h2>Ajouter une rencontre</h2>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="form">
  </div>

  <div class="grid grid-cols-2 gap-4" id="actions"></div>
</form>

<script type="module">
  import { httpRequest }  from '/vue/js/http.js';
  import { renderTemplate }  from '/vue/js/html.js';
  import Components from '/vue/js/components.js';
  import { BASE_GESTION_API_URL } from '/vue/js/constants.js';

  function buildUI() {
    Components.render('#actions', Components.Button({
      label: 'Annuler',
      variant: 'danger',
      type: 'button',
      href: '/vue/dashboard',
    }));
    Components.render('#actions', Components.Button({
      label: 'Ajouter',
      type: 'submit',
    }));

    Components.render('#form', Components.Input({
      id: 'equipe_adverse',
      label: 'Équipe adverse',
      required: true,
    }));

    Components.render('#form', Components.Input({
      id: 'date_heure',
      label: 'Date et heure',
      type: 'datetime-local',
      required: true,
    }));

    Components.render('#form', Components.Select({
      id: 'lieu',
      label: 'Lieu',
      options: ['Domicile', 'Extérieur'],
      required: true,
    }));
  }

  const setAlert = (message) => {
    removeAlert();
    Components.render('#form', Components.Alert({
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
      const res = await httpRequest("POST", `${BASE_GESTION_API_URL}/rencontre/index.php`, data);
      console.log(res)
      window.location.href = '/vue/dashboard/rencontres.php';
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
