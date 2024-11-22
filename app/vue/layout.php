<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../lib/components.php';
require_once __DIR__ . '/../lib/jwt.php';
require_once __DIR__ . '/../lib/error.php';

if (isset($_COOKIE['token'])) {
  $token = $_COOKIE['token'];
  $payload = JWT::validateJWT($token);
  if (!$payload) {
    redirect();
  }
} else {
  redirect();
}

function redirect()
{
  if (str_starts_with($_SERVER['PHP_SELF'], '/vue/dashboard')) {
    header('Location: /log-in.php', true, 303);
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title ?? 'TFC'; ?></title>
  <link rel="stylesheet" href="/vue/output.css">
  <link rel="apple-touch-icon" sizes="180x180" href="/vue/static/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/vue/static/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/vue/static/favicon-16x16.png">
  <link rel="manifest" href="/vue/static/site.webmanifest">
</head>
<body class="flex flex-col min-h-screen">
  <?php // Is logged in?

if ($payload) {
    echo "<div class='p-2'><nav class='flex flex-row gap-4 items-center px-8 py-4 max-w-screen-xl mx-auto rounded-xl bg-neutral-100 dark:bg-neutral-900 w-full border border-neutral-300/50 dark:border-neutral-900'><div class='flex flex-row gap-4 items-center'>";
    Components::Link([
      'label' => 'Dashboard',
      'href' => '/vue/dashboard',
    ]);
    Components::Link([
      'label' => 'Joueurs',
      'href' => '/vue/dashboard/joueurs.php',
    ]);
    Components::Link([
      'label' => 'Matchs',
      'href' => '/vue/dashboard/rencontres.php',
    ]);
    Components::Link([
      'label' => 'Statistiques',
      'href' => '/vue/dashboard/statistiques.php',
    ]);
    echo '</div>';
    Components::Link([
      'label' => 'Se déconnecter',
      'href' => '/vue/log-out.php',
      'class' => 'ml-auto',
    ]);

    Components::Button([
      'icon' => 'sun',
      'id' => 'toggle-theme',
      'variant' => 'primary square',
      'class' => 'p-2',
    ]);
    echo '</nav></div>';
  } else {
    redirect();
  } ?>

  <!-- Main Content -->
  <main class="grow px-2">
    <?php echo $content; ?>
  </main>

  <div class="p-2">
    <footer class="max-w-screen-xl w-full mx-auto px-4 py-2 rounded-xl bg-neutral-100 border border-neutral-300/50 dark:border-neutral-900 dark:bg-neutral-900">
      <p class="font-bold text-base">&copy; <?php echo date('Y'); ?> TFC</p>
    </footer>
  </div>

  <script src="/vue/theme.js"></script>
</body>
</html>
