<?php
require_once __DIR__ . '/../modele/Rencontre.php';
require_once __DIR__ . '/../modele/RencontreDAO.php';
require_once __DIR__ . '/../lib/error.php';
require_once __DIR__ . '/RecupererUneRencontre.php';

class SupprimerUneRencontre
{
  private $DAO;
  private $joueur;

  public function __construct($joueur)
  {
    $this->joueur = $joueur;
    $this->DAO = new RencontreDAO();
  }

  public function execute()
  {
    $this->DAO->delete($this->joueur);
  }
}

if (!isset($_GET['id'])) {
  ErrorHandling::setError('ID de la rencontre non fourni');
  exit();
}

try {
  $joueur = (new RecupererUneRencontre($_GET['id']))->execute();
  (new SupprimerUneRencontre($joueur))->execute();

  header('Location: /vue/dashboard/');
  exit();
} catch (Exception $e) {
  ErrorHandling::setFatalError($e->getMessage());
}
?>
