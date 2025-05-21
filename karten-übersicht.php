<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartenübersicht</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Kartenübersicht</h1>
    <ul class="card-list">
        <?php
        // Datenbankverbindung herstellen
        $conn = new mysqli("localhost", "root", "", "karteikarten");

        // Verbindung prüfen
        if ($conn->connect_error) {
            die("Verbindung fehlgeschlagen: " . $conn->connect_error);
        }

        // Deck-ID abrufen (z. B. aus der URL)
        $deck_id = isset($_GET['deck_id']) ? (int)$_GET['deck_id'] : 0;



        // Deck-Name ermitteln
        $stmt = $conn->prepare('SELECT $deck_name FROM decks WHERE id = ?');
        $stmt->bind_param('i', $deck_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $deck = $result->fetch_assoc();

        if (!$deck) {
            die("Deck nicht gefunden.");
        }

        // Deck-Name validieren und zusammensetzen
        $deck_name = $deck['deck_name'];
        if (!preg_match('/^deck_[a-zA-Z0-9_]+$/', $deck_name)) {
            die("Ungültiger Deckname.");
        }

        // Karten aus der Tabelle des Decks abrufen
        $query = "SELECT id, original, uebersetzung FROM `$deck_name`";
        $result = $conn->query($query);

        if ($result === false) {
            die("Fehler beim Abrufen der Karten: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            // Karten anzeigen
            while ($karte = $result->fetch_assoc()): ?>
                <li class="card-item">
                    <span>Original: <?php echo htmlspecialchars($karte['original']); ?></span><br>
                    <span>Übersetzung: <?php echo htmlspecialchars($karte['uebersetzung']); ?></span>
                    <form method="POST" action="karte-loeschen.php" onsubmit="return confirm('Möchten Sie diese Karte wirklich löschen?');">
                        <input type="hidden" name="karte_id" value="<?php echo htmlspecialchars($karte['id']); ?>">
                        <button type="submit" class="delete-button">Löschen</button>
                    </form>
                </li>
        <?php endwhile;
        } else {
            echo "<p>Keine Karten in diesem Deck gefunden.</p>";
        }

        // Ressourcen freigeben
        $stmt->close();
        $conn->close();
        ?>
    </ul>
</body>

</html>