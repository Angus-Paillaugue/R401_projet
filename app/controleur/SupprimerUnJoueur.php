<?php
require_once __DIR__ . '/../modele/Joueur.php';
require_once __DIR__ . '/../modele/JoueurDAO.php';

class SupprimerUnJoueur
{
  private $DAO;
  private $joueur;

  public function __construct($joueur)
  {
    $this->joueur = $joueur;
    $this->DAO = new JoueurDAO();
  }

  public function execute()
  {
    $this->DAO->delete($this->joueur);
  }
}
?>
