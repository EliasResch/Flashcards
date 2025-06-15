<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deck Löschen</title>
</head>

<body>
    <?php
    // Verbindung zur Datenbank herstellen
    $conn = new mysqli("localhost", "USER443003", "Flashcards1234", "db_443003_2");

    if ($conn->connect_error) {
        die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deck_name'])) {
        $deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['deck_name']);
        $table_name = "deck_" . $deck_name;

        // Tabelle löschen
        $sql = "DROP TABLE IF EXISTS $table_name";
        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "Fehler beim Löschen des Decks: " . $conn->error;
        }
    }

    $conn->close();
    ?>
</body>

</html>