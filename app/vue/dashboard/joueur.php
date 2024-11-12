<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../controleur/RecupererUnJoueur.php';
require_once __DIR__ . '/../../lib/formatters.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

$jwt = Cookies::getCookie('token');
$payload = null;

if ($jwt) {
  $payload = JWT::validateJWT($jwt);
  if (!$payload) {
    header('Location: log-in.php', true, 303);
  }
} else {
  header('Location: log-in.php', true, 303);
}
$title = 'Joueur';

if (!isset($_GET['id'])) {
  throw new Exception('ID du joueur non fourni');
}

$joueur = new RecupererUnJoueur($_GET['id']);
$joueur = $joueur->execute();
?>

<div class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50">
  <div class="flex flex-row items-center justify-between">
    <h1>
      <?php echo $joueur->getNom() . ' ' . $joueur->getPrenom(); ?>
    </h1>
    <?php Components::Button([
      'label' => 'Modifier',
      'class' => 'bg-primary-500 hover:bg-primary-600 text-white',
      'href' => '/dashboard/edit-joueur.php?id=' . $joueur->getId(),
    ]); ?>
  </div>
  <p class="text-neutral-600 text-base">
    Licence : <span class="font-semibold font-mono"><?php echo $joueur->getNumeroLicence(); ?></span>
  </p>
  <div class="flex flex-row gap-8 flex-wrap">
    <div class="flex flex-row gap-2 items-center px-4 py-2 border rounded-lg w-fit border-neutral-300/50">
      <span class="text-neutral-600"><?php echo Components::Icon([
        'icon' => 'birthday',
        'class' => 'size-5',
      ]); ?></span>
      <time><?php echo formatters::formatDate(
        $joueur->getDateNaissance()
      ); ?></time>
    </div>
    <div class="flex flex-row gap-2 items-center px-4 py-2 border rounded-lg w-fit border-neutral-300/50">
      <span class="text-neutral-600"><?php echo Components::Icon([
        'icon' => 'weight',
        'class' => 'size-5',
      ]); ?></span>
      <span><?php echo Formatters::formatWeight($joueur->getPoids()); ?></span>
    </div>
    <div class="flex flex-row gap-2 items-center px-4 py-2 border rounded-lg w-fit border-neutral-300/50">
      <span class="text-neutral-600"><?php echo Components::Icon([
        'icon' => 'status',
        'class' => 'size-5',
      ]); ?></span>
      <span><?php echo $joueur->getStatut(); ?></span>
    </div>
  </div>
  <?php if (count($joueur->getCommentaires()) == 0) {
    echo "<p class='text-neutral-600'>Aucun commentaire,";
    Components::Link([
      'label' => 'en ajouter',
      'href' => '/dashboard/add-commentaire.php?id=' . $joueur->getId(),
    ]);
    echo '</p>';
  } else {
    echo '<div class="flex flex-row justify-between items-center"><h3>Commentaires</h3>';
    Components::Button([
      'label' => 'Ajouter un commentaire',
      'href' => '/dashboard/add-commentaire.php?id=' . $joueur->getId(),
      'class' => 'w-fit',
    ]);
    echo '</div>';
    foreach ($joueur->getCommentaires() as $commentaire) {
      echo "<div class='bg-neutral-50 p-4 rounded-lg border border-neutral-300/50 group/comment relative overflow-hidden'>";
      echo "<p class='text-base'>" . $commentaire->getContenu() . '</p>';
      echo "<time class='text-sm text-neutral-600'>" .
        Formatters::formatDateTime($commentaire->getDate()) .
        '</time>';
      echo "<div class='opacity-0 transition-opacity group-hover/comment:opacity-100 absolute top-0 right-0 bottom-0 grid grid-cols-1 gris-rows-2'>";
      Components::Button([
        'label' => 'Modifier',
        'class' => 'rounded-none',
        'href' => '/dashboard/edit-commentaire.php?id=' . $commentaire->getId(),
      ]);
      Components::Button([
        'label' => 'Supprimer',
        'variant' => 'danger',
        'class' => 'rounded-none',
        'href' =>
          '/dashboard/delete-commentaire.php?id=' .
          $commentaire->getId() .
          '&redirect=' .
          urlencode($_SERVER['REQUEST_URI']),
      ]);
      echo '</div>';
      echo '</div>';
    }
  } ?>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
