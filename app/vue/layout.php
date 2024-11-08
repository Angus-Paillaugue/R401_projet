
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title ?? 'My Website'; ?></title>
  <link rel="stylesheet" href="../../output.css">
</head>
<body class="flex flex-col min-h-screen gap-4">
  <nav class="flex flex-row gap-4 items-center px-4 py-2">
    <?php
    require_once '../../lib/components.php';
    require_once '../../lib/jwt.php';

    Components::Link(['href' => 'log-in.php', 'label' => 'Login']);
    Components::Link(['href' => 'sign-up.php', 'label' => 'Sign Up']);
    if (isset($_COOKIE['token'])) {
      $token = $_COOKIE['token'];
      $payload = JWT::validateJWT($token);
      if ($payload) {
        Components::Link([
          'label' => 'Page restreinte',
          'href' => 'restricted.php',
        ]);
        Components::Link([
          'label' => 'Se déconnecter',
          'href' => 'log-out.php',
          'class' => 'ml-auto',
        ]);
      }
    }
    ?>
  </nav>

  <!-- Main Content -->
  <main class="grow p-2">
    <?php echo $content; ?>
  </main>

  <footer class="max-w-screen-xl w-full mx-auto px-4 py-2 rounded-xl bg-neutral-900 mb-2">
    <p class="text-neutral-100 font-bold text-base">&copy; 2024 My Website</p>
  </footer>
</body>
</html>
