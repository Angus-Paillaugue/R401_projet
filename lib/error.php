<?php
if (!isset($_SESSION)) {
  session_start();
}

/**
 * Class ErrorHandling
 *
 * This class provides static methods to handle error messages using PHP sessions.
 */
class ErrorHandling
{
  /**
   * Sets an error message in the session.
   *
   * @param string $message The error message to be set.
   */
  public static function setError($message)
  {
    $_SESSION['error'] = $message;
  }

  /**
   * Retrieves and clears the error message from the session.
   *
   * @return string|null The error message if set, or null if no error message is found.
   */
  public static function getError()
  {
    if (isset($_SESSION['error'])) {
      $error = $_SESSION['error'];
      unset($_SESSION['error']); // Clear error after retrieving
      return $error;
    }
    return null;
  }

  /**
   * Checks if an error message is set in the session.
   *
   * @return bool True if an error message is set, false otherwise.
   */
  public static function hasError()
  {
    return isset($_SESSION['error']);
  }
}
?>
