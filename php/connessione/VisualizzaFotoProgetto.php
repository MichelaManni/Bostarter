<?php

$nome_progetto = $_SESSION['nome_progetto_foto']; //nome progetto dalla sessione
$foto_paths = []; //array vuoto per salvare i percorsi nelle foto

$query = "CALL VisualizzaFotoProgetto(?)"; //sstored procedure per visualizzare foto progetto
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $nome_progetto); 

if ($stmt->execute()) {
    $result = $stmt->get_result();
   
    while ($row = $result->fetch_assoc()) { // Per ogni riga del risultato salva il percorso foto nell'array
        $foto_paths[] = $row['PercorsoFoto']; // 'PercorsoFoto' è il campo della tabella FotoProgetto
    }
} else {
    echo "<p>Errore nella visualizzazione delle foto: " . htmlspecialchars($stmt->error) . "</p>";
}

$stmt->close();            
