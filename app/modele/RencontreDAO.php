<?php
require_once __DIR__ . '/../lib/connector.php';
require_once 'Rencontre.php';
require_once 'FeuilleMatchDAO.php';

class RencontreDAO
{
  private $conn;

  public function __construct()
  {
    $this->conn = sql_connector::getInstance();
  }

  public function get($id)
  {
    $data = $this->conn->run_query(
      'SELECT * FROM rencontre WHERE id = ?;',
      $id
    );
    $data = $data[0];
    $rencontre = new Rencontre(
      $data['date_heure'],
      $data['equipe_adverse'],
      $data['lieu']
    );
    $rencontre->setId($data['id']);
    $rencontre->setResultat($data['resultat']);

    $feuilleMatch = (new FeuilleMatchDAO())->getForRencontre(
      $rencontre->getId()
    );
    $rencontre->setFeuilleMatch($feuilleMatch);
    return $rencontre;
  }

  public function insert($rencontre)
  {
    $date_heure = $rencontre->getDateHeure();
    $equipe_adverse = $rencontre->getEquipeAdverse();
    $lieu = $rencontre->getLieu();
    $resultat = $rencontre->getResultat();
    $insertedRowId = $this->conn->insert(
      'INSERT INTO rencontre (date_heure, equipe_adverse, lieu, resultat) VALUES (?, ?, ?, ?);',
      $date_heure,
      $equipe_adverse,
      $lieu,
      $resultat
    );
    return $insertedRowId;
  }

  public function update($rencontre)
  {
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

    $feuilleMatch = $rencontre->getFeuilleMatch();
    if ($feuilleMatch) {
      $feuilleMatchDAO = new FeuilleMatchDAO();
      foreach ($feuilleMatch as $feuille) {
        $feuilleMatchDAO->update($feuille);
      }
    }
  }

  public function delete($rencontre)
  {
    $id = $rencontre->getId();
    $this->conn->run_query('DELETE FROM rencontre WHERE id = ?;', $id);
  }

  public function getAll()
  {
    $rows = $this->conn->run_query('SELECT * FROM rencontre;');
    $rencontresArray = [];
    foreach ($rows as $row) {
      $rencontre = new Rencontre(
        $row['date_heure'],
        $row['equipe_adverse'],
        $row['lieu']
      );
      $rencontre->setId($row['id']);
      $rencontre->setResultat($row['resultat']);

      array_push($rencontresArray, $rencontre);
    }

    return $rencontresArray;
  }

  public function getNext($limit)
  {
    if ($limit) {
      $limit = 'LIMIT ' . $limit;
    }
    $sql =
      'SELECT * FROM rencontre WHERE date_heure > NOW() ORDER BY date_heure ASC ' .
      $limit .
      ';';
    $rows = $this->conn->run_query($sql);
    $nextRencontres = [];
    foreach ($rows as $row) {
      $rencontre = new Rencontre(
        $row['date_heure'],
        $row['equipe_adverse'],
        $row['lieu']
      );
      $rencontre->setId($row['id']);
      $rencontre->setResultat($row['resultat']);
      array_push($nextRencontres, $rencontre);
    }

    return $nextRencontres;
  }

  public function getPrevious($limit)
  {
    if ($limit) {
      $limit = 'LIMIT ' . $limit;
    }
    $sql =
      'SELECT * FROM rencontre WHERE date_heure < NOW() ORDER BY date_heure DESC ' .
      $limit .
      ';';
    $rows = $this->conn->run_query($sql);
    $previousRencontres = [];
    foreach ($rows as $row) {
      $rencontre = new Rencontre(
        $row['date_heure'],
        $row['equipe_adverse'],
        $row['lieu']
      );
      $rencontre->setId($row['id']);
      $rencontre->setResultat($row['resultat']);
      array_push($previousRencontres, $rencontre);
    }

    return $previousRencontres;
  }
}
?>
