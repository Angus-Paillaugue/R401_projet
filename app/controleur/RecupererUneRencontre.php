<?php
require_once __DIR__ . '/../modele/RencontreDAO.php';

class RecupererUneRencontre
{
  private $DAO;
  private $id;

  public function __construct($id)
  {
    $this->id = $id;
    $this->DAO = new RencontreDAO();
  }

  public function execute()
  {
    return $this->DAO->get($this->id);
  }
}
?>
