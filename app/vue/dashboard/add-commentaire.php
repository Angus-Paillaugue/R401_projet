<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/error.php';
require_once __DIR__ . '/../../controleur/RecupererUnJoueur.php';
require_once __DIR__ . '/../../controleur/CreerUnCommentaire.php';
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
$title = 'Ajouter un commentaire';

if (!isset($_GET['id'])) {
  ErrorHandling::setFatalError('ID du joueur non fourni');
}

try {
  $joueur = new RecupererUnJoueur($_GET['id']);
  $joueur = $joueur->execute();
} catch (Exception $e) {
  ErrorHandling::setFatalError($e->getMessage());
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $commentaire = $_POST['commentaire'];
  $ajoutCommentaire = new CreerUnCommentaire($joueur->getId(), $commentaire);
  $ajoutCommentaire->execute();
  header('Location: joueur.php?id=' . $joueur->getId(), true, 303);
}
?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) .
  '?' .
  http_build_query(
    $_GET
  ); ?>" class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-900">
  <h1>
    Ajouter un commentaire
  </h1>
  <textarea name="commentaire" class="w-full h-32 border border-neutral-900 rounded-lg p-2" placeholder="Entrez votre commentaire ici"></textarea>

  <?php Components::Button([
    'label' => 'Ajouter',
  ]); ?>
</form>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
