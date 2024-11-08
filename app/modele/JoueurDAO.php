<?php
require_once '../../lib/connector.php';

class JoueurDAO {
  private $conn;

  public function __construct() {
    $this->conn = sql_connector::getInstance();
  }

  public function get($joueur) {
    $id = $joueur->getId();
    $data = $this->conn->run_query('SELECT * FROM joueurs WHERE id = ?;', $id);
    $data = $data[0];
  }

  public function insert($joueur) {
    $nom = $joueur->getNom();
    $prenom = $joueur->getPrenom();
    $numeroLicence = $joueur->getNumeroLicence();
    $dateNaissance = $joueur->getDateNaissance();
    $taille = $joueur->getTaille();
    $poids = $joueur->getPoids();
    $statut = $joueur->getStatut();
    $insertedRow = $this->conn->run_query(
      'INSERT INTO joueur (nom, prenom, numero_licence, date_naissance, taille, poids, statut) VALUES (?, ?, ?, ?, ?, ?, ?);',
      $nom,
      $prenom,
      $numeroLicence,
      $dateNaissance,
      $taille,
      $poids,
      $statut
    );
    return $insertedRow;
  }

  public function update($joueur) {
    $id = $joueur->getId();
    $nom = $joueur->getNom();
    $prenom = $joueur->getPrenom();
    $numeroLicence = $joueur->getNumeroLicence();
    $dateNaissance = $joueur->getDateNaissance();
    $taille = $joueur->getTaille();
    $poids = $joueur->getPoids();
    $statut = $joueur->getStatut();
    $this->conn->run_query(
      'UPDATE joueur SET nom = ?, prenom = ?, numero_licence = ?, date_naissance = ?, taille = ?, poids = ?, statut = ? WHERE id = ?;',
      $nom,
      $prenom,
      $numeroLicence,
      $dateNaissance,
      $taille,
      $poids,
      $statut,
      $id
    );
  }

  public function delete($joueur) {
    $id = $joueur->getId();
    $this->conn->run_query('DELETE FROM joueur WHERE id = ?;', $id);
  }

  public function getAll() {
    return $this->conn->run_query('SELECT * FROM joueur;');
  }

  public function search($nom) {
    return $this->conn->run_query('SELECT * FROM joueur WHERE nom = ?;', $nom);
  }
}
?>
