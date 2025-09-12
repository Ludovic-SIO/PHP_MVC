<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "controleurs/C_connexion.php";  // On la charge une seule fois ici
$auth = new C_connexion(); // On crée une seule instance pour tout le routeur

$page = $_GET['page'] ?? 'login';


switch ($page) {

    case "listeEmployes":
        if (!$auth->isLoggedOn()) {
            header("Location: index.php?page=connexion");
            exit();
        }

        require_once "controleurs/C_consulterEmployes.php";
        $controleur = new C_consulterEmployes();
        $service = $_GET['service'] ?? null;
        $controleur->action_listeEmployes($service);
        break;

    case "saisieEmploye":
        if (!$auth->isLoggedOn()) {
            header("Location: index.php?page=connexion");
            exit();
        }

        require_once "controleurs/C_ajouterEmploye.php";
        $controleur = new C_ajouterEmploye();
        $controleur->action_saisie();
        break;

    case "ajoutEmploye":
        if (!$auth->isLoggedOn()) {
            header("Location: index.php?page=connexion");
            exit();
        }

        require_once "controleurs/C_ajouterEmploye.php";
        $controleur = new C_ajouterEmploye();
        $controleur->action_ajout(
            $_POST["matricule"] ?? '',
            $_POST["nom"] ?? '',
            $_POST["prenom"] ?? '',
            $_POST["service"] ?? ''
        );
        break;

    case "connexion":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? '';
            $mdp = $_POST['mdp'] ?? '';
            $auth->action_login($login, $mdp);
        } else {
            $auth->action_afficher();  // affiche v_connexion.php
        }
        break;
    

    case "inscription":
        require_once "controleurs/C_inscription.php";
        $controleur = new C_inscription();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? '';
            $mdp = $_POST['mdp'] ?? '';
            $mdpConf = $_POST['mdpConf'] ?? '';
            $controleur->action_ajouterUtilisateur($login, $mdp, $mdpConf);
        } else {
            $controleur->action_afficher();
        }
        break;

    case "accueil":
        if (!$auth->isLoggedOn()) {
            header("Location: index.php?page=connexion");
            exit();
        }

        require_once "controleurs/C_accueil.php";
        $controleur = new C_accueil();
        $controleur->action_afficher();
        break;

    case "deconnexion":

        session_destroy();
        header("Location: index.php?page=login");
        exit();
        break;
        

    case "login":
    default:
        require_once "controleurs/C_login.php";
        $controleur = new C_login();
        $controleur->action_afficher();
        break;
}
