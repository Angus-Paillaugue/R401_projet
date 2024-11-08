<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../lib/connector.php';
require_once '../../lib/jwt.php';
require_once '../../lib/cookies.php';
require_once '../../lib/components.php';
require_once '../../lib/error.php';

$title = 'Log-in';
$conn = sql_connector::getInstance('auth_test', 'localhost', 'school', "\$iutinfo");
$loc = htmlspecialchars($_SERVER['PHP_SELF']) . '?' . http_build_query($_GET);

ob_start();

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $lastName = $_POST['last_name'];
  $password = $_POST['password'];
  if (empty($lastName) || empty($password)) {
    ErrorHandling::setError('Veuillez remplir tous les champs');
  } else {
    $lastName = htmlspecialchars($lastName);
    $password = htmlspecialchars($password);
    $user = $conn->run_query('SELECT * FROM users WHERE last_name=?;', $lastName);
    if (count($user) == 0) {
      ErrorHandling::setError("Cet utilisateur n'existe pas");
    } else {
      $user = $user[0];
      if (password_verify($password, $user['password_hash'])) {
        $payload = [
          'id' => $user['id'],
          'last_name  ' => $user['lastName'],
          'exp' => time() + 60 * 60 * 24, // Token expiration set to 1 day
        ];
        $jwt = JWT::generateJWT($payload);
        Cookies::setCookie('token', $jwt, time() + 60 * 60 * 24);
      } else {
        ErrorHandling::setError('Mot de passe incorrect');
      }
    }
  }
  header("Location: $loc", true, 303);
}
?>

<div class="max-w-xl mx-auto w-full p-4">
	<form action="<?php echo htmlspecialchars(
   $_SERVER['PHP_SELF']
 ); ?>" method="post" class="p-4 border space-y-4 flex flex-col rounded-xl w-full bg-white">
		<h2 class="m-0">Se connecter</h2>
		<?php if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Form building
    Components::Input([
      'id' => 'last_name',
      'label' => 'Nom de famille',
      'placeholder' => "Votre nom de famille",
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
      'label' => 'Créer un compte',
      'href' => 'sign-up.php',
    ]);
  } ?>
	</form>
</div>

<?php
$content = ob_get_clean();
require_once './layout.php';

?>
