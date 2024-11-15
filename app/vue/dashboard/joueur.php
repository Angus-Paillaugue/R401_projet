<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/error.php';
require_once __DIR__ . '/../../controleur/RecupererUnJoueur.php';
require_once __DIR__ . '/../../controleur/RecupererStatistiquesJoueur.php';
require_once __DIR__ . '/../../lib/formatters.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

$title = 'Joueur';

if (!isset($_GET['id'])) {
  throw new Exception('ID du joueur non fourni');
}

try {
  $joueur = (new RecupererUnJoueur($_GET['id']))->execute();

  $statistiques = (new RecupererStatistiquesJoueur($joueur))->execute();
} catch (Exception $e) {
  ErrorHandling::setFatalError($e->getMessage());
}
?>

<div class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900">
  <div class="flex flex-row items-center justify-between">
    <h1>
      <?php echo $joueur->getNom() . ' ' . $joueur->getPrenom(); ?>
    </h1>
    <div class="flex flex-row gap-4">
      <?php
      Components::Button([
        'label' => 'Modifier',
        'href' => '/vue/dashboard/edit-joueur.php?id=' . $joueur->getId(),
      ]);
      Components::Button([
        'icon' => 'trash',
        'variant' => 'danger square',
        'class' => 'p-3',
        'href' => '/controleur/SupprimerUnJoueur.php?id=' . $joueur->getId(),
      ]);
      ?>
    </div>
  </div>
  <p class="text-neutral-600 dark:text-neutral-400 text-base">
    Licence : <span class="font-semibold font-mono"><?php echo $joueur->getNumeroLicence(); ?></span>
  </p>
  <div class="flex flex-row gap-8 flex-wrap">
    <!-- Anniversaire -->
    <div class="flex flex-row gap-2 items-center px-4 py-2 border rounded-lg w-fit border-neutral-300/50 dark:border-neutral-900">
      <span class="text-neutral-600 dark:text-neutral-400"><?php echo Components::Icon(
        [
          'icon' => 'birthday',
          'class' => 'size-5',
        ]
      ); ?></span>
      <time><?php echo formatters::formatDate(
        $joueur->getDateNaissance()
      ); ?></time>
    </div>

    <!-- Poids -->
    <div class="flex flex-row gap-2 items-center px-4 py-2 border rounded-lg w-fit border-neutral-300/50 dark:border-neutral-900">
      <span class="text-neutral-600 dark:text-neutral-400"><?php echo Components::Icon(
        [
          'icon' => 'weight',
          'class' => 'size-5',
        ]
      ); ?></span>
      <span><?php echo Formatters::formatWeight($joueur->getPoids()); ?></span>
    </div>

    <!-- Status -->
    <div class="flex flex-row gap-2 items-center px-4 py-2 border rounded-lg w-fit border-neutral-300/50 dark:border-neutral-900">
      <span class="text-neutral-600 dark:text-neutral-400"><?php echo Components::Icon(
        [
          'icon' => 'status',
          'class' => 'size-5',
        ]
      ); ?></span>
      <span><?php echo $joueur->getStatut(); ?></span>
    </div>

    <!-- Poste -->
    <div class="flex flex-row gap-2 items-center px-4 py-2 border rounded-lg w-fit border-neutral-300/50 dark:border-neutral-900">
      <span class="text-neutral-600 dark:text-neutral-400"><?php echo Components::Icon(
        [
          'icon' => 'poste',
          'class' => 'size-5',
        ]
      ); ?></span>
      <span><?php echo $statistiques['poste']; ?></span>
    </div>
  </div>
  <?php if (count($joueur->getCommentaires()) == 0) {
    echo "<p class='text-neutral-600 dark:text-neutral-400'>Aucun commentaire,";
    Components::Link([
      'label' => 'en ajouter',
      'href' => '/vue/dashboard/add-commentaire.php?id=' . $joueur->getId(),
    ]);
    echo '</p>';
  } else {
    echo '<div class="flex flex-row justify-between items-center"><h3>Commentaires</h3>';
    Components::Button([
      'label' => 'Ajouter un commentaire',
      'href' => '/vue/dashboard/add-commentaire.php?id=' . $joueur->getId(),
      'class' => 'w-fit',
    ]);
    echo '</div>';
    foreach ($joueur->getCommentaires() as $commentaire): ?>
      <div class='bg-neutral-100 dark:bg-neutral-900 p-4 rounded-lg border border-neutral-300/50 dark:border-neutral-900 group/comment relative overflow-hidden'>
      <p class='text-base whitespace-pre-wrap'><?php echo $commentaire->getContenu(); ?></p>
      <time class='text-sm ttext-neutral-600 dark:ext-neutral-400'>
        <?php echo Formatters::formatDateTime($commentaire->getDate()); ?>
      </time>
      <div class='opacity-0 transition-opacity group-hover/comment:opacity-100 bottom-0 absolute top-0 right-0 flex flex-col justify-between'>
      <?php
      Components::Button([
        'label' => 'Modifier',
        'href' =>
          '/vue/dashboard/edit-commentaire.php?id=' . $commentaire->getId(),
      ]);
      Components::Button([
        'label' => 'Supprimer',
        'variant' => 'danger',
        'href' =>
          '/controleur/SupprimerUnCommentaire.php?id=' .
          $commentaire->getId() .
          '&redirect=' .
          urlencode($_SERVER['REQUEST_URI']),
      ]);
      ?>
      </div>
      </div>
      <?php endforeach;
  } ?>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
