<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title ?? 'My Website'; ?></title>
  <link rel="stylesheet" href="/output.css">
</head>
<body class="flex flex-col min-h-screen">
      <?php
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);
      require_once __DIR__ . '/../lib/components.php';
      require_once __DIR__ . '/../lib/jwt.php';

      // Is logged in?
      if (isset($_COOKIE['token'])) {
        $token = $_COOKIE['token'];
        $payload = JWT::validateJWT($token);
        if ($payload) {
          echo "<div class='p-2'><nav class='flex flex-row gap-4 items-center px-8 py-4 max-w-screen-xl mx-auto rounded-xl bg-neutral-900 text-neutral-100 w-full'>";
          Components::Link([
            'label' => 'Dashboard',
            'href' => '/dashboard',
            'class' => 'text-neutral-100 hover:text-neutral-400',
          ]);
          Components::Link([
            'label' => 'Se déconnecter',
            'href' => '/log-out.php',
            'class' => 'ml-auto text-neutral-100 hover:text-neutral-400',
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
      <p class="text-neutral-100 font-bold text-base">&copy; 2024 My Website</p>
    </footer>
  </div>
</body>
</html>
