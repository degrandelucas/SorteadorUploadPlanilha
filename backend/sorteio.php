<?php

$config = require '../config/database.php';
$conn = new mysqli(...array_values($config));

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Erro conexão BD']));
}

$result = $conn->query("SELECT * FROM participantes WHERE sorteado = FALSE ORDER BY RAND() LIMIT 1");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $conn->query("UPDATE participantes SET sorteado = TRUE WHERE id = " . $row['id']);
    echo json_encode(['success' => true, 'vencedor' => ['numero' => $row['numero'], 'nome' => $row['nome']]]);
} else {
    echo json_encode(['success' => false, 'message' => 'Todos sorteados']);
}

$conn->close();