<?php

// Habilita a exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações do banco de dados
$servername = "127.0.0.1";
$username = "dev_adbelem";
$password = "admin@15";
$dbname = "sorteio_ad";


// Conecta ao banco de dados
$connection = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($connection->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados: ' . $connection->connect_error]));
}

// Seleciona um participante não sorteado aleatório
$result = $connection->query("SELECT * FROM participantes WHERE sorteado = FALSE ORDER BY RAND() LIMIT 1");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $vencedor = ['numero' => $row['numero'], 'nome' => $row['nome']];

    // Atualiza o participante como sorteado
    $connection->query("UPDATE participantes SET sorteado = TRUE WHERE id = " . $row['id']);

    echo json_encode(['success' => true, 'vencedor' => $vencedor]);
} else {
    echo json_encode(['success' => false, 'message' => 'Todos os participantes já foram sorteados.']);
}

// Fecha a conexão com o banco de dados
$connection->close();
?>