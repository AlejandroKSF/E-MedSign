<?php
include('../connect.php');

$conn = $conexao;

$agendamento = $_GET['id'];
$paciente = $_GET['paciente'];

// Verifica se o ID foi passado na URL
if (isset($agendamento) && !empty($agendamento)) {
    // Prepara a consulta SQL para atualizar o status do agendamento
    $stmt = $conn->prepare("UPDATE tb_agendamento SET ds_status = 'Finalizado' WHERE cd_agendamento = ?");

    
    // Vincula o parâmetro à consulta
    $stmt->bind_param("i", $agendamento);

    $stmt2 = $conn->prepare("UPDATE tb_paciente SET ic_primeiro_atend = 0 WHERE cd_paciente = ?");
    
    
    // Vincula o parâmetro à consulta
    $stmt2->bind_param("i", $paciente);
    $stmt2->execute();
    
    // Executa a consulta
    if ($stmt->execute()) {
        echo "Status atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar o status: " . $stmt->error;
    }
    
    // Fecha a declaração
    $stmt->close();
} else {
    echo "ID do agendamento não fornecido.";
}

$conn->close();
?>
