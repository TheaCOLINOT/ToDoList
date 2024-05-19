<?php
// Configuration de la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);
// Pour que la connexion fonctionne il faut ces 4 variables

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}
// Si la variable de connexion rencontre une erreur on affiche l'echec

// Ajouter une nouvelle tâche si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task'])) {
    // if verifie si le parametre TASK est bien envoyé et que la methode demandée est POST
    $task = $conn->real_escape_string($_POST['task']);
    $sql = "INSERT INTO todos (title) VALUES ('$task')";
    // la valeur TITLE est écrite par l'utilisateur
    if ($conn->query($sql) === TRUE) {
        echo "Nouvelle tâche ajoutée avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Récupérer les tâches existantes
$sql = "SELECT title FROM todos";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste de Tâches</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Ma Liste de Tâches</h1>
<div id="todo-container">
    <form method="POST" action="">
        <input type="text" name="task" placeholder="Nouvelle tâche" required>
        <button type="submit">Ajouter une tâche</button>
    </form>
    <ul id="todo-list">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['title']) . " </li>";
            }
        } else {
            echo "<li>Aucune tâche</li>";
        }
        ?>
    </ul>
</div>
</body>
</html>

