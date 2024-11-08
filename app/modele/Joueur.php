<?php
class Joueur
{
  private $id;
  private $nom;
  private $prenom;
  private $numero_licence;
  private $date_naissance;
  private $taille;
  private $poids;
  private $statut;
  private $commentaires;

  public function __construct(
    $nom,
    $prenom,
    $numero_licence,
    $date_naissance,
    $taille,
    $poids,
    $statut
  ) {
    $this->nom = $nom;
    $this->prenom = $prenom;
    $this->numero_licence = $numero_licence;
    $this->date_naissance = $date_naissance;
    $this->taille = $taille;
    $this->poids = $poids;
    $this->statut = $statut;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getNom()
  {
    return $this->nom;
  }

  public function getPrenom()
  {
    return $this->prenom;
  }

  public function getNumeroLicence()
  {
    return $this->numero_licence;
  }

  public function getDateNaissance()
  {
    return $this->date_naissance;
  }

  public function getTaille()
  {
    return $this->taille;
  }

  public function getPoids()
  {
    return $this->poids;
  }

  public function getStatut()
  {
    return $this->statut;
  }

  public function getCommentaires()
  {
    return $this->commentaires;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function setNom($nom)
  {
    $this->nom = $nom;
  }

  public function setPrenom($prenom)
  {
    $this->prenom = $prenom;
  }

  public function setNumeroLicence($numero_licence)
  {
    $this->numero_licence = $numero_licence;
  }

  public function setDateNaissance($date_naissance)
  {
    $this->date_naissance = $date_naissance;
  }

  public function setTaille($taille)
  {
    $this->taille = $taille;
  }

  public function setPoids($poids)
  {
    $this->poids = $poids;
  }

  public function setStatut($statut)
  {
    $this->statut = $statut;
  }

  public function setCommentaires($commentaires)
  {
    $this->commentaires = $commentaires;
  }

  public function __toString()
  {
    return $this->nom . ' ' . $this->prenom;
  }
}
?>
