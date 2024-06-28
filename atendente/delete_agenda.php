<?php

if (isset($_POST['agenda_id'])) {
    $agendaId = $_POST['agenda_id'];

    // Conectar ao banco de dados e executar a consulta de exclusão
    include('../connect.php'); 

    mysqli_query($conexao, "DELETE FROM tb_agendamento where cd_agendamento = '$agendaId'");
} else {
    // Se o ID da agenda não foi enviado, retorne um status 400 (Solicitação inválida)
    http_response_code(400);
}
?>