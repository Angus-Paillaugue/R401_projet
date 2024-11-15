<?php
require_once __DIR__ . '/../modele/Joueur.php';
require_once __DIR__ . '/../modele/JoueurDAO.php';
require_once __DIR__ . '/../lib/error.php';

class CreerUnJoueur
{
  private $nom;
  private $prenom;
  private $numero_licence;
  private $date_naissance;
  private $taille;
  private $poids;
  private $DAO;

  public function __construct(
    $nom,
    $prenom,
    $numero_licence,
    $date_naissance,
    $taille,
    $poids
  ) {
    $this->nom = $nom;
    $this->prenom = $prenom;
    $this->numero_licence = $numero_licence;
    $this->date_naissance = $date_naissance;
    $this->taille = $taille;
    $this->poids = $poids;
    $this->DAO = new JoueurDAO();
  }

  public function execute()
  {
    $joueur = new Joueur(
      $this->nom,
      $this->prenom,
      $this->numero_licence,
      $this->date_naissance,
      $this->taille,
      $this->poids
    );
    $insertedRowId = $this->DAO->insert($joueur);
    $joueur->setId(intval($insertedRowId));
    return $joueur;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (
    !isset($_POST['nom']) ||
    !isset($_POST['prenom']) ||
    !isset($_POST['licence']) ||
    !isset($_POST['date_de_naissance']) ||
    !isset($_POST['taille']) ||
    !isset($_POST['poids'])
  ) {
    ErrorHandling::setError('Tous les champs sont obligatoires');
    header('Location: /vue/dashboard/add-joueur.php', true, 303);
    exit();
  }
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $licence = $_POST['licence'];
  $date_de_naissance = $_POST['date_de_naissance'];
  $taille = $_POST['taille'];
  $poids = $_POST['poids'];

  $joueur = new CreerUnJoueur(
    $nom,
    $prenom,
    $licence,
    $date_de_naissance,
    $taille,
    $poids
  );
  $joueur = $joueur->execute();

  header(
    'Location: /vue/dashboard/joueur.php?id=' . $joueur->getId(),
    true,
    303
  );
  exit();
}
?>
