<?php
class M_utilisateur {
    private $connexion;

    public function __construct() {
        $this->connexion = new mysqli('localhost', 'root', '', 'emp_sce1');
        if ($this->connexion->connect_error) {
            die("Erreur de connexion : " . $this->connexion->connect_error);
        }
        $this->connexion->set_charset("utf8");
    }

    public function ajouterUtilisateur($login, $mdp) {
        $login_sanitized = $this->connexion->real_escape_string($login);
        $mdp_hashed = hash('sha256', $mdp);

        $sql = "INSERT INTO utilisateur (login, mdp) VALUES ('$login_sanitized', '$mdp_hashed')";
        return $this->connexion->query($sql);
    }

    public function verifierUtilisateur($login, $mdp) {
        $login_sanitized = $this->connexion->real_escape_string($login);
        $mdp_hashed = hash('sha256', $mdp);

        $sql = "SELECT * FROM utilisateur WHERE login='$login_sanitized' AND mdp='$mdp_hashed'";
        $result = $this->connexion->query($sql);
        return $result->num_rows === 1;
    }

    public function isLoggedON()
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $nom_utilisateur = $_POST['nom_utilisateur'];
              $mot_de_passe = $_POST['mot_de_passe'];
          
              $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE nom_utilisateur = ?");
              $stmt->bind_param("s", $nom_utilisateur);
              $stmt->execute();
              $result = $stmt->get_result();
          
              if ($result->num_rows > 0) {
                  $row = $result->fetch_assoc();
                  

                  if (password_verify($mot_de_passe, $row['Mot_de_passe'])) {
                    $_SESSION['pseudoU'] = $nom_utilisateur;
                    $_SESSION['id_utilisateur'] = $row['id_utilisateur']; 
                    $message = "<script>alert('Connexion réussie !'); window.location.href='accueil.php';</script>";
                }
                 else {
                      $message = "<script>alert('Mot de passe incorrect');</script>";
                  }
              } else {
                  $message = "<script>alert('Nom d\'utilisateur incorrect');</script>";
              }
              
          }
          }
          
          
          catch(exeption $e){
            $message = "<script>alert('une erreur c'est produite veuillez réessayer plus tard !'); window.location.href='accueil.php';</script>";
          
            }
        
    }
}
?>
