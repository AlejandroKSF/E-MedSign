<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='../css/menu.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


</head>



<body>
    <?php
    include ("../navibar.php");

    session_start();
    $menu = generateMenu($_SESSION['user_type'], $permissions);

    // Imprimir o menu
    echo $menu;
    ?>
    <section class="cabeça">
        <!-- Calendário -->
        <div class="wrap" id="calendar">
            <div class="medicos">
                <div class="divmedico">

                    <div id="perfil-empresa">
                        <img src="../imagens/perfil.png" id="user_avatar" class="imagem-empresa" alt="Avatar">

                        <p id="user_medico">

                        </p>

                        <div class="Campo-Pesquisar">
                            <svg viewBox="0 0 24 24" aria-hidden="true" class="icon-pesquisar">
                                <g>
                                    <path
                                        d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                                    </path>
                                </g>
                            </svg>
                            <input class="Input-Pesquisar" type="search" placeholder="Pesquisar" />
                        </div>

                    </div>

                </div>


                <div class="medicosDisp">
                    <h3>Médicos em atendimento</h3>
                    <?php
                    include ('../connect.php');
                    include ('../main.php');
                    $result = GetMedicosOn($_SESSION['clinica']);
                    if ($result->num_rows > 0) {
                        // Formatando os dados dos médicos para HTML
                        while ($row = $result->fetch_assoc()) {
                            $medicos[] = "<div class='cardMedico' id='medico_" . $row['cd_FuncionarioID'] . "' onclick='carregarAgendamentos(" . $row['cd_FuncionarioID'] . ")'>" .
                                "<p class='medicodisp'>Médico: " . $row['nm_funcionario'] . "</p>" .
                                "<p class='especialidade-medico'>Especialidade: " . $row['ds_Especializacao'] . "</p>" .
                                "</div>";
                        }
                    }

                    // Saída dos dados dos médicos
                    if (isset($medicos)) {
                        echo implode("", $medicos);
                    }

                    ?>
                </div>

                <div class="medicosOff">
                    <h3>Médicos fora de expediente</h3>
                    <?php
                    $result1 = GetMedicosOff($_SESSION['clinica']);
                    if ($result1->num_rows > 0) {
                        // Formatando os dados dos médicos para HTML
                        while ($row = $result1->fetch_assoc()) {
                            $medicos1[] = "<div class='cardMedico' id='medico_" . $row['cd_FuncionarioID'] . "' onclick='carregarAgendamentos(" . $row['cd_FuncionarioID'] . ")'>" .
                                "<p class='medicodisp'>Médico: " . $row['nm_funcionario'] . "</p>" .
                                "<p class='especialidade-medico'>Especialidade: " . $row['ds_Especializacao'] . "</p>" .
                                "</div>";
                        }
                    }

                    // Saída dos dados dos médicos
                    if (isset($medicos1)) {
                        echo implode("", $medicos1);
                    }

                    ?>
                </div>

            </div>
            <div class="divCalendario">
                <div class="calendHead">

                    <div class="setasemanaanterio">
                        <i class="bi bi-arrow-left-circle" onclick="prevWeek()"></i>
                    </div>
                    <div class="calendarioagendamento">
                        <input type="date" class="calendarrioagen">
                    </div>

                    <div class="setasemanaanterio">
                        <i class="bi bi-arrow-right-circle" onclick="nextWeek()"></i>
                    </div>

                </div>
                <table class="calendario">
                    <thead class="calhhorariocampo">
                    </thead>
                    <tbody id="horarios">
                        <!-- Conteúdo da tabela gerado dinamicamente pelo JavaScript -->
                    </tbody>
                </table>
            </div>

        </div>
    </section>
    <div id="modal" class="agendar">
        <div class="modal-content">

            <div class="titulo-agendamento">
                <h2 class="titulo-">Agendamento<h2> </h2>
                    <span class="close">&times;</span>
                    <p id="modal-date-time"></p>
            </div>

            <form class="AgendarForme" id="AgendarForm" method="POST" action="novoAgendamento.php">



                <div class="input-modal-nome">
                    <label class="label-modal" for="">Nome do paciente</label>
                    <input class="input-modal" type="text" id="nome" name="nome" placeholder="Nome" required>
                </div>

                <div class="inputForm-medico">
                    <label class="label-modal" for="">Médico</label>
                    <input class="input-modal" type="text" id="medicoId" name="medicoId" placeholder="" required>
                </div>

                <div class="inputForm-numero">
                    <label class="label-modal" for="">Telefone</label>
                    <input class="input-modal" type="text" placeholder="(00)000000000" id="contato" name="contato"
                        required>
                </div>

                <div class="inputForm-numero" id="cpf">
                    <label class="label-modal" for="">CPF</label>
                    <input class="input-modal" type="text" placeholder="000.000.000-00" name="cpf" maxlength="14" oninput="formatCPF(this)">
                </div>


                <!-- exibir cpf somente se já existir cadastro !!! checkbox checked -->

                <div class="radio-buttons-container">
                    <label class="radio-button__label">
                        Já é paciente ?
                    </label>

                    <div class="radio-button">
                        <input name="radio-group" id="radio1" name="cadastrado" value="sim" class="radio-button__input"
                            type="radio">
                        <label for="radio1" class="radio-button__label">
                            <span class="radio-button__custom"></span>

                            Sim
                        </label>
                    </div>
                    <div class="radio-button">
                        <input name="radio-group" id="radio3" name="cadastrado" value="nao" class="radio-button__input"
                            type="radio">
                        <label for="radio3" class="radio-button__label">
                            <span class="radio-button__custom"></span>

                            Não
                        </label>
                    </div>
                </div>
                <div class="Agendar-horario">
                    <div class="inputForm-horario">
                        <label class="label-modal" for="">Horário</label>
                        <input class="input-modal" type="time" id="horario" name="horario" class="inputs" required>
                    </div>

                    <div class="inputForm">
                        <label class="label-modal" for="">Data da Consulta</label>
                        <input class="input-modal" type="date" id="dataConsulta" name="dataConsulta" class="inputs"
                            required onchange="validateDate()">
                    </div>

                    <button class="botao-insc" id="submitButton" type="submit" value="Agendar">
                        <span>Agendar</span></button>
                </div>
            </form>
        </div>
    </div>



    <div id="editModal" class="modal-editar-agenda">
        <div class="modal-content-editar">

            <div class="titulo-editar-agendamento">
                <h2 class="titulo-">Agendamento<h2> </h2>
                    <span class="close">&times;</span>
                    <p id="modal-date-time"></p>
            </div>

            <form id="EditagendamentoForm" class="Editar-agendamento" method="POST" action="editarAgendamento.php">


                <div class="input-modal-nome">
                    <label class="label-modal" for="">Nome do paciente</label>
                    <input class="input-modal" type="text" id="editName" name="editAgendamento" placeholder="Nome"
                        required value="">
                </div>

                <div class="inputForm-numero">
                    <label class="label-modal" for="">CPF</label>
                    <input class="input-modal" type="text" placeholder="000.000.000-00" id="editCPF" name="editCPF" maxlength="14" oninput="formatCPF(this)">
                </div>

                <div class="inputForm-numero">
                    <label class="label-modal" for="">Contato</label>
                    <input class="input-modal" type="text" id="editContato" name="editContato" required>
                </div>

                <div class="Agendar-horario">

                    <div class="inputForm-horario">
                        <label class="label-modal" for="">Horario</label>
                        <input class="input-modal" type="time" id="editHorario" name="editHorario" required>
                    </div>

                    <div class="inputForm">
                        <label class="label-modal" for="">Data</label>
                        <input class="input-modal" type="date" id="editDataConsulta" name="editDataConsulta" onchange="validateDateEdit()" required>
                    </div>
                </div>

                <button class="botao-insc" id="submitButtonEdit" type="submit" value="Salvar"> <span>Salvar</span></button>

            </form>
        </div>
    </div>

    <script>

        //CALENDARIO
        var currentDate = new Date();

        function generateCalendar() {
            var calendarBody = document.getElementById("calendar").getElementsByTagName("tbody")[0];
            var calendarHead = document.getElementById("calendar").getElementsByTagName("thead")[0];
            var days = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
            var firstDayOfWeek = new Date(currentDate);
            firstDayOfWeek.setDate(currentDate.getDate() - currentDate.getDay());

            calendarBody.innerHTML = "";
            calendarHead.innerHTML = "";

            var headerRow = calendarHead.insertRow(0);
            headerRow.classList.add("TRClass");
            var timeHeader = document.createElement("th");
            timeHeader.classList.add("HoraClass");
            timeHeader.textContent = "Hora";
            headerRow.appendChild(timeHeader);
            for (var j = 0; j < 7; j++) {
                var cell = document.createElement("th");
                var day = new Date(firstDayOfWeek);
                day.setDate(firstDayOfWeek.getDate() + j);
                var formattedDate = day.toLocaleDateString('pt-BR', { year: 'numeric', month: '2-digit', day: '2-digit' });
                cell.innerHTML = "<div class='dia-semana'>" + days[j] + "</div><div class='mes-data'>" + day.toLocaleDateString('pt-BR', { day: '2-digit', month: 'short' }) + "</div>";
                cell.classList.add("day-header");
                headerRow.appendChild(cell);
            }

            for (var hour = 8; hour <= 18; hour++) {
                var row = calendarBody.insertRow(hour - 8);
                row.classList.add("TrAgendamento");
                var timeCell = row.insertCell(0);
                timeCell.textContent = hour.toString().padStart(2, '0') + ":00";

                for (var j = 0; j < 7; j++) {
                    var cell = row.insertCell(j + 1);
                    var day = new Date(firstDayOfWeek);
                    cell.classList.add("testando");
                    day.setDate(firstDayOfWeek.getDate() + j);
                    var formattedDate = day.toLocaleDateString('pt-BR', { year: 'numeric', month: '2-digit', day: '2-digit' });
                    cell.dataset.date = formattedDate; 
                    cell.textContent = "";
                }
            }
        }


        document.addEventListener('click', function (event) {
            var target = event.target;
            if (target.classList.contains('agenda')) {
                return;
            }
            if (target.tagName === 'TD' && target.parentElement.parentElement.id === 'horarios') {

                var date = target.dataset.date; 
                var time = target.parentElement.cells[0].textContent; 
                var medicoId = selectedMedicoId;
                openModal(date, time, medicoId); 
            }
        });




        var selectedMedicoId = null;

        function prevWeek() {
            currentDate.setDate(currentDate.getDate() - 7);
            updateCalendar();
        }

        function nextWeek() {
            currentDate.setDate(currentDate.getDate() + 7);
            updateCalendar();
        }

        function carregarAgendamentos(medicoId) {
            selectedMedicoId = medicoId;
            fetch('get_agendamentos.php?medico_id=' + medicoId + '&week=' + currentDate.getTime())
                .then(response => response.json())
                .then(data => {
                    var table = document.getElementById('horarios');
                    // Limpar todas as células da tabela antes de adicionar novos agendamentos
                    for (var i = 0; i < table.rows.length; i++) {
                        for (var j = 1; j < table.rows[i].cells.length; j++) {
                            table.rows[i].cells[j].innerHTML = '';
                        }
                    }


                    // Adicionar os novos agendamentos
                    data.forEach(agendamento => {
                        console.log(agendamento);
                        var dia = new Date(agendamento.dia);
                        dia.setDate(dia.getDate() + 1);
                        console.log(dia);
                        var hora = agendamento.hora.split(':');
                        var horaFormatada = hora[0] + ':' + hora[1];
                        var rowIndex = parseInt(hora[0]) - 8;
                        var columnIndex = dia.getDay() + 1;
                        // Verificar se o dia pertence à semana atual
                        var diaSemanaAtual = new Date(currentDate);
                        diaSemanaAtual.setDate(diaSemanaAtual.getDate() - diaSemanaAtual.getDay() + columnIndex - 1);
                        if (diaSemanaAtual.getMonth() === dia.getMonth() && diaSemanaAtual.getDate() === dia.getDate()) {
                            // Verificar se a célula está dentro dos limites da tabela
                            if (rowIndex >= 0 && rowIndex < table.rows.length && columnIndex >= 1 && columnIndex < table.rows[rowIndex].cells.length) {
                                var cell = table.rows[rowIndex].cells[columnIndex];
                                var content = "";

                                if (agendamento.idpaciente === null) {
                                    

                                    content += "<div class='agenda' data-agenda-id='" + agendamento.id + "'>" +
                                        "<span class='paciente'>" + agendamento.paciente + "</span>" +
                                        "<span class='phone-number'>" + agendamento.contato + "</span>" +
                                        "<span class='paciente'>" + agendamento.status + "</span>" +
                                        "<div class='icone-agendamento'>" +
                                        "<a class='semStyle' href='cadastro.php?id=" + agendamento.id + "'><i class='bi bi-person-plus'></i></a>" +
                                        "<i class='bi bi-pencil-square' data-agendamento-id='" + agendamento.id + "'></i>" +
                                        "<i class='bi bi-trash3'></i>" +
                                        "</div>" +
                                        "</div>";
                                } else {
                                   
                                    content += "<div class='agenda' data-agenda-id='" + agendamento.id + "'>" +
                                        "<span class='paciente'>" + agendamento.paciente + "</span>" +
                                        "<span class='phone-number'>" + agendamento.contato + "</span>" +
                                        "<div class='icone-agendamento'>" +
                                        "<i class='bi bi-pencil-square' data-agendamento-id='" + agendamento.id + "'></i>" +
                                        "<i class='bi bi-trash3'></i>" +
                                        "</div>" +
                                        "</div>";
                                }

                                cell.innerHTML = content;
                            }
                        }
                    });

                })
                .catch(error => {
                    console.error('Erro ao carregar os agendamentos:', error);
                });
        }

        function updateCalendar() {
            var calendarBody = document.getElementById("calendar").getElementsByTagName("tbody")[0];
            calendarBody.innerHTML = "";
            generateCalendar();
            if (selectedMedicoId !== null) {
                carregarAgendamentos(selectedMedicoId);
            }
        }

        // Obtém o modal
        var modal = document.getElementById("modal");
        var modal2 = document.getElementById("editModal");
        var closeModal1 = document.querySelector("#modal .close");
        var closeModal2 = document.querySelector("#editModal .close");

        // Quando o usuário clica no fechamento (x) do primeiro modal, fecha o modal
        closeModal1.onclick = function () {
            modal.style.display = "none";
        }

        // Quando o usuário clica no fechamento (x) do segundo modal, fecha o modal2
        closeModal2.onclick = function () {
            modal2.style.display = "none";
        }


        // Modifique a função openModal para aceitar o medicoId
        function openModal(date, time, medicoId) {
            var modal = document.getElementById("modal");
            var modalDateInput = document.getElementById("dataConsulta");
            var modalTimeInput = document.getElementById("horario");
            var modalMedicoIdInput = document.getElementById("medicoId"); // Novo elemento adicionado
            var modalCPFInput = document.getElementById("cpf");
            var radio1 = document.getElementById("radio1");
            var radio3 = document.getElementById("radio3");

            var formattedDate = date.split('/').reverse().join('-');

            modalDateInput.value = formattedDate;
            modalTimeInput.value = time.split(':')[0] + ':' + time.split(':')[1];
            modalMedicoIdInput.value = medicoId;
            modal.style.display = "block";
            radio1.checked = true;
        }

        radio1.addEventListener("change", function () {
            cpfInput = document.getElementById("cpf");
            cpfInput.style.display = "block"; // Mostra o campo CPF quando o radio1 é selecionado
        });

        radio3.addEventListener("change", function () {
            cpfInput = document.getElementById("cpf");
            cpfInput.style.display = "none"; // Oculta o campo CPF quando o radio3 é selecionado
        });

        document.addEventListener('click', function (event) {
            var target = event.target;
            if (target.classList.contains('agenda')) {
                // Se o clique for na agenda, não faz nada
                return;
            }
            if (target.tagName === 'I' && target.classList.contains('bi') && target.classList.contains('bi-trash3')) {
                // Se o clique for no ícone de exclusão
                var agendaId = target.closest('.agenda').dataset.agendaId;
                var phoneNumber = target.closest('.agenda').querySelector('.phone-number').textContent;
                var confirmation = confirm('Deseja cancelar essa agenda?\n Entre em contato com o paciente pelo telefone: ' + phoneNumber);
                if (confirmation) {

                    deleteAgenda(agendaId);
                    updateCalendar();
                } else {
                    // Se o usuário cancelar, não faz nada
                    alert('O agendamento não foi cancelado');
                }
            }

        });


        function deleteAgenda(agendaId) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_agenda.php'); // Caminho para o script PHP que lidará com a exclusão
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                   
                    alert('Agendamento cancelado.');
                    
                } else {
                    // Se houver algum erro ao excluir a agenda
                    alert('Erro ao excluir a agenda. Por favor, tente novamente.');
                }
            };
            xhr.send('agenda_id=' + encodeURIComponent(agendaId)); // Envie o ID da agenda para o script PHP
        }



        document.addEventListener('click', function (event) {
            var target = event.target;
            if (target.classList.contains('agenda')) {
                // Se o clique for na agenda, não faz nada
                return;
            }
            if (target.tagName === 'I' && target.classList.contains('bi') && target.classList.contains('bi-pencil-square')) {
                // Se o clique for no ícone de edição
                var agendamentoId = target.dataset.agendamentoId;
                console.log(agendamentoId);
                openEditModal(agendamentoId);
            }
        });
        function openEditModal(agendamentoId) {
            var modal = document.getElementById("editModal");

            fetch('get_agendamento_details.php?agendamento_id=' + agendamentoId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    
                    document.getElementById('editName').value = data.nm_paciente;
                    document.getElementById('editCPF').value = data.cpf;
                    document.getElementById('editHorario').value = data.hr_horario;
                    document.getElementById('editDataConsulta').value = data.dt_data;
                    document.getElementById('editContato').value = data.tel;
                })
                .catch(error => {
                    console.error('Erro na solicitação:', error);
                });
            modal.style.display = "block";

        }


        function validateDate() {
            const dateInput = document.getElementById('dataConsulta');
            const submitButton = document.getElementById('submitButton');
            const selectedDate = new Date(dateInput.value);
            const currentDate = new Date();
            
            currentDate.setHours(0, 0, 0, 0);

            if (selectedDate <= currentDate) {
                alert("A data da consulta deve ser posterior à data de hoje.");
                submitButton.disabled = true;
            } else {
                submitButton.disabled = false;
            }
        }


        function validateDateEdit() {
            const dateInput = document.getElementById('editDataConsulta');
            const submitButton = document.getElementById('submitButtonEdit');
            const selectedDate = new Date(dateInput.value);
            const currentDate = new Date();
            
            currentDate.setHours(0, 0, 0, 0);

            if (selectedDate <= currentDate) {
                alert("A data da consulta deve ser posterior à data de hoje.");
                submitButton.disabled = true;
            } else {
                submitButton.disabled = false;
            }
        }

        function formatCPF(cpfInput) {
            let cpf = cpfInput.value.replace(/\D/g, ""); 
            if (cpf.length > 11) {
                cpf = cpf.substring(0, 11); 
            }

            // Add formatting
            cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
            cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
            cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

            cpfInput.value = cpf;
        }


        document.addEventListener('DOMContentLoaded', function () {
            validateDate();
        });


        document.addEventListener("DOMContentLoaded", function () {
            var form = document.getElementById("EditagendamentoForm");

            form.addEventListener("submit", function (event) {
                event.preventDefault();

 
                var formData = new FormData(form);


                fetch("editarAgendamento.php", {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            
                            updateCalendar();
                            alert("Agendamento alterado com sucesso!!!");

                            
                        } else {
                           
                            alert("Falha em alterar o agendamento. Erro: " + data.error);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        // Show error message if there's a network error or other issues
                        alert("Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.");
                    });
            });
        });

        window.onload = function () {
            generateCalendar();
            carregarAgendamentos();
        };


        document.getElementById('open_btn').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('open-sidebar');
            document.body.classList.toggle('menu-open')
        });


    </script>




</body>

</html>