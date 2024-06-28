<?php
include('../connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $paciente = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $medico = mysqli_real_escape_string($conexao, trim($_POST['medicoId']));
    $horario = mysqli_real_escape_string($conexao, trim($_POST['horario']));
    $data = mysqli_real_escape_string($conexao, trim($_POST['dataConsulta']));
    $contato = mysqli_real_escape_string($conexao, trim($_POST['contato']));
    $cadastrado = mysqli_real_escape_string($conexao, trim($_POST['radio-group']));
    $cpf = mysqli_real_escape_string($conexao, trim($_POST['cpf']));


    if ($cadastrado == 'sim') {

        $query = "SELECT cd_paciente FROM tb_paciente WHERE cd_cpf = '$cpf'";
        $result = mysqli_query($conexao, $query);
        if (mysqli_num_rows($result) > 0) {
 
            $row = mysqli_fetch_assoc($result);
            $cd_paciente = $row['cd_paciente'];
            $query = "INSERT INTO tb_agendamento (cd_medico, cd_paciente, nm_paciente, hr_horario, dt_data, cd_contato, ds_status)
                      VALUES ('$medico', '$cd_paciente', '$paciente', '$horario', '$data', '$contato', 'Aguardando')";
        } else {
   
            echo "<script>alert('Paciente com CPF $cpf não encontrado. Por favor, verifique o CPF.'); window.history.back();</script>";
            exit();
        }
    } else {
    
        $query = "INSERT INTO tb_agendamento (cd_medico, nm_paciente, hr_horario, dt_data, cd_contato, ds_status)
                  VALUES ('$medico', '$paciente', '$horario', '$data', '$contato', 'Aguardando')";
    }


    if (mysqli_query($conexao, $query)) {
        echo "<script>alert('Agendamento realizado com sucesso!'); window.location.href = 'dashboardAtendente.php';</script>";
    } else {

        error_log("Database insertion error: " . mysqli_error($conexao));
        echo json_encode(["success" => false, "error" => "Falha ao adicionar o agendamento. Tente novamente"]);
    }


} else {
    header('Location: ../index.html');
    exit();
}
?>
