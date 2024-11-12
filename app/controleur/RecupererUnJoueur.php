<?php
require_once __DIR__ . '/../modele/JoueurDAO.php';

class RecupererUnJoueur
{
  private $DAO;
  private $id;

  public function __construct($id)
  {
    $this->id = $id;
    $this->DAO = new JoueurDAO();
  }

  public function execute()
  {
    return $this->DAO->get($this->id);
  }
}
?>
