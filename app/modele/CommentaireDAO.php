<?php
require_once __DIR__ . '/../lib/connector.php';
require_once 'Commentaire.php';

class CommentaireDAO
{
  private $conn;

  public function __construct()
  {
    $this->conn = sql_connector::getInstance();
  }

  public function get($id)
  {
    $data = $this->conn->run_query(
      'SELECT * FROM commentaire WHERE id = ?;',
      $id
    );
    $data = $data[0];
    $commentaire = new Commentaire($data['id_joueur'], $data['contenu']);
    $commentaire->setId($data['id']);

    return $commentaire;
  }

  public function insert($commentaire)
  {
    $id_joueur = $commentaire->getIdJoueur();
    $contenu = $commentaire->getContenu();
    $insertedRow = $this->conn->run_query(
      'INSERT INTO commentaire (id_joueur, contenu) VALUES (?, ?);',
      $id_joueur,
      $contenu
    );
    return $insertedRow;
  }

  public function update($commentaire)
  {
    $id = $commentaire->getId();
    $id_joueur = $commentaire->getIdJoueur();
    $contenu = $commentaire->getContenu();
    $this->conn->run_query(
      'UPDATE commentaire SET id_joueur = ?, contenu = ?, date = ? WHERE id = ?;',
      $id_joueur,
      $contenu,
      $id,
      date('Y-m-d H:i:s')
    );
  }

  public function delete($commentaire)
  {
    $id = $commentaire->getId();
    $this->conn->run_query('DELETE FROM commentaire WHERE id = ?;', $id);
  }

  public function getAll()
  {
    $data = $this->conn->run_query('SELECT * FROM commentaire;');

    $commentaires = [];
    foreach ($data as $row) {
      $commentaire = new Commentaire($row['id_joueur'], $row['contenu']);
      $commentaire->setId($row['id']);
      $commentaire->setDate($row['date']);
      array_push($commentaires, $commentaire);
    }
    return $commentaires;
  }

  public function getAllForJoueur($joueur)
  {
    $id_joueur = $joueur->getId();
    $data = $this->conn->run_query(
      'SELECT * FROM commentaire WHERE id_joueur = ?;',
      $id_joueur
    );

    $commentaires = [];
    foreach ($data as $row) {
      $commentaire = new Commentaire($row['id_joueur'], $row['contenu']);
      $commentaire->setId($row['id']);
      $commentaire->setDate($row['date']);
      array_push($commentaires, $commentaire);
    }
    return $commentaires;
  }
}
?>
