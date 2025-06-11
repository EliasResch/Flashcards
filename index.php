<?php
//Verbindung zur Datenbank, Anzeige der Decks und anzahl der Karten in den einzelnen Decks
$decks = [];
$conn = new mysqli("localhost", "root", "", "karteikarten");
$result = $conn->query("SHOW TABLES LIKE 'deck_%'");
while ($row = $result->fetch_array()) {
  $table_name = $row[0];
  $deck_name = str_replace("deck_", "", $table_name);

  $count_result = $conn->query("SELECT COUNT(*) AS count FROM $table_name");
  $count = $count_result->fetch_assoc()['count'];

  $decks[] = [
    'name' => $deck_name,
    'count' => $count,
    'table' => $table_name
  ];
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
  <div class="page-container">
    <header>
      <img src="img/flash.png" alt="Flashcards Logo" class="logo-img">

    </header>
    </header>
    <main class="tx">
      <div class="tx-text">
        <h1>Flashcards</h1>
        <h1>Was ist Flashcards?</h1><br>
      </div>

      <div class="flashcards-info">
        <p>
          <strong>Flashcards</strong> ist eine Online-Lernplattform, mit der man auf einfache
          Weise digitale Karteikarten erstellen und lernen kann.
          Die Grundidee ist simpel: Auf eine Seite der Karteikarte kommt eine Frage,
          ein Begriff oder ein Wort – auf die Rückseite die passende Antwort, Definition oder Übersetzung.
        </p>
        <p>
          Durch verschiedene Lernmodi wie Abfragen hilft Flashcards dabei, Wissen langfristig zu speichern – ideal zur Prüfungsvorbereitung oder zum Vokabellernen.
        </p><br>
      </div>
      <!--Buttons für neues Deck erstellen und Karten Bearbeiten-->
      <a href="deck-erstellen.php" class="cta-button">Neues Deck Erstellen</a>
      <a href="karten-bearbeiten.php" class="cta-button">Karten Bearbeiten</a>
      <br>

      <img id="karten" src="img/Karten.jpg" alt="karten">
      <br>

      <br>
      <!--Anzeige der Decks, Buttons für Deck öffnen und Deck löschen-->
      <div class="deck-container">
        <?php if (empty($decks)): ?>
          <p>Es sind keine Decks vorhanden. Erstellen Sie ein neues Deck</p>
        <?php else: ?>
          <?php foreach ($decks as $deck): ?>
            <div class="deck-card">
              <h2><?php echo htmlspecialchars($deck['name']); ?></h2>
              <p>Karten: <?php echo $deck['count']; ?></p>
              <a href="deck-karten.php?deck=<?php echo urlencode($deck['name']); ?>" class="cta-button">Deck öffnen</a>
              <form method="POST" action="deck-loeschen.php" onsubmit="return confirm('Möchten Sie dieses Deck wirklich löschen?');">
                <input type="hidden" name="deck_name" value="<?php echo htmlspecialchars($deck['name']); ?>">
                <button type="submit" class="cta-button delete-button">Deck löschen</button>
              </form>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </main>
  </div>
          <!--Footer für Impressum, Datenschutzerklärung und Kontaktseite-->
  <footer>
    <p>&copy; 2025 Flashcards. Alle Rechte vorbehalten.
      <a href="impressum.html" style="color: #ea6f4a; text-decoration: none;">Impressum, </a>
      <a href="Datenschutz.html" style="color: #ea6f4a; text-decoration: none;">Dateschutzerklärung</a>
    </p>
  </footer>
</body>

</html>