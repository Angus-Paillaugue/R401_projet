<?php
require_once __DIR__ . '/../modele/CommentaireDAO.php';

class RecupererUnCommentaire
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
    return $this->DAO->get($this->id);
  }
}
?>
