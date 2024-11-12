<?php
require_once __DIR__ . '/../modele/RencontreDAO.php';

class ModifierUneRencontre
{
  private $DAO;
  private $rencontre;

  public function __construct($rencontre)
  {
    $this->rencontre = $rencontre;
    $this->DAO = new RencontreDAO();
  }

  public function execute()
  {
    return $this->DAO->update($this->rencontre);
  }
}
?>
