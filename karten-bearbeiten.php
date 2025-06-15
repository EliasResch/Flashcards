    <?php
    // Verbindung zur Datenbank herstellen
    $conn = new mysqli("localhost", "USER443003", "Flashcards1234", "db_443003_2");
     
    // Überprüfen der Datenbankverbindung
    if ($conn->connect_error) {
        die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
    }
     
    // Alle Deck-Tabellen abrufen, um sie aufzulisten
    $decks = [];
    $result = $conn->query("SHOW TABLES LIKE 'deck_%'");
    while ($row = $result->fetch_array()) {
        $decks[] = $row[0];
    }
     
     
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
     
        if (isset($_POST['update_card'])) {
     
            $deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['deck_name']);
            $card_id = (int)$_POST['card_id'];
            $original = $conn->real_escape_string($_POST['original']);
            $uebersetzung = $conn->real_escape_string($_POST['uebersetzung']);
     
            // SQL-Befehl zum Aktualisieren der Karte
            $sql = "UPDATE $deck_name SET original='$original', uebersetzung='$uebersetzung' WHERE id=$card_id";
            $conn->query($sql);
     
        } elseif (isset($_POST['delete_card'])) {
            $deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['deck_name']);
            $card_id = (int)$_POST['card_id'];
     
            // SQL-Befehl zum Löschen der Karte
            $sql = "DELETE FROM $deck_name WHERE id=$card_id";
            $conn->query($sql);
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
     
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwq+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
      <link rel="stylesheet" href="style.css">
     
      <title>Flashcards</title>
    </head>
    <body>
        <h1>Karten Bearbeiten</h1>
     
        <?php foreach ($decks as $deck): ?>
            <div class="deck-card">
                <a class="bearbeiten" href="karten-bearbeiten.php?deck=<?php echo urlencode($deck); ?>"><?php echo htmlspecialchars(str_replace("deck_", "", $deck)) ?></a>
            </div>
        <?php endforeach; ?>
     
        <?php if (isset($_GET['deck'])): ?>
            <?php
     
            $deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['deck']);
            $cards = $conn->query("SELECT * FROM $deck_name");
            ?>
            <h2>Deck: <?php echo htmlspecialchars(str_replace("deck_", "", $deck_name)); ?></h2>
     
            <?php while ($card = $cards->fetch_assoc()): ?>
                <form method="POST">
                    <input type="hidden" name="deck_name" value="<?php echo htmlspecialchars($deck_name); ?>">
                    <input type="hidden" name="card_id" value="<?php echo htmlspecialchars($card['id']); ?>">
                    <input type="text" name="original" class="styled-input" value="<?php echo htmlspecialchars($card['original']); ?>">
                    <input type="text" name="uebersetzung" class="styled-input" value="<?php echo htmlspecialchars($card['uebersetzung']); ?>">
                    <button type="submit" class="cta-button" name="update_card">Speichern</button>
                    <button type="submit" class="cta-button" name="delete_card">Löschen</button>
                    <input type="button" class="cta-button" id="startseite" value="Zur Startseite" onclick="window.location.href='index.php';">
                </form>
            <?php endwhile; ?>
        <?php endif; ?>
    </body>
    </html>