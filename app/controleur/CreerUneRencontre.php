<?php
require_once __DIR__ . '/../modele/Rencontre.php';
require_once __DIR__ . '/../modele/RencontreDAO.php';
require_once __DIR__ . '/../lib/error.php';

class CreerUneRencontre
{
  private $date_heure;
  private $equipe_adverse;
  private $lieu;
  private $DAO;

  public function __construct($date_heure, $equipe_adverse, $lieu)
  {
    $this->date_heure = $date_heure;
    $this->equipe_adverse = $equipe_adverse;
    $this->lieu = $lieu;
    $this->DAO = new RencontreDAO();
  }

  public function execute()
  {
    $rencontre = new Rencontre(
      $this->date_heure,
      $this->equipe_adverse,
      $this->lieu
    );
    $idRencontre = $this->DAO->insert($rencontre);
    $rencontre->setId($idRencontre);
    return $rencontre;
  }
}
?>
