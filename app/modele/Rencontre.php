<?php
class Rencontre {
  private $id;
  private $date_heure;
  private $equipe_adverse;
  private $lieu;
  private $resultat;

  public function __construct($date_heure, $equipe_adverse, $lieu, $resultat) {
    $this->date_heure = $date_heure;
    $this->equipe_adverse = $equipe_adverse;
    $this->lieu = $lieu;
    $this->resultat = $resultat;
  }

  public function getId() {
    return $this->id;
  }

  public function getDateHeure() {
    return $this->date_heure;
  }

  public function getEquipeAdverse() {
    return $this->equipe_adverse;
  }

  public function getLieu() {
    return $this->lieu;
  }

  public function getResultat() {
    return $this->resultat;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function setDateHeure($date_heure) {
    $this->date_heure = $date_heure;
  }

  public function setEquipeAdverse($equipe_adverse) {
    $this->equipe_adverse = $equipe_adverse;
  }

  public function setLieu($lieu) {
    $this->lieu = $lieu;
  }

  public function setResultat($resultat) {
    $this->resultat = $resultat;
  }

  public function __toString() {
    return $this->date_heure . ' ' . $this->equipe_adverse . ' ' . $this->lieu . ' ' . $this->resultat;
  }
}
?>
