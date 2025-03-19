<?php

session_start();

// Verifica se os dados do Excel estão na sessão
if (isset($_SESSION['excelData'])) {
    $participantes = $_SESSION['excelData'];

    // Verifica se já existem vencedores sorteados
    if (!isset($_SESSION['vencedores'])) {
        $_SESSION['vencedores'] = [];
    }
    $vencedores = $_SESSION['vencedores'];

    // Remove os participantes já sorteados da lista
    $participantesNaoSorteados = array_filter($participantes, function($participante) use ($vencedores) {
        return !in_array($participante, $vencedores);
    });

    // Verifica se ainda há participantes para sortear
    if (!empty($participantesNaoSorteados)) {
        $indiceVencedor = array_rand($participantesNaoSorteados);
        $vencedor = $participantesNaoSorteados[$indiceVencedor];

        // Adiciona o vencedor à lista de vencedores
        $vencedores[] = $vencedor;
        $_SESSION['vencedores'] = $vencedores;

        echo json_encode(['success' => true, 'vencedor' => $vencedor, 'vencedores' => $vencedores]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Todos os participantes já foram sorteados.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dados do Excel não encontrados na sessão.']);
}