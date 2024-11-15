<?php
require_once __DIR__ . '/../modele/User.php';
require_once __DIR__ . '/../modele/UserDAO.php';
require_once __DIR__ . '/../lib/cookies.php';
require_once __DIR__ . '/../lib/jwt.php';
require_once __DIR__ . '/../lib/error.php';
require_once __DIR__ . '/UtilisateurExiste.php';

class CreerUnUtilisateur
{
  private $username;
  private $password_hash;
  private $DAO;

  public function __construct($username, $password_hash)
  {
    $this->username = $username;
    $this->password_hash = $password_hash;
    $this->DAO = new UserDAO();
  }

  public function execute()
  {
    $user = new User($this->username, $this->password_hash);
    $insertedRowId = $this->DAO->insert($user);
    $user->setId($insertedRowId);
    return $user;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  if (empty($username) || empty($password)) {
    ErrorHandling::setError('Veuillez remplir tous les champs');
    header('Location: /vue/sign-up.php');
    exit();
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
        'username' => $username,
        'exp' => time() + 60 * 60 * 24, // Token expiration set to 1 day
      ];
      $jwt = JWT::generateJWT($payload);
      Cookies::setCookie('token', $jwt, time() + 60 * 60 * 24);
      header('Location: /vue/dashboard', true, 303);
      exit();
    }
  }
  header('Location: /vue/sign-up.php', true, 303);
  exit();
}
?>
