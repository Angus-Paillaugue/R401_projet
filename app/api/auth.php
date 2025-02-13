<?php
require_once __DIR__ . '/../lib/connector.php';
require_once __DIR__ . '/../lib/api.php';
require_once __DIR__ . '/../lib/jwt.php';

function authenticate_request()
{
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

    return $payload;
  } catch (Exception $e) {
    API::deliver_response(401, $e->getMessage());
  }
}
?>
