<?php
session_start();
require_once '../../lib/connector.php';
require_once '../../lib/components.php';
require_once '../../lib/error.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = 'Sign up';

ob_start();

$conn = sql_connector::getInstance('auth_test', 'localhost', 'school', "\$iutinfo");
$loc = htmlspecialchars($_SERVER['PHP_SELF']) . '?' . http_build_query($_GET);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  if (empty($username) || empty($password)) {
    ErrorHandling::setError('Veuillez remplir tous les champs');
  } else {
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);
    $rows = $conn->run_query('SELECT * FROM users WHERE username=?;', $username);
    if (count($rows) > 0) {
      ErrorHandling::setError('Cet utilisateur existe déjà');
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $conn->run_query(
        'INSERT INTO users (username, password_hash) VALUES (?, ?);',
        $username,
        $hashed_password
      );
      header('Location: log-in.php', true, 303);
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
    <?php
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
    ?>
  </form>
</div>

<?php
$content = ob_get_clean();
require_once './layout.php';


?>
