<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'medico') {
    // Redireciona para a página de erro de acesso negado
    header('Location: ../acesso_negado.php');
    exit();
}
include ("../connect.php");

// Receber o JSON do corpo da requisição
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $userId = $_SESSION['user_id'];

    // Prepare a query SQL
    $query = "REPLACE INTO tb_medico_config (cd_medico, config_json) VALUES (?, ?)";
    $stmt = $conexao->prepare($query);
    $configJson = json_encode($data);

    // Verificar se a preparação da consulta foi bem-sucedida
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Erro na preparação da consulta']);
        exit;
    }

    // Vincular parâmetros e executar a consulta
    $stmt->bind_param("is", $userId, $configJson);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conexao->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Nenhum dado recebido']);
}
?>