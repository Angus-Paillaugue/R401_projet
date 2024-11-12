<?php
require_once __DIR__ . '/../modele/CommentaireDAO.php';

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
?>
