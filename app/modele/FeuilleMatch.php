<?php
class FeuilleMatch {
  private $id;
  private $id_rencontre;
  private $id_joueur;
  private $role_debut;
  private $role_fin;
  private $poste;
  private $evaluation;

  public function __construct($id_rencontre, $id_joueur, $role_debut, $role_fin, $poste, $evaluation) {
    $this->id_rencontre = $id_rencontre;
    $this->id_joueur = $id_joueur;
    $this->role_debut = $role_debut;
    $this->role_fin = $role_fin;
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

  public function getRoleDebut() {
    return $this->role_debut;
  }

  public function getRoleFin() {
    return $this->role_fin;
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

  public function setRoleDebut($role_debut) {
    $this->role_debut = $role_debut;
  }

  public function setRoleFin($role_fin) {
    $this->role_fin = $role_fin;
  }

  public function setPoste($poste) {
    $this->poste = $poste;
  }

  public function setEvaluation($evaluation) {
    $this->evaluation = $evaluation;
  }

  public function __toString() {
    return $this->id_rencontre . ' ' . $this->id_joueur . ' ' . $this->role_debut . ' ' . $this->role_fin . ' ' . $this->poste . ' ' . $this->evaluation;
  }
}
?>
