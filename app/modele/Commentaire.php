<?php
class Commentaire
{
  private $id;
  private $id_joueur;
  private $contenu;
  private $date;

  public function __construct($id_joueur, $contenu)
  {
    $this->id_joueur = $id_joueur;
    $this->contenu = $contenu;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getIdJoueur()
  {
    return $this->id_joueur;
  }

  public function getContenu()
  {
    return $this->contenu;
  }

  public function getDate()
  {
    return $this->date;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function setIdJoueur($id_joueur)
  {
    $this->id_joueur = $id_joueur;
  }

  public function setContenu($contenu)
  {
    $this->contenu = $contenu;
  }

  public function setDate($date)
  {
    $this->date = $date;
  }
}
?>
