<?php
require_once __DIR__ . '/../lib/error.php';
require_once __DIR__ . '/../modele/Commentaire.php';
require_once __DIR__ . '/../modele/CommentaireDAO.php';
require_once __DIR__ . '/RecupererUnCommentaire.php';

class SupprimerUnCommentaire
{
  private $DAO;
  private $id;

  public function __construct($id)
  {
    $this->id = $id;
    $this->DAO = new CommentaireDAO();
  }

  public function execute()
  {
    $this->DAO->delete($this->id);
  }
}

// if (!isset($_GET['id'])) {
//   ErrorHandling::setFatalError('ID du commentaire non fourni');
// }

// if (!intval($_GET['id'])) {
//   ErrorHandling::setFatalError('ID du commentaire non valide');
// }

// try {
//   $commentaire = (new RecupererUnCommentaire($_GET['id']))->execute();
// } catch (Exception $e) {
//   ErrorHandling::setFatalError($e->getMessage());
// }

// (new SupprimerUnCommentaire($commentaire))->execute();

// $redirect = urldecode($_GET['redirect']) ?? '/vue/dashboard';

// header('Location: ' . $redirect);
// exit();

?>
