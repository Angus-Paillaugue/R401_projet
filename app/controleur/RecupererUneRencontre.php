<?php
require_once '../modele/RencontreDAO.php';

class RecupererUneRequete {
  private $DAO;
  private $id;

  public function __construct($id) {
    $this->id = $id;
    $this->DAO = new RencontreDAO();
  }

  public function execute() {
    return $this->DAO->get($this->id);
  }
}
?>
