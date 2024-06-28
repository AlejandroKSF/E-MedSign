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

// Verifica se os parâmetros medico_id e date foram passados
if(isset($_GET['medico_id']) && isset($_GET['date'])) {
    // Obtém os parâmetros
    $medico_id = $_GET['medico_id'];
    $date = $_GET['date'];

    // Consulta para obter os agendamentos do médico para a data fornecida
    $sql = "SELECT ag.hr_horario as hora, pac.nm_paciente as paciente FROM tb_agendamento as ag join tb_paciente as pac on pac.cd_paciente = ag.cd_paciente WHERE ag.cd_medico = $medico_id AND ag.dt_data = '2024-03-11'";
    $result = $conn->query($sql);
    $agendamentos = [];

    if ($result->num_rows > 0) {
        // Formato dos dados para JavaScript
        while ($row = $result->fetch_assoc()) {
            $agendamentos[] = [
                'hora' => $row['hora'],
                'paciente' => $row['paciente'],
            ];
        }
    }

    // Saída dos dados como JSON
    echo json_encode($agendamentos);
} else {
    echo json_encode(['error' => 'Parâmetros medico_id e/ou date não foram fornecidos']);
}

$conn->close();
?>