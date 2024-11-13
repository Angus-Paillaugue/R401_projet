<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/error.php';
require_once __DIR__ . '/../../controleur/CreerUneRencontre.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

$title = 'Ajouter une rencontre';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $date_heure = $_POST['date_heure'];
  $equipe_adverse = $_POST['equipe_adverse'];
  $lieu = $_POST['lieu'];

  if (empty($date_heure) || empty($equipe_adverse) || empty($lieu)) {
    ErrorHandling::setError('Veuillez remplir tous les champs');
    header('Location: ' . $_SERVER['PHP_SELF']);
  }

  $creerUneRencontre = new CreerUneRencontre(
    $date_heure,
    $equipe_adverse,
    $lieu
  );
  $idRencontre = $creerUneRencontre->execute();

  header('Location: /dashboard/rencontre.php?id=' . $idRencontre);
}
?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) .
  '?' .
  http_build_query(
    $_GET
  ); ?>" class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-6 border-neutral-900">
  <h2>Ajouter une rencontre</h2>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      Components::Input([
        'label' => 'Équipe adverse',
        'id' => 'equipe_adverse',
      ]);
      Components::Input([
        'label' => 'Date et heure',
        'id' => 'date_heure',
        'type' => 'datetime-local',
      ]);
      Components::Select([
        'label' => 'Lieu',
        'id' => 'lieu',
        'options' => ['Domicile', 'Extérieur'],
      ]);
      if (ErrorHandling::hasError()) {
        Components::Alert([
          'text' => ErrorHandling::getError(),
          'class' => 'md:col-span-2',
        ]);
      }
    } ?>
  </div>

  <div class="grid grid-cols-2 gap-4">
    <?php
    Components::Button([
      'label' => 'Annuler',
      'variant' => 'danger',
      'type' => 'button',
      'href' => '/dashboard',
    ]);
    Components::Button([
      'label' => 'Ajouter',
      'type' => 'submit',
    ]);
    ?>
  </div>
</form>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
