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
     //Neue Karten zum Deck hinzufügen
    $cards = [];
    $result = $conn->query("SELECT * FROM $table_name");
    while ($row = $result->fetch_assoc()) {
        $cards[] = $row;
    }
     
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['deck'])) {
        $deck_name = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['deck']);
        $table_name = "deck_" . $deck_name;
        $conn = new mysqli("localhost", "USER443003", "Flashcards1234", "db_443003_2");
        $original = $conn->real_escape_string($_POST['original']);
        $uebersetzung = $conn->real_escape_string($_POST['uebersetzung']);
     
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
     
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwq+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
      <link rel="stylesheet" href="style.css">
     
      <title>Flashcards</title>
    </head>
    <body>
        <div class="container mt-5">
            <h1 class="text-center">Deck: <?php echo htmlspecialchars($deck_name); ?></h1>
     
            <div id="carouselExampleFade" class="carousel slide" data-bs-ride="false">
                <div class="carousel-inner">
                    <?php
                   //Karten im Deck anzeigen
                    $displayOption = $_GET['displayOption'] ?? 'both';
     
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
                    </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                    </button>
                <form method="GET">
                    </form>
            </div>
        </div>
     
        <form method="POST" action="deck-karten.php?deck=<?php echo urlencode($_GET['deck']); ?>">
            <input type="text" name="original" class="styled-input" placeholder="Original" required><br>
            <input type="text" name="uebersetzung" class="styled-input" placeholder="Übersetzung" required><br>
            <input type="submit" class="cta-button" id="deckHinzufügen" value="Zum Deck hinzufügen"><br>
            <input type="button" class="cta-button" id="startseite" value="Zur Startseite" onclick="window.location.href='index.php';"><br>
        </form>
     
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>