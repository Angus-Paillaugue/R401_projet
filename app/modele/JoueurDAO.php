<?php
require_once __DIR__ . '/../lib/connector.php';
require_once 'CommentaireDAO.php';
require_once 'Joueur.php';

class JoueurDAO
{
  private $conn;

  public function __construct()
  {
    $this->conn = sql_connector::getInstance(
      'R401_projet_gestion',
      'R401_projet_gestion',
      'R401_projet_gestion',
      array_key_exists('MYSQL_HOST', $_ENV) ? $_ENV['MYSQL_HOST'] : 'localhost'
    );
  }

  public function get($id)
  {
    $data = $this->conn->run_query('SELECT * FROM joueur WHERE id = ?;', $id);
    if (count($data) == 0) {
      throw new Exception('Joueur non trouvé');
    }
    $data = $data[0];

    $joueur = new Joueur(
      $data['nom'],
      $data['prenom'],
      $data['numero_licence'],
      $data['date_naissance'],
      $data['taille'],
      $data['poids']
    );
    $joueur->setId($data['id']);
    $joueur->setStatut($data['statut']);
    $commentaires = (new CommentaireDAO())->getAllForJoueur($joueur);
    $joueur->setCommentaires($commentaires);

    return $joueur;
  }

  public function insert($joueur)
  {
    $nom = $joueur->getNom();
    $prenom = $joueur->getPrenom();
    $numeroLicence = $joueur->getNumeroLicence();
    $dateNaissance = $joueur->getDateNaissance();
    $taille = $joueur->getTaille();
    $poids = $joueur->getPoids();
    $statut = $joueur->getStatut();
    $insertedRowId = $this->conn->insert(
      'INSERT INTO joueur (nom, prenom, numero_licence, date_naissance, taille, poids, statut) VALUES (?, ?, ?, ?, ?, ?, ?);',
      $nom,
      $prenom,
      $numeroLicence,
      $dateNaissance,
      $taille,
      $poids,
      $statut
    );
    return $insertedRowId;
  }

  public function update($id, $fields)
  {
    $columns = [
      'nom',
      'prenom',
      'numero_licence',
      'date_naissance',
      'taille',
      'poid',
    ];
    $sql = 'UPDATE joueur SET ';

    $args = array_filter(
      $fields,
      function ($key) use ($columns) {
        return in_array($key, $columns);
      },
      ARRAY_FILTER_USE_KEY
    );
    $sqlArgs = [];
    $sqlValues = [];

    foreach ($columns as $key => $column) {
      if (array_key_exists($column, $args)) {
        array_push($sqlValues, $args[$column]);
        array_push($sqlArgs, $column . ' = ?');
      }
    }

    $sql .= implode(', ', $sqlArgs);
    $sql .= ' WHERE id = ?';
    array_push($sqlValues, $id);

    $this->conn->run_query($sql, ...$sqlValues);
  }

  public function delete($id)
  {
    $this->conn->run_query('DELETE FROM joueur WHERE id = ?;', $id);
  }

  public function getAll()
  {
    $rows = $this->conn->run_query('SELECT * FROM joueur;');
    $joueursArray = [];
    foreach ($rows as $row) {
      $joueur = new Joueur(
        $row['nom'],
        $row['prenom'],
        $row['numero_licence'],
        $row['date_naissance'],
        $row['taille'],
        $row['poids']
      );
      $joueur->setId($row['id']);
      $joueur->setStatut($row['statut']);
      $commentaires = (new CommentaireDAO())->getAllForJoueur($joueur);
      $joueur->setCommentaires($commentaires);
      array_push($joueursArray, $joueur);
    }

    return $joueursArray;
  }

  public function search($nom)
  {
    $rows = $this->conn->run_query(
      'SELECT * FROM joueur WHERE nom LIKE ?;',
      '%' . $nom . '%'
    );
    $joueursArray = [];
    foreach ($rows as $row) {
      $joueur = new Joueur(
        $row['nom'],
        $row['prenom'],
        $row['numero_licence'],
        $row['date_naissance'],
        $row['taille'],
        $row['poids']
      );
      $joueur->setId($row['id']);
      $joueur->setStatut($row['statut']);
      $commentaires = (new CommentaireDAO())->getAllForJoueur($joueur);
      $joueur->setCommentaires($commentaires);
      array_push($joueursArray, $joueur);
    }

    return $joueursArray;
  }

  public function getStatistics($id)
  {
    $data = $this->conn->run_query(
      'SELECT poste, COUNT(poste) as count FROM feuille_match WHERE id_joueur = ? GROUP BY poste ORDER BY count DESC LIMIT 1;',
      $id
    );
    if (count($data) == 0) {
      return [
        'poste' => null,
      ];
    }
    return [
      'poste' => $data[0]['poste'],
    ];
  }
}
?>
