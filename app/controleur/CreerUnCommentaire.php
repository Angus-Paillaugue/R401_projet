<?php
require_once __DIR__ . '/../modele/Commentaire.php';
require_once __DIR__ . '/../modele/CommentaireDAO.php';

class CreerUnCommentaire
{
  private $id_joueur;
  private $contenu;
  private $DAO;

  public function __construct($id_joueur, $contenu)
  {
    $this->id_joueur = $id_joueur;
    $this->contenu = $contenu;
    $this->DAO = new CommentaireDAO();
  }

  public function execute()
  {
    $commentaire = new Commentaire($this->id_joueur, $this->contenu);
    $insertedRow = $this->DAO->insert($commentaire);
    $commentaire->setId($insertedRow['id']);
    return $commentaire;
  }
}
?>
