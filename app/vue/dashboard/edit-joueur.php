<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/error.php';
require_once __DIR__ . '/../../controleur/RecupererUnJoueur.php';
require_once __DIR__ . '/../../controleur/ModifierUnJoueur.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

$title = 'Modifier un joueur';

if (!isset($_GET['id'])) {
  ErrorHandling::setFatalError('ID du joueur non valide');
}

try {
  $joueur = (new RecupererUnJoueur($_GET['id']))->execute();
} catch (Exception $e) {
  ErrorHandling::setFatalError($e->getMessage());
}
?>

<form method="POST" action="/controleur/ModifierUnJoueur.php" class="container w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-300/50 dark:border-neutral-900">
  <h1>
    Modifier un joueur
  </h1>
  <input type="hidden" name="id" value="<?php echo $joueur->getId(); ?>" />
  <?php
  echo "<div class='grid grid-cols-1 md:grid-cols-2 gap-4'>";
  Components::Input([
    'id' => 'nom',
    'label' => 'Nom',
    'required' => true,
    'value' => $joueur->getNom(),
  ]);
  Components::Input([
    'id' => 'prenom',
    'label' => 'Prénom',
    'required' => true,
    'value' => $joueur->getPrenom(),
  ]);
  echo '</div>';

  echo "<div class='grid grid-cols-1 md:grid-cols-2 gap-4'>";
  Components::Input([
    'id' => 'licence',
    'label' => 'Numéro de licence',
    'required' => true,
    'value' => $joueur->getNumeroLicence(),
  ]);
  Components::Input([
    'id' => 'date_de_naissance',
    'label' => 'Date de naissance',
    'type' => 'date',
    'required' => true,
    'value' => $joueur->getDateNaissance(),
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
    'value' => $joueur->getTaille(),
  ]);
  Components::Input([
    'id' => 'poids',
    'label' => 'Poids',
    'type' => 'number',
    'required' => true,
    'min' => 0,
    'step' => 0.01,
    'max' => 300,
    'value' => $joueur->getPoids(),
  ]);
  echo '</div>';

  if (ErrorHandling::hasError()) {
    Components::Alert([
      'text' => ErrorHandling::getError(),
    ]);
  }

  Components::Button([
    'label' => 'Modifier',
    'type' => 'submit',
    'class' => 'ml-auto',
  ]);
  ?>
</form>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
