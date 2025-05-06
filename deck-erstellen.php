<?php
// Verbindung zur Datenbank herstellen
$conn = new mysqli("localhost", "root", "", "karteikarten");

if ($conn->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
}

// Variablen initialisieren
$deck_name = "";
$cards = [];
$error = "";

// Deck erstellen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deck_name'])) {
    $deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['deck_name']);
    if (empty($deck_name)) {
        $error = "Bitte geben Sie einen gültigen Deck-Namen ein.";
    } else {
        $table_name = "deck_" . $deck_name;

        // Prüfen, ob die Tabelle bereits existiert
        $result = $conn->query("SHOW TABLES LIKE '$table_name'");
        if ($result->num_rows > 0) {
            $error = "Ein Deck mit diesem Namen existiert bereits.";
        } else {
            // Tabelle erstellen
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
    <title>Deck Ansicht - <?php echo htmlspecialchars($deck_name); ?></title>
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
                <button type="submit" class="cta-button">Deck erstellen</button>
                <a href="index.php" class="cta-button" id="deck-erstellen-zurück">Zurück</a>
            </form>
        <?php else: ?>
            <h1 class="text-center">Deck: <?php echo htmlspecialchars($deck_name); ?></h1>
            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($cards as $index => $card): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <div class="d-flex flex-column align-items-center">
                                <h2 class="text-primary">Original</h2>
                                <p class="fs-4"><?php echo htmlspecialchars($card['original']); ?></p>
                                <h2 class="text-success mt-4">Übersetzung</h2>
                                <p class="fs-4"><?php echo htmlspecialchars($card['uebersetzung']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <!-- Fortschrittsanzeige -->
            <div class="progress-indicator">
                <span id="progress">1/<?php echo count($cards); ?></span>
            </div>

            <form method="POST" class="mt-5">
                <h3>Neue Karte hinzufügen</h3>
                <div class="mb-3">
                    <label for="original" class="form-label">Original</label>
                    <input type="text" name="original" id="original" class="form-control" placeholder="Originaltext" required>
                </div>
                <div class="mb-3">
                    <label for="uebersetzung" class="form-label">Übersetzung</label>
                    <input type="text" name="uebersetzung" id="uebersetzung" class="form-control" placeholder="Übersetzung" required>
                </div>
                <button type="submit" class="btn btn-success">Karte hinzufügen</button>
                <a href="index.php" class="btn btn-secondary">Zurück</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>