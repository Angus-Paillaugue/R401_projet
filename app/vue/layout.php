<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title ?? 'My Website'; ?></title>
  <link rel="stylesheet" href="/output.css">
  <link rel="apple-touch-icon" sizes="180x180" href="/static/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/static/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/static/favicon-16x16.png">
  <link rel="manifest" href="/static/site.webmanifest">
</head>
<body class="flex flex-col min-h-screen">
  <?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require_once __DIR__ . '/../lib/components.php';
  require_once __DIR__ . '/../lib/jwt.php';
  require_once __DIR__ . '/../lib/error.php';

  // Is logged in?
  if (isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];
    $payload = JWT::validateJWT($token);
    if ($payload) {
      echo "<div class='p-2'><nav class='flex flex-row gap-4 items-center px-8 py-4 max-w-screen-xl mx-auto rounded-xl bg-neutral-900 w-full'>";
      Components::Link([
        'label' => 'Dashboard',
        'href' => '/dashboard',
      ]);
      Components::Link([
        'label' => 'Se déconnecter',
        'href' => '/log-out.php',
        'class' => 'ml-auto',
      ]);
      echo '</nav></div>';
    }
  }
  ?>

  <!-- Main Content -->
  <main class="grow px-2">
    <?php echo $content; ?>
  </main>

  <div class="p-2">
    <footer class="max-w-screen-xl w-full mx-auto px-4 py-2 rounded-xl bg-neutral-900">
      <p class="font-bold text-base">&copy; 2024 My Website</p>
    </footer>
  </div>
</body>
</html>
