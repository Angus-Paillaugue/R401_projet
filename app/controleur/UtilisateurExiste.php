<?php
require_once '../modele/UserDAO.php';

class UtilisateurExiste {
  private $username;
  private $DAO;

  public function __construct($username) {
    $this->username = $username;
    $this->DAO = new UserDAO();
  }

  public function execute() {
    $user = $this->DAO->search($this->username);
    return $user;
  }
}
?>
