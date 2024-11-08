<?php
require_once '../modele/Joueur.php';
require_once '../modele/JoueurDAO.php';

class SupprimerUnJoueur {
  private $DAO;
  private $joueur;

  public function __construct($joueur) {
    $this->joueur = $joueur;
    $this->DAO = new JoueurDAO();
  }

  public function exec() {
    $this->DAO->delete($this->joueur);
  }
}
?>
