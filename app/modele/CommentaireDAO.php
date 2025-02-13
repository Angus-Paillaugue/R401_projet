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
    if (count($data) == 0) {
      throw new Exception('Commentaire non trouvé');
    }
    $data = $data[0];
    $commentaire = new Commentaire($data['id_joueur'], $data['contenu']);
    $commentaire->setId($data['id']);

    return $commentaire;
  }

  public function insert($commentaire)
  {
    $id_joueur = $commentaire->getIdJoueur();
    $contenu = $commentaire->getContenu();
    $insertedRow = $this->conn->insert(
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
      'UPDATE commentaire SET id_joueur = ?, contenu = ? WHERE id = ?;',
      $id_joueur,
      $contenu,
      $id
    );
  }

  public function delete($id)
  {
    $exists = $this->get($id);
    if (!$exists) {
      throw new Exception('Commentaire non trouvé');
    }
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
      'SELECT * FROM commentaire WHERE id_joueur = ? ORDER BY updated_at DESC;',
      $id_joueur
    );

    $commentaires = [];
    foreach ($data as $row) {
      $commentaire = new Commentaire($row['id_joueur'], $row['contenu']);
      $commentaire->setId($row['id']);
      $commentaire->setDate($row['updated_at']);
      array_push($commentaires, $commentaire);
    }
    return $commentaires;
  }
}
?>
