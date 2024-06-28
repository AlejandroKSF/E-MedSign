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
    <link href='../css/Atendimento_Prescricao.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <link href='../css/Atedimento_Menu.scss' rel='stylesheet'>
    <script src="../script/onclick.js" defer></script>
    <script src="../script/navbar.js" defer></script>
    <title>Inicio Médico</title>

</head>
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
        <div class="tables">

            <ul class="links-menu-atedimento">
                <a class="menu" href="./Atendimento_Anamnese.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Anamnese </span></li>
                </a>
                <a class="menu " href="./Atendimento_Diagnostico.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
                    <li> <span>Diagnóstico</span></li>
                </a>
                <a class="menu active"
                    href="./Atendimento_Prescricao.php?id=<?= $idAgendamento ?>&cpf=<?= $paciente ?>">
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
                <h1>Nome do paciente </h1>
            </div>


            <div class="search-container-box">
                <div class="adicionar-medicamento">
                    <input class="input-comentario" type="text" id="medicamento" name="medicamento"
                        placeholder="Pesquise um medicamento...">
                    <label class="label-comentario">Adicionar medicamento</label>
                    <button class="buttonPadrao" id="btnAdicionarMedicamento"><span>Adicionar
                            Medicamento</span></button>
                </div>



                <ul class="Tabela-doenca" id="id04">

                    <?php
                
                    $result = suggestMedicamento();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<li>" . $row["nm_medicamento"] . "</li>";
                        }
                    }

                    ?>
                </ul>


            </div>


            <div class="tabela-medicamento" id="listaMedicamentos">

            </div>



            <div class="botao">

                <button class="buttonPadrao" id="btnSalvar" class="buttonCenter" style="margin-top:20px;"
                    type="button"><span>Salvar</span></button>
            </div>

        </div>
    </main>
    <script>
        var coll = document.getElementsByClassName("colapse");
        var i;
        var inputCpf = document.getElementById("medicamento");
        var ulId01 = document.getElementById("id04");

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

            if (event.target.tagName === "LI" && event.target.parentNode.id === "id04") {
                inputCpf.value = event.target.textContent;
                ulId01.style.display = "none";
            }
        });


        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.display === "block") {
                    content.style.display = "none";
                } else {
                    content.style.display = "block";
                }
            });
        }





        // Função para adicionar medicamento à lista
        // Função para gerar um ID único
        function generateUniqueId() {
            return Math.random().toString(36).substr(2, 9);
        }

        // Função para adicionar medicamento à lista
        function adicionarMedicamentoLista(nomeMedicamento) {
            var button = document.createElement("button");
            button.setAttribute("type", "button");
            button.classList.add("colapse");
            button.textContent = nomeMedicamento;

            var divContent = document.createElement("div");
            divContent.classList.add("contentColapse");
            divContent.style.display = "none"; // Oculta o conteúdo por padrão

            // Conteúdo detalhado
            var detalhesMedicamento = `
                <ul class="Detalhe-medicamento">

                    <li class="estInputs">
                    <div class="Campo-input-label">
                        <label class="label-form" for="quantidade_${generateUniqueId()}">Quantidade</label>
                        <input type="text" name="quantidade" id="quantidade_${generateUniqueId()}" placeholder="Quantidade" class="inputs">
                        </div>
                    </li>
                    <li class="estInputs">
                    <div class="Campo-input-label">
                        <label class="label-form" for="unidade_${generateUniqueId()}">Unidade</label>
                        <input type="text" name="unidade" id="unidade_${generateUniqueId()}" placeholder="Unidade" class="inputs">
                        </div>
                    </li>
                    <li class="estInputs">
                    <div class="Campo-input-label">
                        <label class="label-form" for="forma_aplicacao_${generateUniqueId()}">Forma de aplicação</label>
                        <input type="text" name="forma_aplicacao" id="forma_aplicacao_${generateUniqueId()}" placeholder="Forma de aplicação" class="inputs">
                        </div>
                    </li>
                </ul>

                <ul class="Detalhe-medicamento">
                    <li class="estInputs">
                    <div class="Campo-input-label">
                    <input type="text" name="frequencia" id="frequencia_${generateUniqueId()}" placeholder="Frequência" class="inputs">
                        <label class="label-form" for="frequencia_${generateUniqueId()}">Frequência</label>
                        </div>
                    </li>
                    <li class="radio-buttons-container">
                    <div class="campo-button">
                    <div class="radio-button">
                        <input class="radio-button__input" type="radio" id="prioridade_${generateUniqueId()}" name="prioridade" value="Senecessario"  />
                        <label  class="radio-button__label" for="prioridade1_${generateUniqueId()}"><span class="radio-button__custom"></span> Se necessário</label>
                        </div>
                        <div class="radio-button">
                        <input class="radio-button__input" type="radio" id="prioridade_${generateUniqueId()}" name="prioridade" value="Urgente" />
                        <label  class="radio-button__label" for="prioridade2_${generateUniqueId()}"><span class="radio-button__custom"></span> Urgente</label>
                        </div>
                        <div class="radio-button">
                        <input class="radio-button__input" type="radio" id="prioridade_${generateUniqueId()}" name="prioridade" value="Acriterio" />
                        <label  class="radio-button__label" for="prioridade3_${generateUniqueId()}"><span class="radio-button__custom"></span> A critério médico</label>
                        </div>
                    </div>
                    </li>
                </ul>

                <ul class="Detalhe-medicamento">
                    <li class="estInputs">
                    <div class="Campo-input-label">
                    <input type="date" name="data_inicio" id="data_inicio_${generateUniqueId()}" class="inputs">
                        <label class="label-form" for="data_inicio_${generateUniqueId()}">Data de inicio</label>
                        </div>
                    </li>
                    <li class="estInputs">
                    <div class="Campo-input-label">

                        <input type="time" name="hora_inicial" id="hora_inicial_${generateUniqueId()}" class="inputs">
                        <label class="label-form" for="hora_inicial_${generateUniqueId()}">Hora Inicial</label>
                        </div>
                    </li>
                    <li class="estInputs">
                    <div class="Campo-input-label">
                    <input type="number" name="dias_aplicacao" id="dias_aplicacao_${generateUniqueId()}" class="inputs">
                        <label class="label-form" for="dias_aplicacao_${generateUniqueId()}">Dias de aplicação</label>
                        </div>

                    </li>
                    <li class="estInputs">

                    <div class="Campo-checkbox">
                     <input class="input-checkbox" id="continuo_${generateUniqueId()}" name="checkbox" type="checkbox">
                     <label for="continuo_${generateUniqueId()}" class="label-checkbox" for="terms-checkbox-37">Continuar
                    </label>
                   </div>


                    </li>
                </ul>
                <div class="Campo-texto-label">
                    <textarea class="texto-comentario" id="observacoes_${generateUniqueId()}" name="observacoes"></textarea>
                    <label class="label-form" for="observacoes_${generateUniqueId()}">Observações</label>
                    </div>
            `;

            divContent.innerHTML = detalhesMedicamento;

            button.addEventListener("click", function () {
                divContent.classList.toggle("active");
                if (divContent.classList.contains("active")) {
                    divContent.style.display = "block";
                } else {
                    divContent.style.display = "none";
                }
            });

            var div = document.createElement("div");
            div.classList.toggle("tabela-ul");
            div.appendChild(button);
            div.appendChild(divContent);

            document.getElementById("listaMedicamentos").appendChild(div);
            document.getElementById("medicamento").value = "";
        }

        // Evento de clique para adicionar medicamento à lista
        document.getElementById("btnAdicionarMedicamento").addEventListener("click", function () {
            var nomeMedicamento = document.getElementById("medicamento").value;
            if (nomeMedicamento.trim() !== "") {
                adicionarMedicamentoLista(nomeMedicamento);
            }
        });











        document.getElementById("btnSalvar").addEventListener("click", function () {
            // Coletar todos os itens da lista
            var listaMedicamentos = document.querySelectorAll(".contentColapse");

            // Array para armazenar os dados dos medicamentos
            var dadosMedicamentos = [];

            var id = "<?= $paciente ?>"
            var agendamento = "<?= $idAgendamento ?>"

            // Loop através de cada item da lista
            listaMedicamentos.forEach(function (item) {
                // Coletar o nome do remédio
                var nomeRemedio = item.parentNode.querySelector(".colapse").textContent;

                // Coletar os dados dos inputs dentro do item
                var inputs = item.querySelectorAll("input, textarea");

                // Objeto para armazenar os dados do medicamento atual
                var dadosMedicamento = {
                    nome: nomeRemedio
                };

                // Loop através de cada input
                inputs.forEach(function (input) {
                    // Armazenar os valores dos inputs no objeto de dados
                    dadosMedicamento[input.name] = input.value;
                });

                // Adicionar os dados do medicamento ao array de dados
                dadosMedicamentos.push(dadosMedicamento);
            });

            var xhr = new XMLHttpRequest();
            var url = "gerarpdfPrescricao.php";
            var params = {
                paciente: '<?= $paciente ?>',
                dadosMedicamentos: dadosMedicamentos // Array de dados de medicamentos
            };
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var pdfHash = xhr.getResponseHeader('X-PDF-Hash');
                        console.log(pdfHash);
                        enviarPDFParaBancoPresc(id, pdfHash, agendamento);
                    } else {
                        // Tratar erros de requisição
                        console.error("Erro na requisição: " + xhr.status);
                    }
                }
            };

            // Converter objeto JavaScript para JSON e enviar
            xhr.send(JSON.stringify(params));
        });


        function enviarPDFParaBancoPresc(id, pdfHash, agendamento) {
            // Enviar o PDF base64 e o hash para o script PHP que o insere no banco de dados
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'inserirpdfnobancoPresc.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // PDF inserido no banco de dados com sucesso
                    alert('Prescricao salva com sucesso!');
                }
            };
            xhr.send('paciente=' + id + '&pdfHash=' + pdfHash + '&agendamento=' + agendamento);
        }

    </script>




</body>

</html>