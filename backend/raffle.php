<?php

namespace Backend;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../traits/DatabaseConnection.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Traits\DatabaseConnection;

class Raffle{
    use DatabaseConnection;

    public function drawParticipant($gift)
    {
        try {
            $connection = $this->getConnection();
            $sql = "SELECT * FROM participants WHERE winner = 0 ORDER BY RAND() LIMIT 1";
            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                $participant = $result->fetch_assoc(); //recuperar os dados do participante sorteado, array associativo no PHP

                $updateSQL = "UPDATE participants SET winner = 1, gift = ? WHERE id = ?";
                $updateState = $connection->prepare($updateSQL);
                $updateState->bind_param("si", $gift, $participant['id']);
                $updateState->execute();

                $connection->close();
                return $participant;
            } else {
                $connection->close();
                return ['error' => 'Todos os participantes já foram selecionados.'];
            }
        } catch (\Exception $error) {
            return ['error' => 'Erro ' . $error->getMessage()];
        }
    }
}

$raffle = new Raffle();

if (isset($_POST['action']) && $_POST['action'] === 'draw') { // Verifica action no POST
    if (isset($_POST['prize'])) { // Verifica prize no POST
        $gift = $_POST['prize'];
        $drawnParticipant = $raffle->drawParticipant($gift);
        echo json_encode($drawnParticipant);
    } else {
        echo json_encode(['error' => 'Prêmio não repassado para os dados.']);
    }
} else {
    echo json_encode(['error' => 'Não foi possível realizar a ação.']);
}