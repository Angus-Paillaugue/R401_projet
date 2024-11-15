<?php
require_once __DIR__ . '/UtilisateurExiste.php';
require_once __DIR__ . '/../lib/error.php';
require_once __DIR__ . '/../lib/jwt.php';
require_once __DIR__ . '/../lib/cookies.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  if (empty($username) || empty($password)) {
    ErrorHandling::setError('Veuillez remplir tous les champs');
  } else {
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);
    $userExists = new UtilisateurExiste($username);
    $user = $userExists->execute();
    if (count($user) == 0) {
      ErrorHandling::setError("Cet utilisateur n'existe pas");
    } else {
      $user = $user[0];
      if (password_verify($password, $user->getPasswordHash())) {
        $payload = [
          'id' => $user->getId(),
          'username' => $user->getUsername(),
          'exp' => time() + 60 * 60 * 24, // Token expiration set to 1 day
        ];
        $jwt = JWT::generateJWT($payload);
        Cookies::setCookie('token', $jwt, time() + 60 * 60 * 24);
        header('Location: /vue/dashboard', true, 303);
        exit();
      } else {
        ErrorHandling::setError('Mot de passe incorrect');
      }
    }
  }
  header('Location: /vue/log-in/php', true, 303);
  exit();
}
?>
