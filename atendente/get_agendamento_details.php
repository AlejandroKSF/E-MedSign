<?php
include('../connect.php');

if (isset($_GET['agendamento_id'])) {
    $agendamentoId = $_GET['agendamento_id'];


    $result = mysqli_query($conexao, "Select ag.*, pac.cd_telefone as tel ,pac.cd_cpf as cpf from tb_agendamento as ag join tb_paciente as pac on pac.cd_paciente = ag.cd_paciente where ag.cd_agendamento = $agendamentoId");

    if ($result) {

        $agendamento = mysqli_fetch_assoc($result);
        

        if ($agendamento) {

            echo json_encode($agendamento);
        } else {

            http_response_code(404); 
            echo json_encode(array("error" => "agendamento not found"));
        }
    } else {

        http_response_code(500); 
        echo json_encode(array("error" => mysqli_error($conexao)));
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Missing agendamento_id parameter"));
}
?>