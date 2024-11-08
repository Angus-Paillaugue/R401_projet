<?php
require_once __DIR__ . '/../modele/RencontreDAO.php';

class ToutesLesRencontres
{
  private $DAO;
  private $limit;

  public function __construct($limit)
  {
    $this->DAO = new RencontreDAO();
    $this->limit = $limit;
  }

  public function execute()
  {
    if (isset($this->limit)) {
      return [
        'previous' => $this->DAO->getPrevious($this->limit),
        'next' => $this->DAO->getNext($this->limit),
      ];
    }
    return [
      'previous' => $this->DAO->getPrevious(null),
      'next' => $this->DAO->getNext(null),
    ];
  }
}
?>
