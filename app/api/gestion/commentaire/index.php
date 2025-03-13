<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../../lib/api.php';
require_once __DIR__ . '/../../../modele/Commentaire.php';
require_once __DIR__ . '/../../../modele/CommentaireDAO.php';
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

authenticate_request();

$DAO = new CommentaireDAO();
switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $id = API::keyExists($_GET, 'id') ? htmlspecialchars($_GET['id']) : null;
    if ($id) {
      try {
        $data = $DAO->get($id);
        API::deliver_response(
          200,
          'Comment fetched successfully',
          transform_comment($data)
        );
      } catch (Exception $e) {
        API::deliver_response(500, 'Comment not found', [
          'message' => $e->getMessage(),
        ]);
      }
    } else {
      API::deliver_response(400, 'You need to pass an id to fetch a comment');
    }
    break;
  case 'POST':
    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) {
      API::deliver_response(400, 'Request body is empty');
    }
    if (!API::keyExists($body, 'contenu')) {
      API::deliver_response(400, 'contenu is required');
    }
    if (!API::keyExists($body, 'id_joueur')) {
      API::deliver_response(400, 'id_joueur is required');
    }

    try {
      $comment = new Commentaire(intval($body['id_joueur']), $body['contenu']);
      $insertedRowId = $DAO->insert($comment);
      $comment->setId($insertedRowId);
      $comment->setDate(date('Y-m-d H:i:s'));

      API::deliver_response(
        201,
        'Comment created',
        transform_comment($comment)
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
      API::deliver_response(200, 'Comment deleted');
    } catch (Exception $e) {
      API::deliver_response(500, 'An error occurred', [
        'message' => $e->getMessage(),
      ]);
    }
    break;
  case 'PUT':
    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) {
      API::deliver_response(400, 'Request body is empty');
    }
    if (!API::keyExists($body, 'id')) {
      API::deliver_response(400, 'id is required');
    }
    if (!API::keyExists($body, 'id_joueur')) {
      API::deliver_response(400, 'id_joueur is required');
    }
    if (!API::keyExists($body, 'contenu')) {
      API::deliver_response(400, 'contenu is required');
    }

    $commentaire = new Commentaire($body['id_joueur'], $body['contenu']);
    $commentaire->setId(intval(htmlspecialchars($body['id'])));
    try {
      $DAO->update($commentaire);
      API::deliver_response(200, 'Comment updated');
    } catch (Exception $e) {
      API::deliver_response(500, 'An error occurred', [
        'message' => $e->getMessage(),
      ]);
    }
    break;
  case 'OPTIONS':
    API::deliver_response(200, 'OK');
  default:
    API::deliver_response(405, 'Method not allowed');
    break;
}
?>
