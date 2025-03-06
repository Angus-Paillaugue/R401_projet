<?php
ob_start();

$title = 'Modifier une rencontre';
?>

<form method="POST" class="container w-full mx-auto p-4 rounded-xl border space-y-6 border-neutral-300/50 dark:border-neutral-900">
  <h2>Modifier une rencontre</h2>

  <input type="hidden" name="id" />

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="main">
  </div>

  <div class="grid grid-cols-2 gap-4" id="actions"></div>
</form>

<script type="module">
  import { httpRequest }  from '/vue/js/http.js';
  import { renderTemplate }  from '/vue/js/html.js';
  import Components from '/vue/js/components.js';
  import { BASE_GESTION_API_URL, BASE_AUTH_API_URL } from '/vue/js/constants.js';

  const RENCONTRE_ID = new URLSearchParams(window.location.search).get('id');

  function buildUI() {
    Components.render('#actions', Components.Button({
      label: 'Annuler',
      variant: 'danger',
      type: 'button',
      href: `/vue/dashboard/rencontre.php?id=${new URLSearchParams(window.location.search).get('id')}`,
    }));
    Components.render('#actions', Components.Button({
      label: 'Enregistrer',
      variant: 'primary',
      type: 'submit',
    }));

    Components.render('#main', Components.Input({
      label: 'Équipe adverse',
      id: 'equipe_adverse',
    }));
    Components.render('#main', Components.Input({
      label: 'Date et heure',
      id: 'date_heure',
      type: 'datetime-local',
    }));
    Components.render('#main', Components.Input({
      label: 'Lieu',
      id: 'lieu',
    }));
    Components.render('#main', Components.Select({
      label: 'Résultat',
      id: 'resultat',
      options: [
        {
          text: 'Victoire',
          value: 'victoire',
        },
        {
          text: 'Défaite',
          value: 'defaite',
        },
        {
          text: 'Nul',
          value: 'nul',
        }
      ],
    }));
  }

  async function populateFields() {
    const res = await httpRequest('GET', `${BASE_GESTION_API_URL}/rencontre/index.php?id=${new URLSearchParams(window.location.search).get('id')}`);
    const rencontre = res.data;

    $('input[name="id"]').val(rencontre.id);
    $('input[name="equipe_adverse"]').val(rencontre.equipe_adverse);
    $('input[name="date_heure"]').val(rencontre.date_heure);
    $('input[name="lieu"]').val(rencontre.lieu);
    $('select[name="resultat"]').val(rencontre.resultat.toLowerCase());

    // Get all of the players to populate the select
    const playersRes = await httpRequest('GET', `${BASE_GESTION_API_URL}/joueur/index.php`);
    const players = playersRes.data;
    const playersOptions = players.map(joueur => ({
      text: joueur.prenom + ' ' + joueur.nom,
      value: joueur.id
    }));

    // Add the feulle de match
    for (let i = 0; i < 16; i++) {
      const feuille = rencontre.feuille_match
        ? rencontre.feuille_match[i]
        : null;

      if(!feuille) {
        continue;
      }

      const roleOptions = [
        'Titulaire',
        'Remplaçant'
      ];
      const evaluationOptions = new Array(5).fill(0).map((_, i) => i + 1);

      const feuilleTemplate = `
        <div class='p-4 rounded-lg border gap-4 border-neutral-300/50 dark:border-neutral-900 grid grid-cols-2'>
          <input type='hidden' name='id_feuille_{{i}}' id='id_feuille_{{i}}' value='{{id_feuille}}' class='hidden invisible'>
            <span id="joueurSelect-{{i}}"></span>
            <span id="posteInput-{{i}}"></span>
          <div class='block'>
            <p class='text-neutral-600 dark:text-neutral-400 font-medium text-base mb-1 block'>Rôle</p>
            <div class='flex items-center'>
              <span id="roleDebutSelect-{{i}}"></span>
              <span id="icon-{{i}}"></span>
              <span id="roleFinSelect-{{i}}"></span>
            </div>
          </div>
          <div class='block'>
            <span id="evaluationSelect-{{i}}"></span>
          </div>
        </div>
      `;


      const html = renderTemplate(feuilleTemplate, {
        i,
        id_feuille: feuille.id
      });
      $('#main').append(html);

      // Adding components to the template
      const components = {
        joueurSelect: Components.Select({
          label: 'Joueur',
          id: `joueur_${i}`,
          options: playersOptions,
          value: feuille.joueur.id,
        }),
        posteInput: Components.Input({
          label: 'Poste',
          id: `poste_${i}`,
          value: feuille.poste,
        }),
        roleDebutSelect: Components.Select({
          id: `role_debut_${i}`,
          options: roleOptions,
          value: feuille.role_debut,
        }),
        roleFinSelect: Components.Select({
          id: `role_fin_${i}`,
          options: roleOptions,
          value: feuille.role_fin,
        }),
        evaluationSelect: Components.Select({
          label: 'Évaluation',
          id: `evaluation_${i}`,
          options: evaluationOptions,
          value: feuille.evaluation,
        }),
        icon: Components.Icon({
          icon: 'arrowRight',
          class: 'shrink-0 size-5 text-neutral-600 dark:text-neutral-400',
        }),
      }
      Components.render(`#joueurSelect-${i}`, components.joueurSelect);
      Components.render(`#posteInput-${i}`, components.posteInput);
      Components.render(`#roleDebutSelect-${i}`, components.roleDebutSelect);
      Components.render(`#roleFinSelect-${i}`, components.roleFinSelect);
      Components.render(`#evaluationSelect-${i}`, components.evaluationSelect);
      Components.render(`#icon-${i}`, components.icon);
    }
  }

  function handleSubmit(e) {
    e.preventDefault();
    const data = {
      id: $('input[name="id"]').val(),
      equipe_adverse: $('input[name="equipe_adverse"]').val(),
      date_heure: $('input[name="date_heure"]').val(),
      lieu: $('input[name="lieu"]').val(),
      resultat: $('select[name="resultat"]').val(),
      feuille_match: [],
    };

    const feuilleMatch = [];
    for (let i = 0; i < 16; i++) {
      const id = $(`#id_feuille_${i}`).val();
      if (!id) {
        continue;
      }

      const joueur = $(`#joueur_${i}`).val();
      const poste = $(`#poste_${i}`).val();
      const roleDebut = $(`#role_debut_${i}`).val();
      const roleFin = $(`#role_fin_${i}`).val();
      const evaluation = $(`#evaluation_${i}`).val();

      data.feuille_match.push({
        id_rencontre: RENCONTRE_ID,
        id,
        id_joueur: joueur,
        poste,
        role_debut: roleDebut,
        role_fin: roleFin,
        evaluation,
      });
    }

    httpRequest('PATCH', `${BASE_GESTION_API_URL}/rencontre/index.php`, data)
      .then((e) => {
        console.log(e)
        // window.location.href = `/vue/dashboard/rencontre.php?id=${data.id}`;
      });

  }

  $(document).ready(async () => {
    if(!RENCONTRE_ID) window.history.back();
    buildUI();
    $('form').on('submit', handleSubmit);
    await populateFields();
  });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
