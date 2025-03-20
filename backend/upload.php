<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$config = require '../config/database.php';
$conn = new mysqli(...array_values($config));

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Erro conexão BD']));
}

if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
    die(json_encode(['success' => false, 'message' => 'Arquivo inválido']));
}

try {
    $rows = IOFactory::load($_FILES['excelFile']['tmp_name'])->getActiveSheet()->toArray();
    array_shift($rows);

    $stmt = $conn->prepare("INSERT INTO participantes (numero, nome) VALUES (?, ?)");
    foreach ($rows as $row) {
        $stmt->bind_param("is", $row[0],$row[1]);
        $stmt->execute();
    }
    echo json_encode(['success' => true, 'message' => 'Dados importados']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro arquivo: ' . $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}