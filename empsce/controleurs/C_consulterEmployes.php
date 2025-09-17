<?php
require_once "C_menu.php";
require_once "modeles/M_service.php";
require_once "modeles/M_employe.php";

class C_consulterEmployes
{
    private $data;
    private $modeleEmploye;
    private $modeleService;
    private $controleurMenu;
    public function __construct()
    {
        $this->data = array();
        $this->controleurMenu= new C_menu();
        $this->modeleService= new M_service();
        $this->modeleEmploye = new M_employe();
    }

    public function action_listeEmployes($codeService)
    {
        $this->controleurMenu->FillData($this->data);
        if ($codeService=="all")
        {
            $this->data['leService']=null;
            $this->data['lesEmployes'] = $this->modeleEmploye->GetListe();
        }
        else
        {
            $this->data['leService']=$this->modeleService->GetService($codeService);
            $this->data['lesEmployes'] = $this->modeleEmploye->GetListeService($codeService);
        }
        require_once "vues/v_listeEmployes.php";
    }


    public function action_modifierEmploye($matricule)
    {
        $this->controleurMenu->FillData($this->data);
        $employe = $this->modeleEmploye->GetEmploye($matricule);
        if (!$employe) 
        {
            echo("Employé non trouvé");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $service = $_POST['service'] ?? '';

       
            $this->modeleEmploye->UpdateEmploye($matricule, $nom, $prenom, $service);

            header("Location: index.php?action=listeEmployes&codeService=all");
            exit;
        }

        $this->data['employe'] = $employe;
        require_once "vues/v_modifierEmploye.php";
    }

    public function action_supprimerEmploye($matricule)
    {
        $this->modeleEmploye->DeleteEmploye($matricule);
        header("Location: index.php?action=listeEmployes&codeService=all");
        exit;
    }
}
?>