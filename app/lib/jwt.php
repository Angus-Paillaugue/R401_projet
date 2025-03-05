<?php

/**
 * Class JWT
 *
 * @initialization Call JWT::init() before using any JWT methods
 *
 * A simple implementation of JSON Web Token (JWT) encoding and decoding.
 *
 */
class JWT
{
  private static $secret = 'KEY';

  public static function init()
  {
    self::$secret = array_key_exists('JWT_SECRET', $_ENV)
      ? $_ENV['JWT_SECRET']
      : 'KEY';
  }

  /**
   * Encodes data to Base64 URL format.
  /**
   * Encodes data to Base64 URL format.
   *
   * @param string $data The data to encode.
   * @return string The Base64 URL encoded data.
   */
  private static function base64UrlEncode($data)
  {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
  }

  /**
   * Generates a JSON Web Token (JWT) from the given payload.
   *
   * @param array $payload The payload data to include in the JWT.
   * @return string The generated JWT.
   */
  public static function generateJWT($payload)
  {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $base64UrlHeader = JWT::base64UrlEncode($header);

    $base64UrlPayload = JWT::base64UrlEncode(json_encode($payload));

    $signature = hash_hmac(
      'sha256',
      "$base64UrlHeader.$base64UrlPayload",
      JWT::$secret,
      true
    );
    $base64UrlSignature = JWT::base64UrlEncode($signature);

    return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
  }

  /**
   * Validates a JSON Web Token (JWT).
   *
   * @param string $jwt The JWT to validate.
   * @return mixed The payload if the JWT is valid and not expired, false otherwise.
   */
  public static function validateJWT($jwt)
  {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) {
      return false;
    }

    $header = $parts[0];
    $payload = $parts[1];
    $signature_provided = $parts[2];

    $base64UrlHeader = JWT::base64UrlEncode(base64_decode($header));
    $base64UrlPayload = JWT::base64UrlEncode(base64_decode($payload));

    $signature = hash_hmac(
      'sha256',
      "$base64UrlHeader.$base64UrlPayload",
      JWT::$secret,
      true
    );
    $base64UrlSignature = JWT::base64UrlEncode($signature);

    if ($base64UrlSignature === $signature_provided) {
      $payload = json_decode(base64_decode($payload), true);
      if (isset($payload['exp']) && $payload['exp'] > time()) {
        return $payload;
      } else {
        throw new Exception('Expired token');
      }
    }

    return false;
  }
}
?>
