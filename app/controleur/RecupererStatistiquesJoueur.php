<?php
require_once __DIR__ . '/../modele/JoueurDAO.php';

class RecupererStatistiquesJoueur
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
    $row = $this->DAO->getStatistics($this->joueur);

    return $row;
  }
}
?>
