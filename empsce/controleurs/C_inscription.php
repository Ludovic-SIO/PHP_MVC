<?php
require_once "C_menu.php";

class C_inscription
{
    private $data;
    private $controleurMenu;
    private $idConnexion;

    public function __construct()
    {
        $this->data = array();
        $this->controleurMenu = new C_menu();

        $this->idConnexion = mysqli_connect('localhost', 'root', '', 'empsce1');
        if (!$this->idConnexion) {
            die("Erreur de connexion à la base de données");
        }
        mysqli_set_charset($this->idConnexion, "utf8");

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function action_afficher()
    {
        $this->controleurMenu->FillData($this->data);
        require_once "vues/v_inscription.php";
    }

    public function action_ajouterUtilisateur($login, $mdp, $mdpConf)
    {
        $this->controleurMenu->FillData($this->data);
    
        if ($mdp !== $mdpConf) {
            $this->data['leMessage'] = "Les mots de passe ne correspondent pas.";
            require_once "vues/v_message.php";
            return;
        }
    
        $login_sanitized = mysqli_real_escape_string($this->idConnexion, $login);
    
        $sqlCheck = "SELECT * FROM utilisateur WHERE login = '$login_sanitized'";
        $resultCheck = mysqli_query($this->idConnexion, $sqlCheck);
    
        if (mysqli_num_rows($resultCheck) > 0) {
            $this->data['leMessage'] = "Ce login est déjà utilisé.";
            require_once "vues/v_message.php";
            return;
        }
    
        $mdp_hashed = password_hash('sha256', $mdp);
        $sql = "INSERT INTO utilisateur (login, mdp) VALUES ('$login_sanitized', '$mdp_hashed')";
        $result = mysqli_query($this->idConnexion, $sql);
    
        if ($result) {
            $this->data['leMessage'] = "Inscription réussie, vous allez être redirigé vers la page de connexion.";
            header("Location: index.php?page=connexion");
            exit();
        } 
        else 
        {
            $this->data['leMessage'] = "Erreur lors de l'enregistrement.";
            require_once "vues/v_message.php";
        }
    }
    
}
?>

