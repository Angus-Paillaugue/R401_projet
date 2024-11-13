<?php
require_once __DIR__ . '/../modele/JoueurDAO.php';

class ModifierUnJoueur
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
    return $this->DAO->update($this->joueur);
  }
}
?>
