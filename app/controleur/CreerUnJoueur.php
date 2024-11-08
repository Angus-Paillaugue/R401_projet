<?php
require_once '../modele/Joueur.php';
require_once '../modele/JoueurDAO.php';

class CreerUnJoueur {
  private $nom;
  private $prenom;
  private $numero_licence;
  private $date_naissance;
  private $taille;
  private $poids;
  private $statut;
  private $commentaire;
  private $DAO;

  public function __construct($nom, $prenom, $numero_licence, $date_naissance, $taille, $poids, $statut, $commentaire) {
    $this->nom = $nom;
    $this->prenom = $prenom;
    $this->numero_licence = $numero_licence;
    $this->date_naissance = $date_naissance;
    $this->taille = $taille;
    $this->poids = $poids;
    $this->statut = $statut;
    $this->commentaire = $commentaire;
    $this->DAO = new JoueurDAO();
  }

  public function exec() {
    $joueur = new Joueur($this->nom, $this->prenom, $this->numero_licence, $this->date_naissance, $this->taille, $this->poids, $this->statut, $this->commentaire);
    $insertedRow = $this->DAO->insert($joueur);
    $joueur->setId($insertedRow['id']);
    return $joueur;
  }
}
?>
