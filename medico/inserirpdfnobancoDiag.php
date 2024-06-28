<?php
    session_start();
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type']!== 'medico') {
        // Redireciona para a página de erro de acesso negado
        header('Location: ../acesso_negado.php');
        exit(); 
    }
// Verifica se o base64 do PDF foi recebido via POST
if (isset($_POST['pdfHash'], $_POST['paciente'])) {
    $pdfHash = $_POST['pdfHash'];
    $paciente = $_POST['paciente'];
    $agendamento = $_POST['agendamento'];

    // Conexão com o banco de dados
    include('../connect.php'); 

    // Escape dos dados para prevenir SQL injection
    $pdfHash = mysqli_real_escape_string($conexao, $pdfHash);
    $paciente = mysqli_real_escape_string($conexao, $paciente);

    // Inserção dos dados no banco de dados
    $resultado = mysqli_query($conexao, "INSERT INTO tb_diagnostico (cd_hash_pdf, cd_agendamento) VALUES ('$pdfHash','$agendamento')");

    if ($resultado) {
        echo "PDF inserido no banco de dados com sucesso!";
    } else {
        echo "Erro ao inserir PDF no banco de dados: " . mysqli_error($conexao);
    }
} else {
    echo "Erro: PDF base64 ou ID do paciente não recebidos.";
}

?>