<?php

// Habilita a exibição de erros do PHP para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclui a biblioteca PhpSpreadsheet
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

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

// Verifica se o arquivo foi enviado
if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['excelFile']['tmp_name'];

    try {
        // Carrega o arquivo Excel
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Remove a primeira linha (cabeçalho)
        array_shift($rows);

        // Prepara a consulta SQL para inserção
        $preparedConnection = $connection->prepare("INSERT INTO participantes (numero, nome) VALUES (?, ?)");
        $preparedConnection->bind_param("is", $numero, $nome);

        // Loop através das linhas e insere no banco de dados
        foreach ($rows as $row) {
            $numero = (int) $row[0]; // Assume que o número está na primeira coluna
            $nome = $row[1]; // Assume que o nome está na segunda coluna

            $preparedConnection->execute();
        }

        // Verifica se a inserção foi bem-sucedida
        if ($preparedConnection->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Dados importados com sucesso.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhum dado foi importado.']);
        }

        // Fecha a consulta preparada
        $preparedConnection->close();

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao processar o arquivo: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nenhum arquivo enviado ou erro no upload.']);
}

// Fecha a conexão com o banco de dados
$connection->close();