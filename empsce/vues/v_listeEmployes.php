<?php include_once ('v_entete.php'); ?>
<div class="container">
<?php
    if (is_null($this->data['leService']))
    {
        echo '<h2>Tous les services</h2>';
    }
    else
    {
        echo '<h2>Service '.$this->data['leService'][0]->GetDesignation().'</h2>';
    }
?>

<!-- Barre de recherche -->
<div class="mb-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un employé...">
</div>

<table class="table table-striped table-bordered" id="employeeTable">
    <thead>
        <tr>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Service</th>
        </tr>
    </thead>
<tbody>

<?php
   foreach ($this->data['lesEmployes'] as $unEmploye)
   {
       echo '<tr>';
       echo '<td>' . htmlspecialchars($unEmploye->GetMatricule()) . '</td>';
       echo '<td>' . htmlspecialchars($unEmploye->GetNom()) . '</td>';
       echo '<td>' . htmlspecialchars($unEmploye->GetPrenom()) . '</td>';
       echo '<td>' . htmlspecialchars($unEmploye->GetService()) . '</td>';
       echo '<td>
               <a href="modifier.php?matricule=' . urlencode($unEmploye->GetMatricule()) . '" class="btn btn-sm btn-primary">Modifier</a>
               <a href="supprimer.php?matricule=' . urlencode($unEmploye->GetMatricule()) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Confirmer la suppression ?\')">Supprimer</a>
             </td>';
       echo '</tr>';
   }
   ?>
   
</tbody>
</table>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#employeeTable tbody tr');

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

<?php include_once ('v_piedPage.php'); ?>
