<?php
require_once __DIR__ . '/../../controleur/SupprimerUnCommentaire.php';
require_once __DIR__ . '/../../controleur/RecupererUnCommentaire.php';
require_once __DIR__ . '/../../modele/Commentaire.php';
require_once __DIR__ . '/../../modele/CommentaireDAO.php';

if (!isset($_GET['id'])) {
  throw new Exception('ID du commentaire non fourni');
}

$commentaire = (new RecupererUnCommentaire($_GET['id']))->execute();
(new SupprimerUnCommentaire($commentaire))->execute();

$redirect = urldecode($_GET['redirect']) ?? '/dashboard/commentaires.php';

header('Location: ' . $redirect);
?>
