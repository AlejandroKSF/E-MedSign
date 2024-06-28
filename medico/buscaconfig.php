<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type']!== 'medico') {
    // Redireciona para a página de erro de acesso negado
    header('Location: ../acesso_negado.php');
    exit(); 
}
include("../connect.php"); 


if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não logado']);
    exit;
}

$userId = $_SESSION['user_id'];
$pacienteCpf = htmlspecialchars($_GET['cpf']); 

$queryConfig = "SELECT config_json FROM tb_medico_config WHERE cd_medico = ?";
$stmtConfig = $conexao->prepare($queryConfig);
$stmtConfig->bind_param("i", $userId);

if ($stmtConfig->execute()) {
    $resultConfig = $stmtConfig->get_result();
    if ($resultConfig->num_rows > 0) {
        $rowConfig = $resultConfig->fetch_assoc();
        $configJson = $rowConfig['config_json'];

        $queryPatient = "SELECT ic_primeiro_atend FROM tb_paciente WHERE cd_paciente = ?";
        $stmtPatient = $conexao->prepare($queryPatient);
        $stmtPatient->bind_param("s", $pacienteCpf);

        if ($stmtPatient->execute()) {
            $resultPatient = $stmtPatient->get_result();
            if ($resultPatient->num_rows > 0) {
                $rowPatient = $resultPatient->fetch_assoc();
                $isFirstAppointment = $rowPatient['ic_primeiro_atend'] == 1;

                echo json_encode(['config' => json_decode($configJson, true), 'isFirstAppointment' => $isFirstAppointment]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Paciente não encontrado']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => $stmtPatient->error]);
        }

        $stmtPatient->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Configurações não encontradas']);
    }
} else {
    echo json_encode(['success' => false, 'error' => $stmtConfig->error]);
}

$stmtConfig->close();
$conexao->close();
?>