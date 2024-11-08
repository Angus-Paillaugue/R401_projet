<?php
class FeuillMatch {
  private $id;
  private $id_rencontre;
  private $id_joueur;
  private $role;
  private $poste;
  private $evaluation;

  public function __construct($id_rencontre, $id_joueur, $role, $poste, $evaluation) {
    $this->id_rencontre = $id_rencontre;
    $this->id_joueur = $id_joueur;
    $this->role = $role;
    $this->poste = $poste;
    $this->evaluation = $evaluation;
  }

  public function getId() {
    return $this->id;
  }

  public function getIdRencontre() {
    return $this->id_rencontre;
  }

  public function getIdJoueur() {
    return $this->id_joueur;
  }

  public function getRole() {
    return $this->role;
  }

  public function getPoste() {
    return $this->poste;
  }

  public function getEvaluation() {
    return $this->evaluation;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setIdRencontre($id_rencontre) {
    $this->id_rencontre = $id_rencontre;
  }

  public function setIdJoueur($id_joueur) {
    $this->id_joueur = $id_joueur;
  }

  public function setRole($role) {
    $this->role = $role;
  }

  public function setPoste($poste) {
    $this->poste = $poste;
  }

  public function setEvaluation($evaluation) {
    $this->evaluation = $evaluation;
  }

  public function __toString() {
    return $this->id_rencontre . ' ' . $this->id_joueur . ' ' . $this->role . ' ' . $this->poste . ' ' . $this->evaluation;
  }
}
?>
