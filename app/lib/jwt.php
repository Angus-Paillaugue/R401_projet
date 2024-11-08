<?php

/**
 * Class JWT
 *
 * A simple implementation of JSON Web Token (JWT) encoding and decoding.
 *
 * @package School\Lib
 */
class JWT
{
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

    $secret = 'key'; // You should use a more secure key in production

    $signature = hash_hmac(
      'sha256',
      "$base64UrlHeader.$base64UrlPayload",
      $secret,
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
    $secret = 'key';

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
      $secret,
      true
    );
    $base64UrlSignature = JWT::base64UrlEncode($signature);

    if ($base64UrlSignature === $signature_provided) {
      $payload = json_decode(base64_decode($payload), true);
      if (isset($payload['exp']) && $payload['exp'] > time()) {
        return $payload;
      }
    }

    return false;
  }
}
?>
