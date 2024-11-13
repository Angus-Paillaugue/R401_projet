<?php
require_once __DIR__ . '/../modele/RencontreDAO.php';

class RecupererStatistiquesClub
{
  private $DAORencontre;

  public function __construct()
  {
    $this->DAORencontre = new RencontreDAO();
  }

  public function execute()
  {
    $row = $this->DAORencontre->getStatistics();

    return $row;
  }
}
?>
