<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../lib/jwt.php';
require_once __DIR__ . '/../lib/cookies.php';
require_once __DIR__ . '/../lib/components.php';
require_once __DIR__ . '/../lib/error.php';

$title = 'Sign up';
$loc = htmlspecialchars($_SERVER['PHP_SELF']) . '?' . http_build_query($_GET);

$jwt = Cookies::getCookie('token');
$payload = null;

if ($jwt) {
  $payload = JWT::validateJWT($jwt);
  if ($payload) {
    header('Location: /vue/dashboard', true, 303);
    exit();
  }
}

ob_start();
?>
<div class="max-w-xl mx-auto w-full p-4">
  <form action="/controleur/CreerUnUtilisateur.php" method="post" class="p-4 space-y-4 flex flex-col rounded-xl w-full bg-neutral-100 dark:bg-neutral-800">
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
      Components::Alert([
        'text' => ErrorHandling::getError(),
        'variant' => 'danger',
      ]);
    }
    Components::Button([
      'label' => 'Créer',
      'variant' => 'primary',
    ]);
    Components::Link([
      'label' => 'Se connecter',
      'href' => '/vue/log-in.php',
    ]);
    ?>
  </form>
</div>

<?php
$content = ob_get_clean();
require_once './layout.php';


?>
