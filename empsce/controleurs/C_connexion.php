<?php
require_once "C_menu.php";

class C_connexion {
    private $pdo;

    public function __construct() 
    {
       
        try 
        {
            $dsn = "mysql:host=localhost;dbname=empsce1;charset=utf8";
            $this->pdo = new PDO($dsn, "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch (PDOException $e) 
        {
            echo("Échec lors de la connexion : " . $e->getMessage());
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function action_afficher($error = '') {
        if ($error !== '') {
            $_SESSION['error'] = $error;
        }
        require_once "vues/v_connexion.php";
    }

    public function action_login($login, $mdp) {
        if (!$login || !$mdp) {
            $this->action_afficher("Veuillez remplir tous les champs.");
            return;
        }

        
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE login = :login");
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($mdp, $user['mdp'])) {
                session_regenerate_id(true); 
                $_SESSION['user_id'] = $user['id']; 
                $_SESSION['login'] = $user['login'];
                header("Location: index.php?page=accueil");
                exit();
            } else {
                $this->action_afficher("Mot de passe incorrect.");
            }
        } else {
            $this->action_afficher("Utilisateur non trouvé.");
        }
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
