<?php
// Überprüfen, ob ein Deck-Name über GET übergeben wurde, sonst zur Startseite weiterleiten
if (!isset($_GET['deck'])) {
    header("Location: index.php");
    exit();
}
 
$deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['deck']);
$table_name = "deck_" . $deck_name;
// Verbindung zur Datenbank herstellen
$conn = new mysqli("localhost", "USER443003", "Flashcards1234", "db_443003_2");

// Karten aus dem Deck abrufen
$cards = [];
// Fehler abfangen, falls die Tabelle nicht existiert
$result = $conn->query("SELECT * FROM `$table_name`");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cards[] = $row;
    }
}
 
// Neue Karte zum Deck hinzufügen, wenn das Formular gesendet wird
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['original'])) {
    $original = $conn->real_escape_string($_POST['original']);
    $uebersetzung = $conn->real_escape_string($_POST['uebersetzung']);
 
    $sql = "INSERT INTO `$table_name` (original, uebersetzung) VALUES ('$original', '$uebersetzung')";
 
    $conn->query($sql);
 
    // Nach dem Hinzufügen auf dieselbe Seite weiterleiten, um doppelte Einträge zu vermeiden
    $redirect_url = "deck-karten.php?deck=" . urlencode($deck_name);
    if(isset($_GET['displayOption'])) {
        $redirect_url .= "&displayOption=" . urlencode($_GET['displayOption']);
    }
    header("Location: " . $redirect_url);
    exit();
}

// Anzeigemodus aus der URL holen, Standard ist 'both'
$displayOption = $_GET['displayOption'] ?? 'both';

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deck: <?php echo htmlspecialchars($deck_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container mt-5 text-center">
        <h1>Deck: <?php echo htmlspecialchars($deck_name); ?></h1>

        <div id="flashcardCarousel" class="carousel slide" data-bs-ride="false">
            <div class="carousel-inner">
                <?php if (empty($cards)): ?>
                    <div class="carousel-item active">
                        <div class="d-flex flex-column align-items-center justify-content-center p-5" style="min-height: 200px;">
                            <p class="fs-4">Dieses Deck hat noch keine Karten.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($cards as $index => $card): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <div class="d-flex flex-column align-items-center justify-content-center p-4" style="min-height: 200px;">
                                <?php if ($displayOption === 'both' || $displayOption === 'original'): ?>
                                    <h2 class="text-primary" style="font-size: 1rem; color: #007bff !important;">Original</h2>
                                    <p class="fs-4"><?php echo htmlspecialchars($card['original']); ?></p>
                                <?php endif; ?>
                                
                                <?php if ($displayOption === 'both'): ?>
                                    <hr class="w-75">
                                <?php endif; ?>

                                <?php if ($displayOption === 'both' || $displayOption === 'uebersetzung'): ?>
                                    <h2 class="text-success mt-2" style="font-size: 1rem; color: #28a745 !important;">Übersetzung</h2>
                                    <p class="fs-4"><?php echo htmlspecialchars($card['uebersetzung']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#flashcardCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#flashcardCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="form-container" style="background-color: #ffffff; padding: 2rem; border-radius: 1.875rem; box-shadow: 0 0.25rem 0.625rem rgba(0, 0, 0, 0.1); max-width: 35rem; margin: 2rem auto;">
            
            <form method="GET" action="deck-karten.php" class="d-flex align-items-center justify-content-center mb-4" style="background:none; box-shadow:none; padding:0;">
                <input type="hidden" name="deck" value="<?php echo htmlspecialchars($deck_name); ?>">
                <label for="displayOption" class="me-2">Anzeigemodus:</label>
                <select name="displayOption" id="displayOption" class="form-select form-select-sm w-auto me-2" onchange="this.form.submit()">
                    <option value="both" <?php echo ($displayOption === 'both') ? 'selected' : ''; ?>>Beides</option>
                    <option value="original" <?php echo ($displayOption === 'original') ? 'selected' : ''; ?>>Original</option>
                    <option value="uebersetzung" <?php echo ($displayOption === 'uebersetzung') ? 'selected' : ''; ?>>Übersetzung</option>
                </select>
            </form>

            <hr>

            <form method="POST" action="deck-karten.php?deck=<?php echo urlencode($deck_name); ?><?php if($displayOption) echo '&displayOption='.urlencode($displayOption); ?>" style="background:none; box-shadow:none; padding:0;">
                <input type="text" name="original" class="styled-input" placeholder="Original" required><br>
                <input type="text" name="uebersetzung" class="styled-input" placeholder="Übersetzung" required><br>
                <input type="submit" class="cta-button" id="deckHinzufügen" value="Zum Deck hinzufügen"><br>
                <input type="button" class="cta-button" id="startseite" value="Zur Startseite" onclick="window.location.href='index.php';"><br>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>