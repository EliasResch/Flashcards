<!DOCTYPE html>
<html lang="en">
<body>
    <?php
    // Verbindung zur Datenbank herstellen
    $conn = new mysqli("localhost", "USER443003", "Flashcards1234", "db_443003_2");

    // Überprüfen der Datenbankverbindung
    if ($conn->connect_error) {
        die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
    }

    //Löschen eines Decks
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deck_name'])) {
       
        $deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['deck_name']);
        $table_name = "deck_" . $deck_name;

        
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