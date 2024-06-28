<?php
include ('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $agendamento = $_POST['editAgendamento'];
    $paciente = $_POST['editName'];
    $horario = $_POST['editHorario'];
    $data = $_POST['editDataConsulta'];
    $contato = $_POST['editContato'];

    if (
        mysqli_query($conexao, "UPDATE tb_agendamento Set nm_paciente = '$paciente', hr_horario = '$horario', dt_data = '$data', cd_contato = '$contato' where cd_agendamento = '$agendamento'")
    ) {

        echo json_encode(["success" => true]);
    } else {

        echo json_encode(["success" => false, "error" => mysqli_error($conexao)]);
    }
} else {
    header("Location: index.html");
    exit();
}

?>