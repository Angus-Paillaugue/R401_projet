<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../../lib/connector.php';
require_once __DIR__ . '/../../../lib/api.php';
require_once __DIR__ . '/../../../modele/Rencontre.php';
require_once __DIR__ . '/../../../modele/RencontreDAO.php';
require_once __DIR__ . '/../../../modele/FeuilleMatchDAO.php';
require_once __DIR__ . '/../authenticate.php';

function transform_comment($data)
{
  $JSON = [];
  $JSON['id'] = $data->getId();
  $JSON['contenu'] = $data->getContenu();
  $JSON['date'] = $data->getDate();
  $JSON['id_joueur'] = $data->getIdJoueur();

  return $JSON;
}
function transform_player($data)
{
  $JSON = [];
  $JSON['id'] = $data->getId();
  $JSON['nom'] = $data->getNom();
  $JSON['prenom'] = $data->getPrenom();
  $JSON['numero_licence'] = $data->getNumeroLicence();
  $JSON['date_naissance'] = $data->getDateNaissance();
  $JSON['taille'] = $data->getTaille();
  $JSON['poids'] = $data->getPoids();
  $JSON['statut'] = $data->getStatut();
  $JSON['commentaires'] = $data->getCommentaires()
    ? array_map('transform_comment', $data->getCommentaires())
    : [];

  return $JSON;
}
function transform_feuille_match($feuille)
{
  $JSON = [];
  $JSON['id'] = $feuille->getId();
  $JSON['id_rencontre'] = $feuille->getIdRencontre();
  $JSON['role_debut'] = $feuille->getRoleDebut();
  $JSON['role_fin'] = $feuille->getRoleFin();
  $JSON['poste'] = $feuille->getPoste();
  $JSON['evaluation'] = $feuille->getEvaluation();
  $JSON['joueur'] = transform_player($feuille->getJoueur());

  return $JSON;
}
function transform_rencontre($rencontre)
{
  $JSON = [];
  $JSON['id'] = $rencontre->getId();
  $JSON['date_heure'] = $rencontre->getDateHeure();
  $JSON['equipe_adverse'] = $rencontre->getEquipeAdverse();
  $JSON['lieu'] = $rencontre->getLieu();
  $JSON['resultat'] = $rencontre->getResultat();
  $JSON['feuille_match'] = $rencontre->getFeuilleMatch()
    ? array_map('transform_feuille_match', $rencontre->getFeuilleMatch())
    : [];

  return $JSON;
}

authenticate_request();

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $id = API::keyExists($_GET, 'id') ? htmlspecialchars($_GET['id']) : null;
    $DAO = new RencontreDAO();
    if ($id) {
      // Lister une rencontre
      try {
        $rencontre = $DAO->get($id);
        if (!$rencontre) {
          API::deliver_response(404, 'Rencontre not found');
        }

        API::deliver_response(
          200,
          'Rencontre fetched successfully',
          transform_rencontre($rencontre)
        );
      } catch (Exception $e) {
        API::deliver_response(500, 'An error occurred', [
          'message' => $e->getMessage(),
        ]);
      }
    } else {
      // Lister toute les rencontres
      $limit = API::keyExists($_GET, 'limit')
        ? htmlspecialchars($_GET['limit'])
        : 10;
      try {
        $previous = $DAO->getPrevious($limit);
        $next = $DAO->getNext($limit);
        API::deliver_response(200, 'Rencontres fetched successfully', [
          'previous' => array_map('transform_rencontre', $previous),
          'next' => array_map('transform_rencontre', $next),
        ]);
      } catch (Exception $e) {
        API::deliver_response(500, 'An error occurred', [
          'message' => $e->getMessage(),
        ]);
      }
    }
    break;
  case 'POST':
    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) {
      API::deliver_response(400, 'Request body is empty');
    }
    if (
      !API::keyExists($body, 'equipe_adverse') ||
      empty($body['equipe_adverse'])
    ) {
      API::deliver_response(400, 'equipe_adverse is required');
    }
    if (!API::keyExists($body, 'date_heure') || empty($body['date_heure'])) {
      API::deliver_response(400, 'date_heure is required');
    }
    if (!API::keyExists($body, 'lieu') || empty($body['lieu'])) {
      API::deliver_response(400, 'lieu is required');
    }

    try {
      $DAO = new RencontreDAO();
      $rencontre = new Rencontre(
        $body['date_heure'],
        $body['equipe_adverse'],
        $body['lieu']
      );
      $idRencontre = $DAO->insert($rencontre);
      $rencontre->setId($idRencontre);

      API::deliver_response(
        201,
        'Rencontre created successfully',
        transform_rencontre($rencontre)
      );
    } catch (Exception $e) {
      API::deliver_response(500, 'An error occurred', [
        'message' => $e->getMessage(),
      ]);
    }
    break;
  case 'DELETE':
    $id = API::keyExists($_GET, 'id') ? htmlspecialchars($_GET['id']) : null;
    if (!$id) {
      API::deliver_response(400, 'id is required');
    }

    try {
      $DAO = new RencontreDAO();
      $rencontre = $DAO->delete($id);

      API::deliver_response(200, 'Rencontre deleted successfully');
    } catch (Exception $e) {
      API::deliver_response(500, 'An error occurred', [
        'message' => $e->getMessage(),
      ]);
    }
    break;
  case 'PATCH':
    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) {
      API::deliver_response(400, 'Request body is empty');
    }
    if (!API::keyExists($body, 'id')) {
      API::deliver_response(400, 'id is required');
    }

    $columns = ['date_heure', 'equipe_adverse', 'lieu', 'resultat'];
    $sql = 'UPDATE rencontre SET ';

    $args = array_filter(
      $body,
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
    array_push($sqlValues, $body['id']);

    try {
      // Update the rencontre table
      sql_connector::getInstance(
        'R401_projet_gestion',
        'R401_projet_gestion',
        'R401_projet_gestion',
        array_key_exists('MYSQL_HOST', $_ENV)
          ? $_ENV['MYSQL_HOST']
          : 'localhost'
      )->run_query($sql, ...$sqlValues);

      // Update the feuilles de match if any
      if(array_key_exists('feuille_match', $body)) {
        $feuilleMatchDAO = new FeuilleMatchDAO();
        foreach ($body['feuille_match'] as $feuille) {
          $feuilleMetier = new FeuilleMatch(
            intval($feuille['id_rencontre']),
            intval($feuille['id_joueur']),
            $feuille['role_debut'],
            $feuille['role_fin'],
            $feuille['poste'],
            intval($feuille['evaluation'])
          );
          $feuilleMetier->setId(intval($feuille['id']));
          $feuilleMatchDAO->update($feuilleMetier);
        }
      }
      API::deliver_response(200, 'Rencontre updated successfully');
    } catch (Exception $e) {
      API::deliver_response(500, 'An error occurred', [
        'message' => $e->getMessage(),
      ]);
    }
    break;
  default:
    API::deliver_response(405, 'Method not allowed');
    break;
}
?>
