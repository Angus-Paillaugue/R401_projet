<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/error.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

$title = 'Ajouter un joueur';
?>

<form method="POST" action="/controleur/CreerUnJoueur.php" class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900">
  <h1>
    Ajouter un joueur
  </h1>
  <?php
  echo "<div class='grid grid-cols-1 md:grid-cols-2 gap-4'>";
  Components::Input([
    'id' => 'nom',
    'label' => 'Nom',
    'required' => true,
  ]);
  Components::Input([
    'id' => 'prenom',
    'label' => 'Prénom',
    'required' => true,
  ]);
  echo '</div>';

  echo "<div class='grid grid-cols-1 md:grid-cols-2 gap-4'>";
  Components::Input([
    'id' => 'licence',
    'label' => 'Numéro de licence',
    'required' => true,
  ]);
  Components::Input([
    'id' => 'date_de_naissance',
    'label' => 'Date de naissance',
    'type' => 'date',
    'required' => true,
  ]);
  echo '</div>';

  echo "<div class='grid grid-cols-1 md:grid-cols-2 gap-4'>";
  Components::Input([
    'id' => 'taille',
    'label' => 'Taille',
    'type' => 'number',
    'required' => true,
    'min' => 0,
    'step' => 0.01,
    'max' => 3,
  ]);
  Components::Input([
    'id' => 'poids',
    'label' => 'Poids',
    'type' => 'number',
    'required' => true,
    'min' => 0,
    'step' => 0.01,
    'max' => 300,
  ]);
  echo '</div>';

  if (ErrorHandling::hasError()) {
    Components::Alert([
      'text' => ErrorHandling::getError(),
    ]);
  }

  Components::Button([
    'label' => 'Ajouter',
    'type' => 'submit',
    'class' => 'ml-auto',
  ]);
  ?>
</form>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
