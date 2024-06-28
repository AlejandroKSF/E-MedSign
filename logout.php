<?php
session_start(); // Start the session

if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'medico') {
    // Obter o ID do médico da sessão
    $medico_id = $_SESSION['user_id'];

    // Realizar a atualização do campo ic_expediente para 0 na tabela tb_medico
    include("connect.php"); // Incluir o arquivo de conexão com o banco de dados
    $conn = $conexao;

    $update_sql = "UPDATE tb_medico SET ic_expediente = 0 WHERE cd_funcionarioID = $medico_id";
    if ($conn->query($update_sql) === TRUE) {
        echo "Registro de expediente atualizado para o médico.";
    } else {
        echo "Erro ao atualizar registro de expediente: " . $conn->error;
    }
}
// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page or any other page as needed
header("Location: index.html");
exit;
?>