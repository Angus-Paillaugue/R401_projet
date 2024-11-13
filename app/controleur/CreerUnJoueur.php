<?php
require_once __DIR__ . '/../modele/Joueur.php';
require_once __DIR__ . '/../modele/JoueurDAO.php';

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
?>
