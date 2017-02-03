<?php
require __DIR__ . '/popschool-connect.php';

$erreurs = [];

if($_GET['id'] && $_GET['del']){
  $stmt=null;
  $sql = "DELETE FROM eleves where id= :id";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue("id", $_GET['id']);
  try {
    $stmt->execute();
  } catch (Exception $e) {
    echo $e->getMessage();
    exit();
  }
}

if ($_POST) {
  if (!isset($_POST['prenom']) || empty(trim($_POST['prenom']))) {
    $erreurs[] = "Vous devez renseigner le champ prenom";
  } elseif (strlen($_POST['prenom']) <= 2) {
    $erreurs[] = "Vous devez renseigner un prenom de plus de 3 caractères";
  }

  if (!isset($_POST['nom']) || empty(trim($_POST['nom']))) {
    $erreurs[] = "Vous devez renseigner le champ nom";
  } elseif (strlen($_POST['nom']) <= 2) {
    $erreurs[] = "Vous devez renseigner un nom de plus de 3 caractères";
  }

  if (!isset($_POST['date_de_naissance']) || empty(trim($_POST['date_de_naissance']))) {
    $erreurs[] = "Vous devez renseigner le champ date de naissance";
  }

  if (!$erreurs) {
    try {
    	$conn->insert('eleves', array(
    		'prenom' => $_POST["prenom"],
        'nom' => $_POST["nom"],
        'date_de_naissance' => $_POST["date_de_naissance"],
    	));
    } catch (Exception $e) {
    	echo $e->getMessage();
    	exit();
    }
  }
}

$sqlselect = "SELECT * FROM eleves";
try {
	$stmt = $conn->query($sqlselect);
} catch (Exception $e) {
	echo $e->getMessage();
	exit();
}

?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <title>POPschool</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

<div class="container">
  <h1 style="color: green;">POPschool</h1>
  <p>Liste des élèves</p>
  <p>Inscrivez-vous si ce n'est pas déjà fait.</p>
  <div class="erreur">
  <?php
  foreach ($erreurs as $erreur) {
    echo $erreur . "<br />\n";
  }
  ?>
  </div>
  <br/>
  <form action="<?php echo basename(__FILE__); ?>" method="post" class="form-inline">
    <div class="form-group">
      <label>Prénom:</label>
      <input name="prenom" type="text" class="form-control"placeholder="Entrer votre Prénom" >
    </div>
    <div class="form-group">
      <label>Nom:</label>
      <input name="nom" type="text" class="form-control"placeholder="Entrer votre Nom" >
    </div>
    <div class="form-group">
      <label>Date de Naissance:</label>
      <input name="date_de_naissance" type="date" class="form-control" placeholder="aaaa-mm-jj" >
    </div>
    <button type="submit" class="btn btn-default">Envoyer</button>
  </form>
</div>
<br/>
<div class="container">
  <table class="table">
    <thead>
      <tr>
        <th>Prénom</th>
        <th>Nom</th>
        <th>Date de Naissance</th>
      </tr>
    </thead>
    <tbody>
      <?php   while ($row = $stmt->fetch()) { ?>
      <tr class="success">
        <td><?php	echo htmlentities ($row['prenom'])?></td>
        <td><?php	echo htmlentities ($row['nom'])?></td>
        <td><?php	echo htmlentities ($row['date_de_naissance'])?></td>
        <td><a href=<?php echo basename(__FILE__)."?id=".htmlentities($row['id'])."&del=true"?>>Supprimer</a></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<br/>
</body>
</html>
