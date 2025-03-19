<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
    $file = $_FILES['excelFile']['tmp_name'];
    $allowedExtensions = ['xlsx', 'xls', 'csv', 'ods'];
    $fileExtension = strtolower(pathinfo($_FILES['excelFile']['name'], PATHINFO_EXTENSION));

    //checking if the allowed extension is contained in the uploaded file
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['success' => false, 'message' => 'Tipo de arquivo inválido.']);
        exit;
    }

    try {
        $uploadedFile = IOFactory::load($file);
        $worksheet = $uploadedFile->getActiveSheet(); // Get the first active sheet
        $extractedData = [];
        $initialLine = 2;

        foreach ($worksheet->getRowIterator($initialLine) as $row) {
            $cellIterator = $row->getCellIterator();
            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }
            // Check if there is enough data in the line
            if (count($rowData) >= 2) {
                $extractedData[] = ['numero' => $rowData[0], 'nome' => $rowData[1]];
            }
        }

        $sectionTime = 4 * 60 * 60; // 4 hours
        session_set_cookie_params($sectionTime);
        session_start(); // Start PHP session to manage user data
        $_SESSION['excelData'] = $extractedData; // Store extracted Excel data in session for later use

        echo json_encode(['success' => true, 'message' => 'Arquivo carregado com sucesso!']);

    } catch (\Exception $error) {
        echo json_encode(['success' => false, 'message' => 'Erro ao carregar o arquivo: ' . $error->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nenhum arquivo enviado.']);
}