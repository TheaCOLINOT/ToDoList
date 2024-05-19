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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['todo'])) {
// if verifie si le parametre TASK est bien envoyé et que la methode demandée est POST
    $task = $conn->real_escape_string($_POST['todo']);
    $sql = "INSERT INTO todos (title) VALUES ('$task')";
// la valeur TITLE est écrite par l'utilisateur
    if ($conn->query($sql) === TRUE) {
        echo "Nouvelle tâche ajoutée avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Ajouter une nouvelle tâche si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task']) && isset($_POST['id_todo'])) {
// if verifie si le parametre TASK est bien envoyé et que la methode demandée est POST
    $task = $conn->real_escape_string($_POST['task']);
    $todo = $conn->real_escape_string($_POST['id_todo']);
    $sql = "INSERT INTO tasks (title, id_todo) VALUES ('$task','$todo')";
// la valeur TITLE est écrite par l'utilisateur
    if ($conn->query($sql) === TRUE) {
        echo "Nouvelle tâche ajoutée avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Récupérer les tâches existantes
$sql = "SELECT id,title FROM todos";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // Boucle pour chaque liste trouvée
    while($list = $result->fetch_assoc()) {
        // ID de la liste
        $list_id = $list['id'];
        echo "<div class='list-container'>"; // Début du conteneur de la liste
        echo "<h2>" . htmlspecialchars($list['title']) . "</h2>"; // Afficher le nom de la liste

        // Récupérer les tâches de la liste spécifique
        $tasks_sql = "SELECT id, title, description, deadline FROM tasks WHERE id_todo = $list_id[0]";
        $tasks_result = $conn->query($tasks_sql);

        echo "<ul class='todo-list'>"; // Début de la liste des tâches
        if ($tasks_result->num_rows > 0) {
            // Boucle pour chaque tâche trouvée dans la liste
            while($task = $tasks_result->fetch_assoc()) {
                // Afficher chaque tâche avec sa date de création
                echo "<li>" . htmlspecialchars($task['title']) . " <span>(" . $task['description'] . ")</span></li>";
            }
        } else {
            // Afficher un message si aucune tâche n'est trouvée
            echo "<li>Aucune tâche</li>";
        }
        echo "</ul>"; // Fin de la liste des tâches

        // Formulaire pour ajouter une nouvelle tâche à la liste spécifique
        echo "<form method='POST' action='' class='add-task-form'>";
        echo "<input type='hidden' name='id_todo' value='$list_id[0]'>"; // Champ caché avec l'ID de la liste
        echo "<input type='text' name='task' placeholder='Nouvelle tâche' required>"; // Champ de saisie pour la nouvelle tâche
        echo "<button type='submit'>Ajouter une tâche</button>"; // Bouton pour soumettre le formulaire
        echo "</form>"; // Fin du formulaire

        echo "</div>"; // Fin du conteneur de la liste
    }
} else {
    // Afficher un message si aucune liste n'est trouvée
    echo "<p>Aucune liste</p>";
}
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
        <input type="text" name="todo" placeholder="Nouvelle tâche" required>
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

