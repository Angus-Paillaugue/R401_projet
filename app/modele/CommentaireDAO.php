<?php
require_once '../../lib/connector.php';

class CommentaireDAO {
  private $conn;

  public function __construct() {
    $this->conn = sql_connector::getInstance();
  }

  public function get($commentaire) {
    $id = $commentaire->getId();
    $data = $this->conn->run_query('SELECT * FROM commentaire WHERE id = ?;', $id);
    $data = $data[0];
  }

  public function insert($commentaire) {
    $id_joueur = $commentaire->getIdJoueur();
    $contenu = $commentaire->getContenu();
    $insertedRow = $this->conn->run_query(
      'INSERT INTO commentaire (id_joueur, contenu) VALUES (?, ?);',
      $id_joueur,
      $contenu
    );
    return $insertedRow;
  }

  public function update($commentaire) {
    $id = $commentaire->getId();
    $id_joueur = $commentaire->getIdJoueur();
    $contenu = $commentaire->getContenu();
    $this->conn->run_query(
      'UPDATE commentaire SET id_joueur = ?, contenu = ? WHERE id = ?;',
      $id_joueur,
      $contenu,
      $id
    );
  }

  public function delete($commentaire) {
    $id = $commentaire->getId();
    $this->conn->run_query('DELETE FROM commentaire WHERE id = ?;', $id);
  }

  public function getAll() {
    $data = $this->conn->run_query('SELECT * FROM commentaire;');
    return $data;
  }
}
?>
