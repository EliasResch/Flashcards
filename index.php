<?php
// Array zum Speichern der Decks 
$decks = [];
// Verbindung zur Datenbank herstellen
$conn = new mysqli("localhost", "USER443003", "Flashcards1234", "db_443003_2");
// Alle Tabellen abrufen, die mit 'deck_' beginnen
$result = $conn->query("SHOW TABLES LIKE 'deck_%'");

// Schleife durch alle gefundenen Deck-Tabellen
while ($row = $result->fetch_array()) {
  $table_name = $row[0];
  // Den 'deck_'-Präfix vom Namen entfernen für die Anzeige
  $deck_name = str_replace("deck_", "", $table_name);

  // Die Anzahl der Karten (Zeilen) in der aktuellen Deck-Tabelle zählen
  $count_result = $conn->query("SELECT COUNT(*) AS count FROM $table_name");
  $count = $count_result->fetch_assoc()['count'];

  // Deck-Informationen zum Array hinzufügen
  $decks[] = [
    'name' => $deck_name,
    'count' => $count,
    'table' => $table_name
  ];
}
?>
<!DOCTYPE html>
<html lang="en">
<body>
  <div class="page-container">
    <main class="tx">
        <a href="deck-erstellen.php" class="cta-button">Neues Deck Erstellen</a>
      <a href="karten-bearbeiten.php" class="cta-button">Karten Bearbeiten</a>
      <br>

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
          <footer>
    <p>&copy; 2025 Flashcards. Alle Rechte vorbehalten. </p><br>
      <a href="impressum.html" style="color: #ea6f4a; text-decoration: none;">Impressum |  </a>
      <a href="datenschutz.html" style="color: #ea6f4a; text-decoration: none;">Dateschutzerklärung | </a>
       <a href="kontakt.html" style="color: #ea6f4a; text-decoration: none;">Kontaktseite </a>
          </footer>
  </body>
</html>
