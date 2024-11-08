<?php
require_once '../modele/User.php';
require_once '../modele/UserDAO.php';

class CreerUnUtilisateur {
  private $username;
  private $password_hash;
  private $DAO;

  public function __construct($username, $password_hash) {
    $this->username = $username;
    $this->password_hash = $password_hash;
    $this->DAO = new UserDAO();
  }

  public function execute() {
    $user = new User($this->username, $this->password_hash);
    $insertedRow = $this->DAO->insert($user);
    $user->setId($insertedRow['id']);
    return $user;
  }
}
?>
