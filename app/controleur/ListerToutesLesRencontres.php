<?php
require_once '../modele/RencontreDAO.php';

class ToutesLesRencontres {
  private $DAO;

  public function __construct() {
    $this->DAO = new RencontreDAO();
  }

  public function execute() {
   return array(
    'previous' => $this->DAO->getPrevious(),
    'next' => $this->DAO->getNext()
   );
  }
}
?>
