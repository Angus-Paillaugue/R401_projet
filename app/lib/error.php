<?php
if (!isset($_SESSION)) {
  session_start();
}

require_once __DIR__ . '/error.php';

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

  /**
   * Outputs a fatal error message and terminates the script.
   *
   * @param string $message The error message to be displayed.
   * @return void
   */
  public static function setFatalError($message)
  {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Fatal error</title>
      <link rel="stylesheet" href="/output.css">
      <link rel="apple-touch-icon" sizes="180x180" href="/static/apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="/static/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="/static/favicon-16x16.png">
      <link rel="manifest" href="/static/site.webmanifest">
    </head>
    <body class="flex flex-col min-h-screen">

      <main class="grow px-2">
        <div class="max-w-xl w-full mx-auto my-10">';
    Components::Alert([
      'text' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
    ]);
    echo '
        </div>
      </main>

      <div class="p-2">
        <footer class="max-w-screen-xl w-full mx-auto px-4 py-2 rounded-xl bg-neutral-900">
          <p class="font-bold text-base">&copy; 2024 My Website</p>
        </footer>
      </div>
    </body>
    </html>
    ';
    exit();
  }
}
?>
