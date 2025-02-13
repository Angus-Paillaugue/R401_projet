<?php
require_once __DIR__ . '/../lib/jwt.php';

// If route requiers auth, check if the user is logged in, else redirect to log-in
if (preg_match('/^\/vue\/dashboard/', $_SERVER['REQUEST_URI'])) {
  if (isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];
    $payload = JWT::validateJWT($token);
    if (!$payload) {
      redirect();
    }
    if ($payload['exp'] < time()) {
      redirect();
    }
  } else {
    redirect();
  }
}

function redirect()
{
  header('Location: /vue/log-in.php', true, 303);
  exit();
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
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="flex flex-col min-h-screen">

  <div class='p-2 hidden' id="nav">
    <nav class='flex flex-row gap-4 items-center px-8 py-4 container mx-auto rounded-xl bg-neutral-100 dark:bg-neutral-900 w-full border border-neutral-300/50 dark:border-neutral-900'>
      <div class='flex flex-row gap-4 items-center'></div>
    </nav>
  </div>

  <!-- Main Content -->
  <main class="grow px-2">
    <?php echo $content; ?>
  </main>

  <div class="p-2">
    <footer class="container w-full mx-auto px-4 py-2 rounded-xl bg-neutral-100 border border-neutral-300/50 dark:border-neutral-900 dark:bg-neutral-900">
      <p class="font-bold text-base">&copy; <?php echo date('Y'); ?> TFC</p>
    </footer>
  </div>

  <script type="module">
    import { httpRequest } from '/vue/js/http.js';
    import Components from '/vue/js/components.js';
    const isLogged = <?php echo $payload ? 'true' : 'false'; ?>;

    if (isLogged) {
      $('#nav').removeClass('hidden');
      $('#nav > nav > div').append([
        Components.Link({
          label: 'Dashboard',
          href: '/vue/dashboard',
        }),
        Components.Link({
          label: 'Joueurs',
          href: '/vue/dashboard/joueurs.php',
        }),
        Components.Link({
          label: 'Matchs',
          href: '/vue/dashboard/rencontres.php',
        }),
        Components.Link({
          label: 'Statistiques',
          href: '/vue/dashboard/statistiques.php',
        })
      ]);

      $('#nav > nav').append([
        Components.Link({
          label: 'Se déconnecter',
          href: '/vue/log-out.php',
          class: 'ml-auto',
        }),
        Components.Button({
          icon: 'sun',
          id: 'toggle-theme',
          variant: 'primary square',
          class: 'p-2',
        }),
      ]);
    }
  </script>

  <script src="/vue/js/theme.js"></script>
</body>
</html>
