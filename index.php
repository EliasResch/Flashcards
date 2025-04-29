<?php
// Datenbankverbindung

$conn = new mysqli("localhost", "root", "", "karteikarten");

// Verbindung prüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
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
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
  <header>
    <h1>⚡️Flashcards</h1>
  </header>
  <main class="tx">
    <div class="tx-text">
      <h1> Was ist Flashcards</h1><br>
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
      </div> <br>


    <a href="deck-erstellen.php" class="cta-button">Neues Deck Erstellen</a>
    <br>
    
    <img id="karten" src="img/Karten.jpg" alt="karten">
    

  </main>


  <footer>
    <p>&copy; 2025 Flashcards. Alle Rechte vorbehalten. 
        <a href="impressum.html" style="color: #ea6f4a; text-decoration: none;">Impressum, </a>
        <a href="Datenschutz.html" style="color: #ea6f4a; text-decoration: none;">Dateschutzerklärung</a>
    </p>
</footer>

</body>
</html>