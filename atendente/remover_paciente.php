<?php
include ('../main.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pacienteId = $_POST['id'];

    include ('../connect.php');

    $conn = $conexao;


    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $sql = "update tb_paciente set ic_status = 0 WHERE cd_paciente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $pacienteId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $conn->close();
}
?>