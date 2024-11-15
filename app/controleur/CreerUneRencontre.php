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
    return $idRencontre;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (
    empty($_POST['date_heure']) ||
    empty($_POST['equipe_adverse']) ||
    empty($_POST['lieu'])
  ) {
    ErrorHandling::setError('Veuillez remplir tous les champs');
    header('Location: /vue/dashboard/add-rencontre.php', true, 303);
    exit();
  }

  $date_heure = $_POST['date_heure'];
  $equipe_adverse = $_POST['equipe_adverse'];
  $lieu = $_POST['lieu'];

  $creerUneRencontre = new CreerUneRencontre(
    $date_heure,
    $equipe_adverse,
    $lieu
  );
  $idRencontre = $creerUneRencontre->execute();

  header('Location: /vue/dashboard/rencontre.php?id=' . $idRencontre);
  exit();
}
?>
