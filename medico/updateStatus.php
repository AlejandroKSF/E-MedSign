<?php

include('../connect.php');
$conn = $conexao;


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$idAgendamento = $_POST['id'];
$status = $_POST['status'];


$sql = "UPDATE tb_agendamento SET ds_status = '$status' WHERE cd_agendamento = '$idAgendamento'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
