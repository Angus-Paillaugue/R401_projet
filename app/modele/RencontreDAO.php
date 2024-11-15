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
    if (count($data) == 0) {
      throw new Exception('Rencontre non trouvée');
    }
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

  public function getStatistics()
  {
    $sql = "SELECT
      (SELECT COUNT(*) FROM rencontre WHERE resultat = 'Victoire') as nbMatchGagnes,
      (SELECT COUNT(*) FROM rencontre WHERE resultat = 'Defaite') as nbMatchPerdus,
      (SELECT COUNT(*) FROM rencontre WHERE resultat = 'Nul') as nbMatchNuls,
      (SELECT COUNT(*) FROM rencontre) as nbMatchTotal;";
    $rows = $this->conn->run_query($sql);

    $playersStatsSQL = "SELECT
      joueur.prenom,
      joueur.nom,
      joueur.id,

      -- Poste with the highest number of wins for each joueur
      (SELECT poste
      FROM feuille_match fm
      JOIN rencontre r ON fm.id_rencontre = r.id
      WHERE fm.id_joueur = joueur.id AND r.resultat = 'Victoire'
      GROUP BY poste
      ORDER BY COUNT(*) DESC
      LIMIT 1) AS poste_max_victoire,

      -- Number of times the player was a 'Remplaçant' in either role_debut or role_fin
      COUNT(CASE WHEN feuille_match.role_debut = 'Remplaçant' OR feuille_match.role_fin = 'Remplaçant' THEN 1 END) AS nbRemplacant,
      COUNT(CASE WHEN feuille_match.role_debut = 'Titulaire' OR feuille_match.role_fin = 'Titulaire' THEN 1 END) AS nbTitulaire,

      -- Average evaluation score for the player
      AVG(feuille_match.evaluation) AS moyenne_notes,

      -- Win percentage when the player was 'Titulaire' at either the start or the end of the match
      (100.0 * COUNT(CASE WHEN (feuille_match.role_debut = 'Titulaire' OR feuille_match.role_fin = 'Titulaire') AND rencontre.resultat = 'Victoire' THEN 1 END) /
      NULLIF(COUNT(CASE WHEN feuille_match.role_debut = 'Titulaire' OR feuille_match.role_fin = 'Titulaire' THEN 1 END), 0)) AS pourcentage_victoire_titulaire,
      -- Number of consecutive matches the player has participated in
      (SELECT MAX(consecutive_matches) FROM (
        SELECT id_joueur, COUNT(*) AS consecutive_matches
        FROM (
          SELECT id_joueur, id_rencontre,
          ROW_NUMBER() OVER (PARTITION BY id_joueur ORDER BY date_heure) -
          ROW_NUMBER() OVER (PARTITION BY id_joueur, CASE WHEN role_debut IS NOT NULL OR role_fin IS NOT NULL THEN 1 ELSE 0 END ORDER BY date_heure) AS grp
          FROM feuille_match
          JOIN rencontre ON feuille_match.id_rencontre = rencontre.id
        ) AS subquery
        WHERE role_debut IS NOT NULL OR role_fin IS NOT NULL
        GROUP BY id_joueur, grp
      ) AS consecutive_matches_subquery WHERE id_joueur = joueur.id) AS nbMatchConsecutifs
    FROM joueur
    LEFT JOIN feuille_match ON joueur.id = feuille_match.id_joueur
    LEFT JOIN rencontre ON feuille_match.id_rencontre = rencontre.id

    GROUP BY joueur.id, feuille_match.role_debut, feuille_match.role_fin;";

    $playersStats = $this->conn->run_query($playersStatsSQL);
    return [
      'club' => $rows[0],
      'joueurs' => $playersStats,
    ];
  }
}
?>
