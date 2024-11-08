<?php
require_once '../../lib/connector.php';
require_once 'FeuilleMatch.php';

class FeuilleMatchDAO {
  private $conn;

  public function __construct() {
    $this->conn = sql_connector::getInstance();
  }

  public function get($feuilleMatch) {
    $id = $feuilleMatch->getId();
    $data = $this->conn->run_query('SELECT * FROM feuille_match WHERE id = ?;', $id);
    $data = $data[0];
    $feuilleMatch = new FeuilleMatch($data['id_rencontre'], $data['id_joueur'], $data['role_debut'], $data['role_fin'], $data['poste'], $data['evaluation']);
    $feuilleMatch->setId($data['id']);
  }

  public function insert($feuilleMatch) {
    $id_rencontre = $feuilleMatch->getIdRencontre();
    $id_joueur = $feuilleMatch->getIdJoueur();
    $role_debut = $feuilleMatch->getRoleDebut();
    $role_fin = $feuilleMatch->getRoleFin();
    $poste = $feuilleMatch->getPoste();
    $evaluation = $feuilleMatch->getEvaluation();
    $insertedRow = $this->conn->run_query(
      'INSERT INTO feuille_match (id_rencontre, id_joueur, role_debut, role_fin, poste, evaluation) VALUES (?, ?, ?, ?, ?, ?);',
      $id_rencontre,
      $id_joueur,
      $role_debut,
      $role_fin,
      $poste,
      $evaluation
    );
    return $insertedRow;
  }

  public function update($feuilleMatch) {
    $id = $feuilleMatch->getId();
    $id_rencontre = $feuilleMatch->getIdRencontre();
    $id_joueur = $feuilleMatch->getIdJoueur();
    $role_debut = $feuilleMatch->getRoleDebut();
    $role_fin = $feuilleMatch->getRoleFin();
    $poste = $feuilleMatch->getPoste();
    $evaluation = $feuilleMatch->getEvaluation();
    $this->conn->run_query(
      'UPDATE feuille_match SET id_rencontre = ?, id_joueur = ?, role_debut = ?, role_fin = ?, poste = ?, evaluation = ? WHERE id = ?;',
      $id_rencontre,
      $id_joueur,
      $role_debut,
      $role_fin,
      $poste,
      $evaluation,
      $id
    );
  }

  public function delete($feuilleMatch) {
    $id = $feuilleMatch->getId();
    $this->conn->run_query('DELETE FROM feuille_match WHERE id = ?;', $id);
  }

  public function getAll() {
    $data = $this->conn->run_query('SELECT * FROM feuille_match;');

    $dataArray = array();

    foreach ($data as $feuillMatch) {
      $feuillMatch = new FeuilleMatch($feuillMatch['id_rencontre'], $feuillMatch['id_joueur'], $feuillMatch['role_debut'], $feuillMatch['role_fin'], $feuillMatch['poste'], $feuillMatch['evaluation']);
      $feuillMatch->setId($feuillMatch['id']);
      array_push($dataArray, $feuillMatch);
    }
    return $dataArray;
  }
}
?>
