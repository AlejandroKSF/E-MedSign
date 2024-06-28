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
    <link href='../css/Atendimento_Diagnostico.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <link href='../css/Atedimento_Menu.scss' rel='stylesheet'>
    <script src="../script/onclick.js" defer></script>
    <script src="../script/navbar.js" defer></script>
    <title>Inicio Médico</title>
</head>



<body>
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
                <a class="menu" href="./Atendimento_Anamnese.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Anamnese </span></li>
                </a>
                <a class="menu active"
                    href="./Atendimento_Diagnostico.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Diagnóstico</span></li>
                </a>
                <a class="menu" href="./Atendimento_Prescricao.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Prescrição</span></li>
                </a>
                <a class="menu" href="./Atendimento_Atestado.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Atestado</span></li>
                </a>
                <a class="menu" href="./finalizar_atendimento.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Finalizar atendimento</span></li>
                </a>
            </ul>
            <button onclick="goBack()" class="back"><i class="bi bi-backspace"></i>Voltar</button>

            <div class="titulo">
                <h1>Nome do paciente</h1>
            </div>

            <div class="Diagnostico">
                <div class="titulo-diagnostico">
                    <h2>Diagnóstico Primário</h2>
                </div>
                <div class="search-container-box">
                    <input class="input-comentario" id="diagPri" name="diagPri" type="text" placeholder="J000 ...">
                    <label class="label-comentario">Diagnostico primário</label>
                    <ul class="Tabela-doenca" id="id02">
                        <?php
                        $result1 = suggestCid();
                        if ($result1->num_rows > 0) {
                            while ($row = $result1->fetch_assoc()) {
                                echo "<li>" . $row["ds_codigo"] . ": " . $row["ds_cid"] . "</li>";
                            }
                        }
                        ?>
                    </ul>
                </div>

                <div class="search-container-box">
                    <input class="input-comentario" id="TempoDoenca" name="TempoDoenca" type="text"
                        placeholder="X dias, X horas">
                    <label class="label-comentario">Tempo de doença</label>
                </div>
            </div>

            <div class="Diagnostico">
                <div class="titulo-diagnostico">
                    <h2 class="h2Diag">Diagnósticos Secundários</h2>
                </div>

                <div class="search-container-box">
                    <input class="input-comentario" id="diagSec" name="diagSec" type="select" placeholder="J000 ...">
                    <label class="label-comentario">Diagnósticos Secundários</label>
                    <button class="botaoIco-adicionar" id="btnAdicionarDiagnosticoSec">+</button>

                    <ul class="Tabela-doenca" id="id03">
                        <?php
                        $result2 = suggestCid();
                        if ($result2->num_rows > 0) {
                            while ($row = $result2->fetch_assoc()) {
                                echo "<li>" . $row["ds_codigo"] . ": " . $row["ds_cid"] . "</li>";
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <table class="tabela-diagnostico" id="tabelaDiagSec">
                <thead class="titulo-diagnostico-tabela">
                    <tr class="titulos-tr">
                        <th>Diagnósticos Secundários</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody class="titulo-diagnostico-tabela">
                    <!-- As linhas da tabela serão adicionadas aqui -->
                </tbody>
            </table>
            <div class="botao">
                <button class="buttonPadrao"
                    onclick="gerarPDFDiagnostico('<?= $paciente ?>','<?= $idAgendamento ?>')"><span>Salvar e
                        assinar</span></button>
            </div>

    </main>

    <script>
        // Para o campo diagSec
        var inputSec = document.getElementById("diagSec");
        var ulId03 = document.getElementById("id03");

        inputSec.addEventListener("input", function () {
            if (inputSec.value.trim() !== "") {
                ulId03.style.display = "block";
            } else {
                ulId03.style.display = "none";
            }
        });

        document.addEventListener("click", function (event) {
            if (event.target !== inputSec && event.target !== ulId03 && inputSec.value.trim() !== "") {
                ulId03.style.display = "none";
            }

            if (event.target.tagName === "LI" && event.target.parentNode.id === "id03") {
                inputSec.value = event.target.textContent;
                ulId03.style.display = "none";
            }
        });

        // Para o campo diagPri
        var inputCpf = document.getElementById("diagPri");
        var ulId01 = document.getElementById("id02");

        inputCpf.addEventListener("input", function () {
            if (inputCpf.value.trim() !== "") {
                ulId01.style.display = "block";
            } else {
                ulId01.style.display = "none";
            }
        });

        document.addEventListener("click", function (event) {
            if (event.target !== inputCpf && event.target !== ulId01 && inputCpf.value.trim() !== "") {
                ulId01.style.display = "none";
            }

            if (event.target.tagName === "LI" && event.target.parentNode.id === "id02") {
                inputCpf.value = event.target.textContent;
                ulId01.style.display = "none";
            }
        });

        // Lista para armazenar os diagnósticos secundários
        var listaDiagSec = [];

        // Função para atualizar a tabela com os diagnósticos secundários
        function atualizarTabelaDiagSec() {
            var tabelaBody = document.getElementById('tabelaDiagSec').getElementsByTagName('tbody')[0];
            tabelaBody.innerHTML = ''; // Limpa o conteúdo atual da tabela

            // Itera sobre os diagnósticos secundários na lista
            listaDiagSec.forEach(function (diagSec, index) {
                // Cria uma nova linha na tabela
                var newRow = tabelaBody.insertRow();

                // Cria uma nova célula na linha e adiciona o diagnóstico secundário
                var cellDiagSec = newRow.insertCell();
                cellDiagSec.appendChild(document.createTextNode(diagSec));

                // Cria uma nova célula para o ícone de lixeira
                var cellAcao = newRow.insertCell();
                var iconExcluir = document.createElement('i');
                iconExcluir.className = 'bi bi-trash';
                iconExcluir.style.cursor = 'pointer'; // Define o cursor como ponteiro para indicar que é clicável
                iconExcluir.addEventListener('click', function () {
                    // Remove o diagnóstico secundário da lista ao clicar no ícone de lixeira
                    listaDiagSec.splice(index, 1);
                    // Atualiza a tabela após remover o diagnóstico secundário
                    atualizarTabelaDiagSec();
                });
                cellAcao.appendChild(iconExcluir);
            });
        }

        document.getElementById('btnAdicionarDiagnosticoSec').addEventListener('click', function () {
            // Obtém o valor do campo diagSec
            var diagSecValue = document.getElementById('diagSec').value;

            // Verifica se o valor não está vazio
            if (diagSecValue.trim() !== '') {
                // Adiciona o valor à lista de diagnósticos secundários
                listaDiagSec.push(diagSecValue);

                // Limpa o campo diagSec
                document.getElementById('diagSec').value = '';
                atualizarTabelaDiagSec();
            }
        });
    </script>
</body>

</html>