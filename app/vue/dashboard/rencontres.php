<?php
session_start();
ob_start();

$title = 'Rencontres';
?>

<div class='container w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900'>
  <div class='flex flex-row items-center justify-between'>
    <h2>Toutes les rencontres</h2>
    <div class='flex flex-row gap-2 items-center' id='filters'></div>
  </div>
  <div id="rencontresPrecedentes">
    <h3>Rencontres précédentes</h3>
    <div class='list grid grid-cols-1 lg:grid-cols-2 gap-4'></div>
  </div>

  <div id="rencontresSuivantes">
    <h3>Rencontres à venir</h3>
    <div class='list grid grid-cols-1 lg:grid-cols-2 gap-4'></div>
  </div>
</div>

<script type="module">
  import { httpRequest } from '/vue/js/http.js';
  import { renderTemplate } from '/vue/js/html.js';
  import Components from '/vue/js/components.js';

  const searchParams = new URLSearchParams(window.location.search);

  let showNext = searchParams.has('next');
  let showPrevious = searchParams.has('previous');
  if(!showNext && !showPrevious) {
    showNext = true;
    showPrevious = true;
  }

  function setURLParams() {
    const searchParams = new URLSearchParams();
    if(showNext) {
      searchParams.set('next', '');
    }
    if(showPrevious) {
      searchParams.set('previous', '');
    }
    window.history.replaceState({}, '', `${window.location.pathname}?${searchParams}`);
  }

  const rencontres = (await httpRequest('GET', '/api/rencontre')).data;

  async function addRencontres(previous = true, next = true) {
    setURLParams();

    const template = `
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

    $("#rencontresPrecedentes > .list").empty();
    $("#rencontresSuivantes > .list").empty();

    if(previous) {
      $("#rencontresPrecedentes").removeClass('hidden');
      for(const previous of rencontres.previous) {
        const pillBg =
          previous.resultat === 'Nul'
            ? 'bg-neutral-600'
            : (previous.resultat == 'Victoire'
              ? 'bg-green-600'
              : 'bg-red-600');
        // Affichage du badge si le résultat est défini
        const pill = previous.resultat ? `<div class='px-2 py-1 text-base rounded-full text-neutral-100 font-semibold ${pillBg}'>${previous.resultat}</div>` : ' ';
        $("#rencontresPrecedentes > .list").append(renderTemplate(template, {
          id: previous.id,
          equipe_adverse: previous.equipe_adverse,
          date_heure: new Date(previous.date_heure).toLocaleString(),
          lieu: previous.lieu,
          pill
        }));
      }
    }else {
      $("#rencontresPrecedentes").addClass('hidden');
    }

    if(next) {
      $("#rencontresSuivantes").removeClass('hidden');
      for(const next of rencontres.next) {
        $("#rencontresSuivantes > .list").append(renderTemplate(template, {
          id: next.id,
          equipe_adverse: next.equipe_adverse,
          date_heure: new Date(next.date_heure).toLocaleString(),
          lieu: next.lieu,
          pill: ' '
        }));
      }
    }else {
      $("#rencontresSuivantes").addClass('hidden');
    }
  }

  function addFiltersLinks() {
    const filters = [
      { label: 'Précédentes', id: "filter-previous" },
      { label: 'Toutes', id: "filter-all" },
      { label: 'À venir', id: "filter-next" }
    ];

    const template = `
      <span id="{{id}}" class='cursor-pointer font-medium items-center justify-center inline-flex flex-row gap-2 text-neutral-700 hover:text-neutral-900 dark:text-neutral-100 dark:hover:text-neutral-400 underline transition-colors active:scale-95'>
        {{label}}
      </span>`;

    for(const filter of filters) {
      $("#filters").append(renderTemplate(template, filter));
      $(`#${filter.id}`).click(() => {
        showPrevious = filter.id === 'filter-previous' || filter.id === 'filter-all';
        showNext = filter.id === 'filter-next' || filter.id === 'filter-all';
        addRencontres(showPrevious, showNext);
      });
    }
  }

  $(document).ready(() => {
    addRencontres(showPrevious, showNext);
    addFiltersLinks();
  });

</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
