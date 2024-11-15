<?php
require_once __DIR__ . '/../modele/CommentaireDAO.php';
require_once __DIR__ . '/../lib/error.php';
require_once __DIR__ . '/RecupererUnCommentaire.php';
require_once __DIR__ . '/ModifierUnCommentaire.php';
require_once __DIR__ . '/RecupererUnJoueur.php';
require_once __DIR__ . '/../modele/Commentaire.php';

class ModifierUnCommentaire
{
  private $commentaire;
  private $DAO;

  public function __construct($commentaire)
  {
    $this->DAO = new CommentaireDAO();
    $this->commentaire = $commentaire;
  }

  public function execute()
  {
    return $this->DAO->update($this->commentaire);
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!isset($_POST['id'])) {
    ErrorHandling::setFatalError('ID du commentaire non fourni');
  }

  try {
    $commentaire = (new RecupererUnCommentaire($_POST['id']))->execute();
  } catch (Exception $e) {
    ErrorHandling::setFatalError($e->getMessage());
  }

  $joueur = new RecupererUnJoueur($commentaire->getIdJoueur());
  $joueur = $joueur->execute();

  $commentaire = new Commentaire($joueur->getId(), $_POST['commentaire']);
  $commentaire->setId($_GET['id']);
  (new ModifierUnCommentaire($commentaire))->execute();
  header(
    'Location: /vue/dashboard/joueur.php?id=' . $joueur->getId(),
    true,
    303
  );
  exit();
}
?>
