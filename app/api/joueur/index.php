<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../lib/connector.php';
require_once __DIR__ . '/../../lib/api.php';
require_once __DIR__ . '/../../modele/Joueur.php';
require_once __DIR__ . '/../../modele/JoueurDAO.php';
require_once __DIR__ . '/../auth.php';

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

authenticate_request();

$DAO = new JoueurDAO();
switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $id = API::keyExists($_GET, 'id') ? htmlspecialchars($_GET['id']) : null;
    if ($id) {
      try {
        $data = $DAO->get($id);
        if (!$data) {
          API::deliver_response(404, 'Player not found');
        }
        API::deliver_response(
          200,
          'Player fetched successfully',
          transform_player($data)
        );
      } catch (Exception $e) {
        API::deliver_response(500, 'An error occurred', [
          'message' => $e->getMessage(),
        ]);
      }
    } else {
      $players = $DAO->getAll();
      API::deliver_response(
        200,
        'Players fetched successfully',
        array_map('transform_player', $players)
      );
    }
    break;
  case 'POST':
    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) {
      API::deliver_response(400, 'Request body is empty');
    }
    if (!API::keyExists($body, 'nom')) {
      API::deliver_response(400, 'nom is required');
    }
    if (!API::keyExists($body, 'prenom')) {
      API::deliver_response(400, 'prenom is required');
    }
    if (!API::keyExists($body, 'numero_licence')) {
      API::deliver_response(400, 'numero_licence is required');
    }
    if (!API::keyExists($body, 'date_naissance')) {
      API::deliver_response(400, 'date_naissance is required');
    }
    if (!API::keyExists($body, 'taille')) {
      API::deliver_response(400, 'taille is required');
    }
    if (!API::keyExists($body, 'poid')) {
      API::deliver_response(400, 'poid is required');
    }

    $nom = htmlspecialchars($body['nom']);
    $prenom = htmlspecialchars($body['prenom']);
    $numero_licence = htmlspecialchars($body['numero_licence']);
    $date_naissance = htmlspecialchars($body['date_naissance']);
    $taille = intval(htmlspecialchars($body['taille']));
    $poid = floatval(htmlspecialchars($body['poid']));

    try {
      $player = new Joueur(
        $nom,
        $prenom,
        $numero_licence,
        $date_naissance,
        $taille,
        $poid
      );
      $insertedRowId = $DAO->insert($player);
      $player->setId(intval($insertedRowId));

      API::deliver_response(
        201,
        'Player created successfully',
        transform_player($player)
      );
    } catch (Exception $e) {
      API::deliver_response(500, 'An error occurred', [
        'message' => $e->getMessage(),
      ]);
    }
    break;
  case 'DELETE':
    if (!API::keyExists($_GET, 'id')) {
      API::deliver_response(400, 'id is required');
    }
    $id = intval(htmlspecialchars($_GET['id']));
    try {
      $DAO->delete($id);
      API::deliver_response(200, 'Player deleted successfully');
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

    sql_connector::getInstance()->run_query($sql, ...$sqlValues);
    API::deliver_response(200, 'Player updated successfully');
    break;
  default:
    API::deliver_response(405, 'Method not allowed');
    break;
}
?>
