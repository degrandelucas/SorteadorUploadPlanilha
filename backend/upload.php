<?php

namespace Backend;

require '../vendor/autoload.php';
require '../traits/DatabaseConnection.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Traits\DatabaseConnection;

class Upload
{
    use DatabaseConnection;

    public function processUpload()
    {
        if (isset($_FILES['excelFile'])) {
            $file = $_FILES['excelFile']['tmp_name'];
            try {
                $loadFile = IOFactory::load($file);
                $worksheet = $loadFile->getActiveSheet();
                $rows = $worksheet->toArray();

                array_shift($rows); // Remove o cabeçalho

                $connection = $this->getConnection();

                foreach ($rows as $row) {
                    $sql = "INSERT INTO participantes (numero, nome) VALUES (?, ?)";
                    $bindState = $connection->prepare($sql);
                    $bindState->bind_param("is", $row[0], $row[1]);
                    $bindState->execute();
                }

                $connection->close();

                echo json_encode(['success' => true , 'message' => 'Arquivo importado com sucesso.']);

            } catch (\Exception $error) {
                echo json_encode(['success' => false, 'message' => 'Erro: ' . $error->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhum arquivo recebido.']);
        }
    }
}

$upload = new Upload();
$upload->processUpload();
