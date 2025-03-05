<?php
ob_start();

$title = 'Rencontre';
?>

<div class="container w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900">
  <div class="flex flex-row items-center justify-between">
    <h2>Rencontre</h2>
    <div class="flex flex-row gap-4">
      <div id="modifierButton"></div>
      <div id="deleteRencontreButton"></div>
    </div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4" id="rencontreFields">
    <div class="bg-neutral-100 dark:bg-neutral-900 p-4 rounded-xl border border-neutral-300/50 dark:border-neutral-900">
      <h4 class="text-2xl font-semibold" id="equipeAdverse"></h4>
      <time class="text-base text-neutral-600 dark:text-neutral-400 font-base" id="dateHeure"></time>
      <div class="text-neutral-600 dark:text-neutral-400 text-lg font-semibold flex flex-row items-center gap-2">
        <span id="lieuIcon"></span>
        <span id="lieu"></span>
      </div>
    </div>
    </div>
  </div>
</div>

<script type="module">
  import { httpRequest } from '/vue/js/http.js';
  import { renderTemplate } from '/vue/js/html.js';
  import Components from '/vue/js/components.js';
  import { BASE_GESTION_API_URL } from '/vue/js/constants.js';

  const ID_RENCONTRE = new URLSearchParams(window.location.search).get('id');

  function buildUI() {
    Components.render('#modifierButton', Components.Button({
      label: 'Modifier',
      href: `/vue/dashboard/edit-rencontre.php?id=${ID_RENCONTRE}`,
    }), true);

    Components.render('#deleteRencontreButton', Components.Button({
      icon: 'trash',
      variant: 'danger square',
      class: 'p-3',
      id: 'deleteRencontre',
    }), true);

    $('#deleteRencontre').on('click', async() => {
      const res = await httpRequest("DELETE", `${BASE_GESTION_API_URL}/rencontre/index.php?id=${ID_RENCONTRE}`);
      if(res.status_code === 200) {
        window.location.href = '/vue/dashboard/rencontres.php';
      }
    });
  }

  async function populateFields() {
    let rencontre;
    try {
      const res = await httpRequest("GET", `${BASE_GESTION_API_URL}/rencontre/index.php?id=${ID_RENCONTRE}`);
      rencontre = res.data;
    } catch(e) {
      window.location.href = '/vue/dashboard/rencontres.php';
    }

    $('#equipeAdverse').text(rencontre.equipe_adverse);
    $('#dateHeure').text(rencontre.date_heure);
    $('#lieu').text(rencontre.lieu);
    $('#lieuIcon').html(Components.Icon({
      icon: rencontre.lieu,
      class: 'size-4',
    }));

    const isInPast = new Date(rencontre.date_heure) < new Date();
    const feuilleMatch = rencontre.feuille_match;
    if(isInPast) {
      if(feuilleMatch?.length > 0) {
        const feulleMatchTemplate = `
          <div class="bg-neutral-100 dark:bg-neutral-900 p-4 lg:col-span-2 rounded-xl border border-neutral-300/50 dark:border-neutral-900">
            <h4 class="text-2xl font-semibold">Feuille de match</h4>
            <div class="border border-neutral-300/50 dark:border-neutral-900 block rounded-xl max-h-[500px] overflow-auto">
              <table class="w-full table-auto text-sm">
                <thead class="sticky top-0 bg-neutral-200 dark:bg-neutral-800">
                  <tr>
                    <th scope="col" class="sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600 dark:text-neutral-400">
                      Joueur
                    </th>
                    <th scope="col" class="sticky top-0 h-12 px-4 text-left align-middle font-medium text-neutral-600 dark:text-neutral-400">
                      Role
                    </th>
                  </tr>
                </thead>
                <tbody>
                  ${feuilleMatch.map(feuille => `
                    <tr class="transition-colors even:bg-neutral-300 dark:even:bg-neutral-800">
                      <td class="w-1/12 px-4 align-middle">
                        <a href="/vue/dashboard/joueur.php?id=${feuille.joueur.id}" class="text-base font-medium">${feuille.joueur.nom} ${feuille.joueur.prenom}</a>
                      </td>
                      <td class="w-1/12 px-4 align-middle">
                        <div class="flex flex-row items-center gap-2">
                          ${feuille.role_debut !== feuille.role_fin ? feuille.role_debut + `<span class="arrowRight"></span>` +feuille.role_fin : feuille.role_debut}
                        </div>
                      </td>
                    </tr>
                  `).join('')}
                </tbody>
              </table>
            </div>
          </div>
        `;

        $('#rencontreFields').append(feulleMatchTemplate);
        // Add arrows to indicate role change
        $('.arrowRight').each(function() {
          Components.render(this, Components.Icon({ icon: 'arrowRight', class: 'size-4' }), true);
        });
      }

    }else {
      // There are no feuille de match
      $('#rencontreFields').append(`
        <div class="bg-neutral-100 dark:bg-neutral-900 p-4 lg:col-span-2 space-y-2 rounded-xl border border-neutral-300/50 dark:border-neutral-900">
          <h4 class="text-2xl font-semibold">Feuille de match</h4>
          <p class="text-lg text-neutral-600 dark:text-neutral-400">Aucune feuille de match n'a été renseignée pour cette rencontre.</p>
          <span id="addFeulleMatch"></span>
        </div>
      `);

      Components.render('#addFeulleMatch', Components.Button({
        label: 'Ajouter une feuille de match',
        href: `/vue/dashboard/edit-rencontre.php?id=${ID_RENCONTRE}`,
      }), true);
    }
  }

  $(document).ready(async() => {
    // If no ID_RENCONTRE is provided, go back
    if(!ID_RENCONTRE)
      window.history.back();

    buildUI();
    await populateFields();
  });
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
