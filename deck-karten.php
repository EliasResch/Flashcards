<?php
if (!isset($_GET['deck'])) {
    header("Location: index.php");
    exit();
}

$deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['deck']);
$table_name = "deck_" . $deck_name;
$conn = new mysqli("localhost", "root", "", "karteikarten");

// Karten aus dem Deck abrufen
$cards = [];
$result = $conn->query("SELECT * FROM $table_name");
while ($row = $result->fetch_assoc()) {
    $cards[] = $row;
}

// Neue Karte hinzufügen

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['deck'])) {
    $deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['deck']);
    $table_name = "deck_" . $deck_name;
    $conn = new mysqli("localhost", "root", "", "karteikarten");
    $original = $conn->real_escape_string($_POST['original']);
    $uebersetzung = $conn->real_escape_string($_POST['uebersetzung']);

    // Insert card into the specific deck table
    $sql = "INSERT INTO $table_name (original, uebersetzung) VALUES ('$original', '$uebersetzung')";

    if ($conn->query($sql) === TRUE) {
        $message = "";
    } else {
        $message = "Fehler: " . $conn->error;
    }

    header("Location: deck-karten.php?deck=" . urlencode($deck_name));
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
        <h1 class="text-center">Deck: <?php echo htmlspecialchars($deck_name); ?></h1>

        <!-- Auswahlformular im oberen rechten Eck -->


        <div id="carouselExampleFade" class="carousel slide" data-bs-ride="false">
            <div class="carousel-inner">
                <?php
                $displayOption = $_GET['displayOption'] ?? 'both'; // Standard: Beides anzeigen
                foreach ($cards as $index => $card):
                ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="d-flex flex-column align-items-center">
                            <?php if ($displayOption === 'both' || $displayOption === 'original'): ?>
                                <h2 class="text-primary">Original</h2>
                                <p class="fs-4"><?php echo htmlspecialchars($card['original']); ?></p>
                            <?php endif; ?>
                            <?php if ($displayOption === 'both' || $displayOption === 'uebersetzung'): ?>
                                <h2 class="text-success mt-4">Übersetzung</h2>
                                <p class="fs-4"><?php echo htmlspecialchars($card['uebersetzung']); ?></p>
                            <?php endif; ?>
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
            <form method="GET">
                <input type="hidden" name="deck" value="<?php echo htmlspecialchars($_GET['deck']); ?>">
                <label for="displayOption" class="form-label">Anzeigemodus:</label>
                <select name="displayOption" id="displayOption" class="form-select">
                    <option value="both" <?php echo ($_GET['displayOption'] ?? 'both') === 'both' ? 'selected' : ''; ?>>Beides</option>
                    <option value="original" <?php echo ($_GET['displayOption'] ?? '') === 'original' ? 'selected' : ''; ?>>Nur Original</option>
                    <option value="uebersetzung" <?php echo ($_GET['displayOption'] ?? '') === 'uebersetzung' ? 'selected' : ''; ?>>Nur Übersetzung</option>
                </select>
                <button type="submit" class="cta-button">Anzeigen</button>

            </form>
        </div>

    </div>

    <?php if (isset($message)): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST" action="deck-karten.php?deck=<?php echo urlencode($_GET['deck']); ?>">
        <input type="text" name="original" class="styled-input" placeholder="Original" required><br>
        <input type="text" name="uebersetzung" class="styled-input" placeholder="Übersetzung" required><br>
        <input type="submit" class="cta-button" id="deckHinzufügen" value="Zum Deck hinzufügen"><br>
        <input type="button" class="cta-button" id="startseite" value="Zur Startseite" onclick="window.location.href='index.php';"><br>
    </form>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>