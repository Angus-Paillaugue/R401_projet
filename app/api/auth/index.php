<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../lib/connector.php';
require_once __DIR__ . '/../../lib/jwt.php';
require_once __DIR__ . '/../../lib/api.php';
require_once __DIR__ . '/../../modele/UserDAO.php';
JWT::init();

define('TOKEN_EXPIRATION', 60 * 60 * 24); // Token expiration set to 1 day

switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    $headers = getallheaders();
    if (!API::keyExists($headers, 'Authorization')) {
      API::deliver_response(401, 'Authorization header is missing');
    }

    $jwt = $headers['Authorization'];
    try {
      $jwt = str_replace('Bearer ', '', $jwt);
      $payload = JWT::validateJWT($jwt);

      if (!$payload) {
        API::deliver_response(401, 'Invalid authentication token');
      }

      if ($payload['exp'] < time()) {
        API::deliver_response(401, 'Expired authentication token');
      }

      API::deliver_response(
        200,
        'User authenticated successfully',
        array_filter(
          $payload,
          function ($key) {
            return $key !== 'exp';
          },
          ARRAY_FILTER_USE_KEY
        )
      );
    } catch (Exception $e) {
      API::deliver_response(401, $e->getMessage());
    }
    API::deliver_response(401, 'Invalid authentication token');
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

    $username = htmlspecialchars($body['username']);
    $password = htmlspecialchars($body['password']);
    $DAO = new UserDAO();
    $user = $DAO->search($username);
    if (count($user) == 0) {
      API::deliver_response(500, 'User does not exist');
    } else {
      $user = $user[0];
      if (password_verify($password, $user->getPasswordHash())) {
        $payload = [
          'id' => $user->getId(),
          'username' => $user->getUsername(),
          'exp' => time() + TOKEN_EXPIRATION,
        ];
        $jwt = JWT::generateJWT($payload);
        $cookieOptions = [
          'expires' => time() + TOKEN_EXPIRATION,
          'path' => '/',
          'domain' => '',
          'secure' => isset($_SERVER['HTTPS']),
          'httponly' => false, // Allow JS to access
          'samesite' => 'Lax', // None || Lax  || Strict
        ];
        setcookie('token', $jwt, $cookieOptions);
        API::deliver_response(200, 'User authenticated successfully', $jwt);
      } else {
        API::deliver_response(500, 'Incorrect password');
      }
    }

    break;
  default:
    API::deliver_response(405, 'Method not allowed');
    break;
}
?>
