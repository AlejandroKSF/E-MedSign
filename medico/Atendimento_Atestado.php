<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='../css/Atedimento_Anamnese.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <link href='../css/Atedimento_Menu.scss' rel='stylesheet'>
    <script src="../script/onclick.js" defer></script>
    <script src="../script/navbar.js" defer></script>
    <title>Inicio Médico</title>

</head>

<body>
<?php
    include('../main.php');
   
    $idAgendamento = isset($_GET['id']) ? $_GET['id'] : null;
    $paciente = isset($_GET['cpf']) ? $_GET['cpf'] : null;
    if ($idAgendamento && $paciente) {
        // Função que verifica se o ID do agendamento corresponde ao paciente
        function verificarAgendamento($idAgendamento, $paciente) {
            include('../connect.php');
            $conn = $conexao;
            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            $sql = "SELECT COUNT(*) as count FROM tb_agendamento WHERE cd_agendamento = ? AND cd_paciente = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $idAgendamento, $paciente);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $stmt->close();
            $conn->close();

            return $row['count'] > 0;
        }

        if (!verificarAgendamento($idAgendamento, $paciente)) {
            // Redireciona para a página de erro se o agendamento não corresponder ao paciente
            header('Location: ../acesso_negado.php');
            exit();
        }

        $result1 = infoPaciente($paciente);
    }

?>
    <?php
    include ("../navibar.php");

    session_start();
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'medico') {
        // Redireciona para a página de erro de acesso negado
        header('Location: ../acesso_negado.php');
        exit(); // Certifica-se de que o script pare de executar após o redirecionamento
    }
    $menu = generateMenu($_SESSION['user_type'], $permissions);

    // Imprimir o menu
    echo $menu;
    ?>


    <main class="cabeca-menu">
        <div class="tables">

            <ul class="links-menu-atedimento">

                <a class="menu " href="./Atendimento_Anamnese.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Anamnese </span></li>
                </a>
                <a class="menu " href="./Atendimento_Diagnostico.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Diagnóstico</span></li>
                </a>
                <a class="menu" href="./Atendimento_Prescricao.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Prescrição</span></li>
                </a>
                <a class="menu active" href="./Atendimento_Atestado.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Atestado</span></li>
                </a>
                <a class="menu" href="./finalizar_atendimento.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Finalizar atendimento</span></li>
                </a>

            </ul>
            <button onclick="goBack()" class="back"><i class="bi bi-backspace"></i>Voltar</button>

            <div class="titulo">
                <h1>Nome do paciente </h1>
            </div>

            <div class="search-container-box">
                <label class="label-comentario">Atestado</label>
                <textarea class="input-comentario" id="atestado" name="atestado">
            </textarea>
            </div>

            <div class="botao">

                <button class="buttonPadrao" id="btnSalvar" class="buttonCenter" style="margin-top:20px;" type="button"
                    onclick="gerarPDFAtestado('<?= $paciente ?>','<?= $idAgendamento ?>')"><span>Salvar</span></button>
            </div>

        </div>
    </main>





</body>

</html>