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
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $data = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }
            // Check if there is enough data in the line
            if (count($rowData) >= 2) {
                $data[] = ['numero' => $rowData[0], 'nome' => $rowData[1]];
            }
        }

        session_start();
        $_SESSION['excelData'] = $data;

        echo json_encode(['success' => true, 'message' => 'Arquivo carregado com sucesso!']);

    } catch (\Exception $error) {
        echo json_encode(['success' => false, 'message' => 'Erro ao carregar o arquivo: ' . $error->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nenhum arquivo enviado.']);
}