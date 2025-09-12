<?php
require_once "C_menu.php";

class C_connexion {
    private $idConnexion;

    public function __construct() {
        $this->idConnexion = mysqli_connect('localhost', 'root', '', 'empsce1');
        if (!$this->idConnexion) {
            die("Erreur de connexion à la base de données");
        }
        mysqli_set_charset($this->idConnexion, "utf8");

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function action_afficher($error = '') {
        // On met le message d'erreur dans une variable globale ou session pour la vue
        if ($error !== '') {
            $_SESSION['error'] = $error;
        }
        require_once "vues/v_connexion.php";
    }

    // action_login prend les paramètres $login et $mdp
    public function action_login($login, $mdp) {
        if (!$login || !$mdp) {
            $this->action_afficher("Veuillez remplir tous les champs.");
            return;
        }

        // Protection contre injection SQL
        $login_safe = mysqli_real_escape_string($this->idConnexion, $login);

        $mdp_hashed = hash('sha256', $mdp);

        $query = "SELECT * FROM utilisateur WHERE login = '$login_safe' and mdp= '$mdp_hashed'";
        $result = mysqli_query($this->idConnexion, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            
                $_SESSION['login'] = $login_safe;
                header("Location: index.php?page=accueil");
                exit();
        } 
        else {
                $this->action_afficher("Mot de passe incorrect.");
        }
       

        /*
         // Protection contre injection SQL
        $login_safe = mysqli_real_escape_string($this->idConnexion, $login);

        $query = "SELECT * FROM utilisateur WHERE login = '$login_safe'";
        $result = mysqli_query($this->idConnexion, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            $mdp_hashed = hash('sha256', $mdp);
            
            if ($mdp_hashed == $user['mdp']) {
                $_SESSION['login'] = $user['login'];
                header("Location: index.php?page=accueil");
                exit();
            } else {
                $this->action_afficher("Mot de passe incorrect.");
            }
        } else {
            $this->action_afficher("Utilisateur non trouvé.");
        }

        */
    }

    public function isLoggedOn() {
        return isset($_SESSION['login']) && $_SESSION['login'] !== '';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?page=connexion");
        exit();
    }
}
?>
