<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type']!== 'medico') {
    // Redireciona para a página de erro de acesso negado
    header('Location: ../acesso_negado.php');
    exit();
}
include('../connect.php');

$conn = $conexao;


if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if(isset($_GET['medico_id'])) {
    // Obtém o ID do médico a partir do parâmetro
    $medico_id = $_GET['medico_id'];
    
    // Consulta para obter os agendamentos do médico específico
    $sql = "SELECT cd_agendamento as id, hr_horario as hora, dt_data as dia, cd_contato as contato, nm_paciente as paciente, ds_status as estado FROM tb_agendamento WHERE cd_medico = $medico_id";
    $result = $conn->query($sql);
    $agendamentos = [];

    if ($result->num_rows > 0) {
        // Formato dos dados para JavaScript
        while ($row = $result->fetch_assoc()) {
            $agendamentos[] = [
                'id' => $row['id'],
                'hora' => $row['hora'],
                'dia' => $row['dia'],
                'contato'=> $row['contato'],
                'paciente'=>$row['paciente'],
                'estado'=>$row['estado']
            ];
        }
    }

    // Saída dos dados como JSON
    echo json_encode($agendamentos);
}

$conn->close();
?>
