<?php
require_once '../../lib/connector.php';

class RencontreDAO {
  private $conn;

  public function __construct() {
    $this->conn = sql_connector::getInstance();
  }

  public function get($rencontre) {
    $id = $rencontre->getId();
    $data = $this->conn->run_query('SELECT * FROM rencontre WHERE id = ?;', $id);
    $data = $data[0];
  }

  public function insert($rencontre) {
    $date_heure = $rencontre->getDateHeure();
    $equipe_adverse = $rencontre->getEquipeAdverse();
    $lieu = $rencontre->getLieu();
    $resultat = $rencontre->getResultat();
    $insertedRow = $this->conn->run_query(
      'INSERT INTO rencontre (date_heure, equipe_adverse, lieu, resultat) VALUES (?, ?, ?, ?);',
      $date_heure,
      $equipe_adverse,
      $lieu,
      $resultat
    );
    return $insertedRow;
  }

  public function update($rencontre) {
    $id = $rencontre->getId();
    $date_heure = $rencontre->getDateHeure();
    $equipe_adverse = $rencontre->getEquipeAdverse();
    $lieu = $rencontre->getLieu();
    $resultat = $rencontre->getResultat();
    $this->conn->run_query(
      'UPDATE rencontre SET date_heure = ?, equipe_adverse = ?, lieu = ?, resultat = ? WHERE id = ?;',
      $date_heure,
      $equipe_adverse,
      $lieu,
      $resultat,
      $id
    );
  }

  public function delete($rencontre) {
    $id = $rencontre->getId();
    $this->conn->run_query('DELETE FROM rencontre WHERE id = ?;', $id);
  }

  public function getAll() {
    return $this->conn->run_query('SELECT * FROM rencontre;');
  }

  public function getNext() {
    return $this->conn->run_query('SELECT * FROM rencontre WHERE date_heure > NOW() ORDER BY date_heure;');
  }

  public function getPrevious() {
    return $this->conn->run_query('SELECT * FROM rencontre WHERE date_heure < NOW() ORDER BY date_heure;');
  }
}
?>
