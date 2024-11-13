<?php
require_once __DIR__ . '/../lib/connector.php';
require_once 'FeuilleMatch.php';
require_once 'JoueurDAO.php';

class FeuilleMatchDAO
{
  private $conn;

  public function __construct()
  {
    $this->conn = sql_connector::getInstance();
  }

  public function get($id)
  {
    $data = $this->conn->run_query(
      'SELECT * FROM feuille_match WHERE id = ?;',
      $id
    );
    if (count($data) == 0) {
      throw new Exception('Feuille de match non trouvée');
    }
    $data = $data[0];
    $feuilleMatch = new FeuilleMatch(
      $data['id_rencontre'],
      $data['id_joueur'],
      $data['role_debut'],
      $data['role_fin'],
      $data['poste'],
      $data['evaluation']
    );
    $feuilleMatch->setId($data['id']);
    $joueur = (new JoueurDAO())->get($feuilleMatch->getIdJoueur());
    $feuilleMatch->setJoueur($joueur);
    return $feuilleMatch;
  }

  public function getForRencontre($id)
  {
    $data = $this->conn->run_query(
      'SELECT * FROM feuille_match WHERE id_rencontre = ?;',
      $id
    );
    $dataArray = [];

    foreach ($data as $feuilleMatchDB) {
      $feuilleMatch = new FeuilleMatch(
        $feuilleMatchDB['id_rencontre'],
        $feuilleMatchDB['id_joueur'],
        $feuilleMatchDB['role_debut'],
        $feuilleMatchDB['role_fin'],
        $feuilleMatchDB['poste'],
        $feuilleMatchDB['evaluation']
      );
      $feuilleMatch->setId($feuilleMatchDB['id']);
      $joueur = (new JoueurDAO())->get($feuilleMatch->getIdJoueur());
      $feuilleMatch->setJoueur($joueur);
      array_push($dataArray, $feuilleMatch);
    }
    return $dataArray;
  }

  public function insert($feuilleMatch)
  {
    $id_rencontre = $feuilleMatch->getIdRencontre();
    $id_joueur = $feuilleMatch->getIdJoueur();
    $role_debut = $feuilleMatch->getRoleDebut();
    $role_fin = $feuilleMatch->getRoleFin();
    $poste = $feuilleMatch->getPoste();
    $evaluation = $feuilleMatch->getEvaluation();
    $insertedRowId = $this->conn->insert(
      'INSERT INTO feuille_match (id_rencontre, id_joueur, role_debut, role_fin, poste, evaluation) VALUES (?, ?, ?, ?, ?, ?);',
      $id_rencontre,
      $id_joueur,
      $role_debut,
      $role_fin,
      $poste,
      $evaluation
    );
    return $insertedRowId;
  }

  public function update($feuilleMatch)
  {
    $id = $feuilleMatch->getId();
    if (!$id) {
      $this->insert($feuilleMatch);
      return;
    }
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

  public function delete($feuilleMatch)
  {
    $id = $feuilleMatch->getId();
    $this->conn->run_query('DELETE FROM feuille_match WHERE id = ?;', $id);
  }

  public function getAll()
  {
    $data = $this->conn->run_query('SELECT * FROM feuille_match;');

    $dataArray = [];

    foreach ($data as $feuillMatch) {
      $feuillMatch = new FeuilleMatch(
        $feuillMatch['id_rencontre'],
        $feuillMatch['id_joueur'],
        $feuillMatch['role_debut'],
        $feuillMatch['role_fin'],
        $feuillMatch['poste'],
        $feuillMatch['evaluation']
      );
      $feuillMatch->setId($feuillMatch['id']);
      array_push($dataArray, $feuillMatch);
    }
    return $dataArray;
  }

  public function deleteForRencontre($id)
  {
    $this->conn->run_query(
      'DELETE FROM feuille_match WHERE id_rencontre = ?;',
      $id
    );
  }
}
?>
