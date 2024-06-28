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
    <link href='../css/Finalizar_atendimento.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <link href='../css/Atedimento_Menu.scss' rel='stylesheet'>
    <script src="../script/onclick.js" defer></script>
    <script src="../script/navbar.js" defer></script>
    <title>Inicio Médico</title>
    <style>
        #id04 {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            width: 80%;
            margin-top: -30px;
            margin-left: 10px;
        }

        #id04 li {
            list-style-type: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        #id04 li:hover {
            background-color: #f2f2f2;
        }
    </style>

</head>
<?php
include ('../main.php');

$idAgendamento = isset($_GET['id']) ? $_GET['id'] : null;
$paciente = isset($_GET['cpf']) ? $_GET['cpf'] : null;
if ($idAgendamento && $paciente) {
    // Função que verifica se o ID do agendamento corresponde ao paciente
    function verificarAgendamento($idAgendamento, $paciente)
    {
        include ('../connect.php');
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

<body>

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
        <div class="tables-links">

            <ul class="links-menu-atedimento">
                <a class="menu" href="./Atendimento_Anamnese.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Anamnese </span></li>
                </a>
                <a class="menu " href="./Atendimento_Diagnostico.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Diagnóstico</span></li>
                </a>
                <a class="menu" href="./Atendimento_Prescricao.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Prescrição</span></li>
                </a>
                <a class="menu" href="./Atendimento_Atestado.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Atestado</span></li>
                </a>
                <a class="menu active" href="./finalizar_atendimento.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Finalizar atendimento</span></li>
                </a>
            </ul>
            <button onclick="goBack()" class="back"><i class="bi bi-backspace"></i>Voltar</button>
            <?php
            if (isset($_GET['id'])) {
                // Obtém o ID do agendamento
                $idAgendamento = $_GET['id'];
                $paciente = $_GET['cpf'];
            }

            $result = FinImprimir($paciente, $idAgendamento);
            $result = $result->fetch_assoc();

            ?>
            <aside class="informaçao-button">

                <button class='btnOpenPDF' type="button" data-folder="anamnese" data-assinado='Nassinado'
                    data-parametro="<?= $result['anam_hash'] ?>">
                    <span class="button__text">Imprimir Anamnese</span>
                    <span class="button__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" 
                            id="bdd05811-e15d-428c-bb53-8661459f9307" data-name="Layer 2" class="svg">
                            <path
                                d="M17.5,22.131a1.249,1.249,0,0,1-1.25-1.25V2.187a1.25,1.25,0,0,1,2.5,0V20.881A1.25,1.25,0,0,1,17.5,22.131Z">
                            </path>
                            <path
                                d="M17.5,22.693a3.189,3.189,0,0,1-2.262-.936L8.487,15.006a1.249,1.249,0,0,1,1.767-1.767l6.751,6.751a.7.7,0,0,0,.99,0l6.751-6.751a1.25,1.25,0,0,1,1.768,1.767l-6.752,6.751A3.191,3.191,0,0,1,17.5,22.693Z">
                            </path>
                            <path
                                d="M31.436,34.063H3.564A3.318,3.318,0,0,1,.25,30.749V22.011a1.25,1.25,0,0,1,2.5,0v8.738a.815.815,0,0,0,.814.814H31.436a.815.815,0,0,0,.814-.814V22.011a1.25,1.25,0,1,1,2.5,0v8.738A3.318,3.318,0,0,1,31.436,34.063Z">
                            </path>
                        </svg></span>
                </button>

                <button class='btnOpenPDF' type="button" data-folder="diagnostico" data-assinado='Nassinado'
                    data-parametro="<?= $result['diag_hash'] ?>">
                    <span class="button__text">Imprimir Diagnóstico</span>
                    <span class="button__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35"
                            id="bdd05811-e15d-428c-bb53-8661459f9307" data-name="Layer 2" class="svg">
                            <path
                                d="M17.5,22.131a1.249,1.249,0,0,1-1.25-1.25V2.187a1.25,1.25,0,0,1,2.5,0V20.881A1.25,1.25,0,0,1,17.5,22.131Z">
                            </path>
                            <path
                                d="M17.5,22.693a3.189,3.189,0,0,1-2.262-.936L8.487,15.006a1.249,1.249,0,0,1,1.767-1.767l6.751,6.751a.7.7,0,0,0,.99,0l6.751-6.751a1.25,1.25,0,0,1,1.768,1.767l-6.752,6.751A3.191,3.191,0,0,1,17.5,22.693Z">
                            </path>
                            <path
                                d="M31.436,34.063H3.564A3.318,3.318,0,0,1,.25,30.749V22.011a1.25,1.25,0,0,1,2.5,0v8.738a.815.815,0,0,0,.814.814H31.436a.815.815,0,0,0,.814-.814V22.011a1.25,1.25,0,1,1,2.5,0v8.738A3.318,3.318,0,0,1,31.436,34.063Z">
                            </path>
                        </svg></span>
                </button>

                <button class='btnOpenPDF' type="button" data-folder="prescricao" data-assinado='Nassinado'
                    data-parametro="<?= $result['presc_hash'] ?>">
                    <span class="button__text">Imprimir Prescrição</span>
                    <span class="button__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35"
                            id="bdd05811-e15d-428c-bb53-8661459f9307" data-name="Layer 2" class="svg">
                            <path
                                d="M17.5,22.131a1.249,1.249,0,0,1-1.25-1.25V2.187a1.25,1.25,0,0,1,2.5,0V20.881A1.25,1.25,0,0,1,17.5,22.131Z">
                            </path>
                            <path
                                d="M17.5,22.693a3.189,3.189,0,0,1-2.262-.936L8.487,15.006a1.249,1.249,0,0,1,1.767-1.767l6.751,6.751a.7.7,0,0,0,.99,0l6.751-6.751a1.25,1.25,0,0,1,1.768,1.767l-6.752,6.751A3.191,3.191,0,0,1,17.5,22.693Z">
                            </path>
                            <path
                                d="M31.436,34.063H3.564A3.318,3.318,0,0,1,.25,30.749V22.011a1.25,1.25,0,0,1,2.5,0v8.738a.815.815,0,0,0,.814.814H31.436a.815.815,0,0,0,.814-.814V22.011a1.25,1.25,0,1,1,2.5,0v8.738A3.318,3.318,0,0,1,31.436,34.063Z">
                            </path>
                        </svg></span>
                </button>



                <button class='btnOpenPDF' type="button" data-folder="atestado" data-assinado='Nassinado'
                    data-parametro="<?= $result['ates_hash'] ?>">
                    <span class="button__text">Imprimir Atestado</span>
                    <span class="button__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35"
                            id="bdd05811-e15d-428c-bb53-8661459f9307" data-name="Layer 2" class="svg">
                            <path
                                d="M17.5,22.131a1.249,1.249,0,0,1-1.25-1.25V2.187a1.25,1.25,0,0,1,2.5,0V20.881A1.25,1.25,0,0,1,17.5,22.131Z">
                            </path>
                            <path
                                d="M17.5,22.693a3.189,3.189,0,0,1-2.262-.936L8.487,15.006a1.249,1.249,0,0,1,1.767-1.767l6.751,6.751a.7.7,0,0,0,.99,0l6.751-6.751a1.25,1.25,0,0,1,1.768,1.767l-6.752,6.751A3.191,3.191,0,0,1,17.5,22.693Z">
                            </path>
                            <path
                                d="M31.436,34.063H3.564A3.318,3.318,0,0,1,.25,30.749V22.011a1.25,1.25,0,0,1,2.5,0v8.738a.815.815,0,0,0,.814.814H31.436a.815.815,0,0,0,.814-.814V22.011a1.25,1.25,0,1,1,2.5,0v8.738A3.318,3.318,0,0,1,31.436,34.063Z">
                            </path>
                        </svg></span>
                </button>



                <button type="submit" id="botaoAssinar" class="btnAssinar" data-anam="<?= $result['anam_hash'] ?>"
                data-diag="<?= $result['diag_hash'] ?>" data-presc="<?= $result['presc_hash'] ?>"
                data-ates="<?= $result['ates_hash'] ?>">
                    <span class="button__text">Assinar documentos</span>
                    <span class="button__icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            class="bi bi-check-lg svg" viewBox="0 0 16 16">
                            <path
                                d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                        </svg></span>
                </button>
            </aside>
            <div class="botao">
                <button class="buttonPadrao" id="buttonPadrao" data-parametro="<?= $idAgendamento ?>"><span>Finalizar
                        Atendimento</span></button>
            </div>
        </div>
    </main>
    <script>
        document.getElementById('botaoAssinar').addEventListener('click', function () {
            var idAgendamento = <?= json_encode($idAgendamento) ?>;
            var anamHash = this.getAttribute('data-anam');
            var diagHash = this.getAttribute('data-diag');
            var prescHash = this.getAttribute('data-presc');
            var atesHash = this.getAttribute('data-ates');

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'Assinar_documentos.php?id=' + idAgendamento + '&anam=' + anamHash + '&diag=' + diagHash + '&presc=' + prescHash + '&ates=' + atesHash, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        var response = xhr.responseText;
                        alert('Documentos assinados com sucesso!');
                    } else {
                        alert('Erro ao assinar documentos.');
                    }
                }
            };
            xhr.send();
        });

        document.getElementById('buttonPadrao').addEventListener('click', function () {
            var idAgendamento = <?= $idAgendamento ?>;

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'finAtendimento.php?id=' + idAgendamento + '&paciente=<?= $paciente ?>', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.responseText;

                    // Exibir o alerta de sucesso
                    alert('Atendimento finalizado com sucesso!');

                    // Redirecionar para o índice após a confirmação
                    window.location.href = 'dashboardMedico.php';
                }
            };
            xhr.send();
        });

    </script>
</body>

</html>