<?php

namespace Backend;

require '../vendor/autoload.php';
require '../traits/DatabaseConnection.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Traits\DatabaseConnection;

class Raffle{
    use DatabaseConnection;

    public function drawParticipant()
    {
        try {
            $connection = $this->getConnection();
            $sql = "SELECT * FROMN participants ORDER BY RAND() LIMIT 1";
            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                $participant = $result->fetch_assoc();
                $connection->close();
                return $participant;
            } else {
                $connection->close();
                return ['error' => 'Nenhum participante encontrado.'];
            }
        } catch (\Exception $error) {
            return ['error' => 'Erro: ' . $error->getMessage()];
        }
    }
}