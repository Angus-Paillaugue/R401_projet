<?php
require_once __DIR__ . '/../modele/JoueurDAO.php';
require_once __DIR__ . '/../lib/error.php';

class ModifierUnJoueur
{
  private $DAO;
  private $joueur;

  public function __construct($joueur)
  {
    $this->joueur = $joueur;
    $this->DAO = new JoueurDAO();
  }

  public function execute()
  {
    return $this->DAO->update($this->joueur);
  }
}

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//   if (!isset($_POST['id'])) {
//     ErrorHandling::setFatalError('ID du joueur non valide');
//   }

//   if (
//     !isset($_POST['nom']) ||
//     !isset($_POST['prenom']) ||
//     !isset($_POST['licence']) ||
//     !isset($_POST['date_de_naissance']) ||
//     !isset($_POST['taille']) ||
//     !isset($_POST['poids'])
//   ) {
//     ErrorHandling::setError('Veuillez remplir tous les champs');
//     header(
//       'Location: /vue/dashboard/edit-joueur.php?id=' . $_POST['id'],
//       true,
//       303
//     );
//     exit();
//   }

//   $joueur = new Joueur(
//     $_POST['nom'],
//     $_POST['prenom'],
//     $_POST['licence'],
//     $_POST['date_de_naissance'],
//     $_POST['taille'],
//     $_POST['poids']
//   );
//   $joueur->setId($_POST['id']);

//   $modifierUnJoueur = new ModifierUnJoueur($joueur);
//   $modifierUnJoueur->execute();

//   header('Location: /vue/dashboard/joueur.php?id=' . $_POST['id'], true, 303);
//   exit();
// }

?>
