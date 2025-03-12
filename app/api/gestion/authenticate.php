<?php

function authenticate_request()
{
  $method = $_SERVER['REQUEST_METHOD'];
  if ($method === 'OPTIONS') {
    return;
  }
  $headers = getallheaders();
  $base_url = array_key_exists('BASE_AUTH_API_URL', $_ENV)
    ? $_ENV['BASE_AUTH_API_URL']
    : 'http://localhost:8201/api/auth';

  if (!isset($headers['Authorization'])) {
    API::deliver_response(401, 'Authorization header is missing');
  }

  $ch = curl_init($base_url . '/index.php');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $headers['Authorization'], // Forward the Authorization header
    'Accept: application/json',
  ]);
  curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Set timeout to 5 seconds
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // Connection timeout

  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curlError = curl_error($ch);
  curl_close($ch);

  if ($response === false) {
    API::deliver_response(500, "Failed to connect to auth API: $curlError");
  }

  $responseData = json_decode($response, true);

  if ($httpCode === 200 && isset($responseData['data'])) {
    return $responseData['data'];
  }

  // If the response is not 200, return an error message
  API::deliver_response($httpCode, $responseData['message'] ?? 'Unauthorized');
}

?>
