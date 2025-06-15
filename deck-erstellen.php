    <?php
    // Verbindung zur Datenbank herstellen
    $conn = new mysqli("localhost", "USER443003", "Flashcards1234", "db_443003_2");
     
    // Überprüfen der Datenbankverbindung
    if ($conn->connect_error) {
        die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
    }
    //Erstellen eines neuen Decks
     
    $deck_name = "";
    $cards = [];
    $error = "";
     
     
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deck_name'])) {
     
        $deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['deck_name']);
        if (empty($deck_name)) {
            $error = "Bitte geben Sie einen gültigen Deck-Namen ein.";
        } else {
     
            $table_name = "deck_" . $deck_name;
     
     
            $result = $conn->query("SHOW TABLES LIKE '$table_name'");
            if ($result->num_rows > 0) {
                $error = "Ein Deck mit diesem Namen existiert bereits.";
            } else {
     
                $sql = "CREATE TABLE $table_name (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    original TEXT NOT NULL,
                    uebersetzung TEXT NOT NULL
                )";
     
     
                if ($conn->query($sql) === TRUE) {
                    header("Location: deck-erstellen.php?deck=" . urlencode($deck_name));
                    exit();
                } else {
                    $error = "Fehler beim Erstellen des Decks: " . $conn->error;
                }
            }
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <title>Deck Erstellen <?php echo htmlspecialchars($deck_name); ?></title>
    </head>
    <body>
        <div class="container mt-5">
            <?php if (empty($deck_name)): ?>
                <h1 class="text-center">Neues Deck erstellen</h1>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST" class="mt-4">
                    <div class="mb-3">
                        <input type="text" name="deck_name" id="deck_name" class="styled-input" placeholder="Deck-Name" required>
                    </div>
                    <div id="straight">
                        <button type="submit" class="cta-button">
                            Deck erstellen
                        </button>
                        <button type="reset" class="cta-button" id="backbutton">Zurück</button>
                    </div>
                </form>
            <?php else: ?>
                <h1 class="text-center">Deck: <?php echo htmlspecialchars($deck_name); ?></h1>
                <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    </div>
                <form method="POST" class="mt-5">
                    <h3>Neue Karte hinzufügen</h3>
                    <button type="submit" class="btn btn-success">Karte hinzufügen</button>
                    <a href="index.php" class="btn btn-secondary">Zurück</a>
                </form>
            <?php endif; ?>
        </div>
        <script>
            // JavaScript für den "Zurück"-Button
            document.getElementById('backbutton').addEventListener('click', function() {
                window.location.href = 'index.php';
            });
        </script>
    </body>
    </html>