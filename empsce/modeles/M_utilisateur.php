<?php
class M_utilisateur {
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
    }

    public function getUtilisateurParLogin($login) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE login = :login");
        $stmt->execute(['login' => $login]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function utilisateurExiste($login) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE login = :login");
        $stmt->execute(['login' => $login]);
        return $stmt->fetchColumn() > 0;
    }

    public function ajouterUtilisateur($login, $mdp_hashed) {
        $stmt = $this->pdo->prepare("INSERT INTO utilisateur (login, mdp) VALUES (:login, :mdp)");
        return $stmt->execute(['login' => $login, 'mdp' => $mdp_hashed]);
    }
    
    
}
?>
