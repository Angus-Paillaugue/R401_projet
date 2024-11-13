<?php
session_start();
require_once __DIR__ . '/../../lib/components.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/cookies.php';
require_once __DIR__ . '/../../lib/error.php';
require_once __DIR__ . '/../../controleur/RecupererUnCommentaire.php';
require_once __DIR__ . '/../../controleur/ModifierUnCommentaire.php';
require_once __DIR__ . '/../../controleur/RecupererUnJoueur.php';
require_once __DIR__ . '/../../modele/Commentaire.php';
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
$title = 'Modifier un commentaire';

if (!isset($_GET['id'])) {
  ErrorHandling::setFatalError('ID du commentaire non fourni');
}

try {
  $commentaire = (new RecupererUnCommentaire($_GET['id']))->execute();
} catch (Exception $e) {
  ErrorHandling::setFatalError($e->getMessage());
}

$joueur = new RecupererUnJoueur($commentaire->getIdJoueur());
$joueur = $joueur->execute();

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $commentaire = new Commentaire($joueur->getId(), $_POST['commentaire']);
  $commentaire->setId($_GET['id']);
  (new ModifierUnCommentaire($commentaire))->execute();
  header('Location: joueur.php?id=' . $joueur->getId(), true, 303);
}
?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) .
  '?' .
  http_build_query(
    $_GET
  ); ?>" class="max-w-screen-xl w-full mx-auto p-4 rounded-xl border space-y-4 border-neutral-900">
  <h1>
    Modifier un commentaire
  </h1>
  <textarea name="commentaire" id="commentaire" class="w-full h-32 border border-neutral-900 rounded-lg p-2" placeholder="Entrez votre commentaire ici"><?php echo $commentaire->getContenu(); ?></textarea>

  <?php Components::Button([
    'label' => 'Enregistrer',
  ]); ?>
</form>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout.php';


?>
