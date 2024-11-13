<?php
require_once __DIR__ . '/../../lib/error.php';
require_once __DIR__ . '/../../controleur/SupprimerUnCommentaire.php';
require_once __DIR__ . '/../../controleur/RecupererUnCommentaire.php';
require_once __DIR__ . '/../../modele/Commentaire.php';
require_once __DIR__ . '/../../modele/CommentaireDAO.php';

if (!isset($_GET['id'])) {
  ErrorHandling::setFatalError('ID du commentaire non fourni');
}

if (!intval($_GET['id'])) {
  ErrorHandling::setFatalError('ID du commentaire non valide');
}

try {
  $commentaire = (new RecupererUnCommentaire($_GET['id']))->execute();
} catch (Exception $e) {
  ErrorHandling::setFatalError($e->getMessage());
}

(new SupprimerUnCommentaire($commentaire))->execute();

$redirect = urldecode($_GET['redirect']) ?? '/dashboard/commentaires.php';

header('Location: ' . $redirect);
?>
