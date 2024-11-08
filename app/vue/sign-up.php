<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../lib/jwt.php';
require_once '../../lib/cookies.php';
require_once '../../lib/components.php';
require_once '../../lib/error.php';
require_once '../controleur/CreerUnUtilisateur.php';
require_once '../controleur/UtilisateurExiste.php';

$title = 'Sign up';
$loc = htmlspecialchars($_SERVER['PHP_SELF']) . '?' . http_build_query($_GET);

ob_start();

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  if (empty($username) || empty($password)) {
    ErrorHandling::setError('Veuillez remplir tous les champs');
  } else {
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);
    $userExists = new UtilisateurExiste($username);
    if ($userExists->execute()) {
      ErrorHandling::setError('Ce nom d\'utilisateur existe déjà');
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $createdUser = new CreerUnUtilisateur($username, $hashed_password);
      $createdUser->execute();
      $payload = [
        'id' => $createdUser,
        'username  ' => $username,
        'exp' => time() + 60 * 60 * 24, // Token expiration set to 1 day
      ];
      $jwt = JWT::generateJWT($payload);
      Cookies::setCookie('token', $jwt, time() + 60 * 60 * 24);
      header("Location: dashboard.php", true, 303);
    }
  }
  header("Location: $loc", true, 303);
}
?>
<div class="max-w-xl mx-auto w-full p-4">
  <form action="<?php echo htmlspecialchars(
    $_SERVER['PHP_SELF']
  ); ?>" method="post" class="p-4 border space-y-4 flex flex-col rounded-xl w-full bg-white">
    <h2 class="m-0">Créer un compte</h2>
    <?php if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    Components::Input([
      'id' => 'username',
      'label' => 'Nom d\'utilisateur',
      'placeholder' => "Votre nom d'utilisateur",
    ]);
    Components::Input([
      'id' => 'password',
      'label' => 'Mot de passe',
      'placeholder' => 'Votre mot de passe',
      'type' => 'password',
    ]);
    if (ErrorHandling::hasError()) {
      Components::Alert(['text' => ErrorHandling::getError(), 'variant' => 'danger']);
    }
    Components::Button([
      'label' => 'Envoyer',
      'variant' => 'primary',
    ]);
    Components::Link([
      'label' => 'Se connecter',
      'href' => 'log-in.php',
    ]);
  }
    ?>
  </form>
</div>

<?php
$content = ob_get_clean();
require_once './layout.php';


?>
