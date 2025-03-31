<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../../lib/connector.php';
require_once __DIR__ . '/../../../lib/api.php';
require_once __DIR__ . '/../../../modele/User.php';
require_once __DIR__ . '/../../../modele/UserDAO.php';
require_once __DIR__ . '/../authenticate.php';

function transform_user($user)
{
  $JSON = [];
  $JSON['id'] = $user->getId();
  $JSON['username'] = $user->getUsername();
  $JSON['password_hash'] = $user->getPasswordHash();

  return $JSON;
}

authenticate_request();

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    try {
      $userDAO = new UserDAO();
      $users = $userDAO->getAll();
      API::deliver_response(
        200,
        'Users fetched successfully',
        array_map('transform_user', $users)
      );
    } catch (Exception $e) {
      API::deliver_response(500, 'An error occurred', [
        'message' => $e->getMessage(),
      ]);
    }
    break;
  case 'POST':
    $body = json_decode(file_get_contents('php://input'), true);
    if (!$body) {
      API::deliver_response(400, 'Request body is empty');
    }
    if (!API::keyExists($body, 'username')) {
      API::deliver_response(400, 'username is required');
    }
    if (!API::keyExists($body, 'password')) {
      API::deliver_response(400, 'password is required');
    }

    try {
      $DAO = new UserDAO();
      $username = $body['username'];
      $userExists = $DAO->search($username);
      if ($userExists) {
        API::deliver_response(409, 'User already exists');
      }

      $password_hash = password_hash($body['password'], PASSWORD_DEFAULT);
      $user = new User($username, $password_hash);
      $insertedRowId = $DAO->insert($user);
      $user->setId($insertedRowId);

      API::deliver_response(
        200,
        'User created successfully',
        transform_user($user)
      );
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
