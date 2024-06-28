<?php

session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type']!== 'medico') {
    // Redireciona para a página de erro de acesso negado
    header('Location: ../acesso_negado.php');
    exit(); // Certifica-se de que o script pare de executar após o redirecionamento
}
if (isset($_POST['pdfHash'], $_POST['paciente'])) {
    $pdfHash = $_POST['pdfHash'];
    $paciente = $_POST['paciente'];
    $agendamento = $_POST['agendamento'];


    include('../connect.php');

    $pdfHash = mysqli_real_escape_string($conexao, $pdfHash);
    $paciente = mysqli_real_escape_string($conexao, $paciente);


    $resultado = mysqli_query($conexao, "INSERT INTO tb_anamnese (cd_hash_pdf, cd_medico ,cd_agendamento) VALUES ('$pdfHash','".$_SESSION['user_id']."','$agendamento')");

    if ($resultado) {
        echo "PDF inserido no banco de dados com sucesso!";
    } else {
        echo "Erro ao inserir PDF no banco de dados: " . mysqli_error($conexao);
    }
} else {
    echo "Erro: PDF base64 ou ID do paciente não recebidos.";
}

?>