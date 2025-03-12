<?php

class API
{
  static function deliver_response($status_code, $status_message, $data = null)
  {
    // Paramétrage de l'entête HTTP
    http_response_code($status_code);
    //header("HTTP/1.1 $status_code $status_message");
    header('Content-Type: application/json; charset=utf-8');

    // CORS handling
    $request_headers = getallheaders();
    // Get the origin from headers or fallback to server variable
    if (isset($request_headers['Origin'])) {
      $origin = $request_headers['Origin'];
    } elseif (isset($_SERVER['HTTP_ORIGIN'])) {
      $origin = $_SERVER['HTTP_ORIGIN'];
    } else {
      $origin = '';
    }

    // Set the origin to the requesting origin (not wildcard) to work with credentials
    if ($origin) {
      header('Access-Control-Allow-Origin: ' . $origin);
      header('Access-Control-Allow-Credentials: true');
      header('Access-Control-Allow-Headers: Authorization, Content-Type');
      header('Access-Control-Allow-Methods: *');

      // Handle preflight requests
      if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit(0);
      }
    }

    $response['status_code'] = $status_code;
    $response['status_message'] = $status_message;
    $response['data'] = $data;
    $json_response = json_encode($response);
    if ($json_response === false) {
      die('json encode ERROR : ' . json_last_error_msg());
    }
    // Affichage de la réponse (Retourné au client)
    echo $json_response;
    die();
  }

  static function keyExists($arr, $key)
  {
    return array_key_exists($key, $arr);
  }
}
?>
