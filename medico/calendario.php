<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Calendar and Menu</title>
    <!-- Icons and CSS links -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='../css/calendario.scss' rel='stylesheet'>
    <link href='../css/menu.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <script src="../script/navbar.js" defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>

    </style>
</head>

<body>
    <?php
    include ("../navibar.php");
    session_start();
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type']!== 'medico') {
        // Redireciona para a página de erro de acesso negado
        header('Location: ../acesso_negado.php');
        exit(); // Certifica-se de que o script pare de executar após o redirecionamento
    }
    $menu = generateMenu($_SESSION['user_type'], $permissions);
    echo $menu;
    ?>

    <section class="cabeça">
        <div class="wrap-calendario" id="calendar">
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
                    <thead class="calhhorariocampo"></thead>
                    <tbody id="horarios"></tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Modal Structure -->
    <div id="myModal" class="modal-olho">
        <div class="modal-conteudo-olho">
            <div class="titulo-olho">
            <span class="close">&times;</span>
            <h2 class="titulo">Agendamento</h2>
            </div>
            <div class="informacao-olho">
            <p class="texto-olho" id="modal-paciente"></p>
            <p class="texto-olho" id="modal-contato"></p>
            <p class="texto-olho" id="modal-data"></p>
            <p class="texto-olho" id="modal-hora"></p>
            <p class="texto-olho" id="modal-status"></p>
</div>
        </div>
    </div>

    <script>
        var currentDate = new Date();
        var selectedMedicoId = <?= $_SESSION['user_id']; ?>;

        function generateCalendar() {
            var calendarBody = document.getElementById("calendar").querySelector("tbody");
            var calendarHead = document.getElementById("calendar").querySelector("thead");
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
                cell.innerHTML = `<div class='dia-semana'>${days[j]}</div><div class='mes-data'>${day.toLocaleDateString('pt-BR', { day: '2-digit', month: 'short' })}</div>`;
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
                    cell.classList.add("testando");
                    cell.dataset.date = new Date(firstDayOfWeek.setDate(firstDayOfWeek.getDate() + j)).toLocaleDateString('pt-BR');
                    cell.textContent = "";
                }
            }
        }

        function prevWeek() {
            currentDate.setDate(currentDate.getDate() - 7);
            updateCalendar();
        }

        function nextWeek() {
            currentDate.setDate(currentDate.getDate() + 7);
            updateCalendar();
        }

        function carregarAgendamentos() {
            fetch(`get_agendamentos.php?medico_id=${selectedMedicoId}&week=${currentDate.getTime()}`)
                .then(response => response.json())
                .then(data => {
                    var table = document.getElementById('horarios');
                    for (var i = 0; i < table.rows.length; i++) {
                        for (var j = 1; j < table.rows[i].cells.length; j++) {
                            table.rows[i].cells[j].innerHTML = '';
                        }
                    }

                    data.forEach(agendamento => {
                        var dia = new Date(agendamento.dia);
                        dia.setDate(dia.getDate() + 1);
                        var hora = agendamento.hora.split(':');
                        var rowIndex = parseInt(hora[0]) - 8;
                        var columnIndex = dia.getDay() + 1;

                        var diaSemanaAtual = new Date(currentDate);
                        diaSemanaAtual.setDate(diaSemanaAtual.getDate() - diaSemanaAtual.getDay() + columnIndex - 1);

                        if (diaSemanaAtual.getMonth() === dia.getMonth() && diaSemanaAtual.getDate() === dia.getDate()) {
                            if (rowIndex >= 0 && rowIndex < table.rows.length && columnIndex >= 1 && columnIndex < table.rows[rowIndex].cells.length) {
                                var cell = table.rows[rowIndex].cells[columnIndex];
                                cell.innerHTML += `
                                    <div class='agenda' data-agenda-id='${agendamento.id}' data-paciente='${agendamento.paciente}' data-contato='${agendamento.contato}' data-dia='${agendamento.dia}' data-hora='${agendamento.hora}' data-estado='${agendamento.estado}'>
                                        <span class='paciente'>${agendamento.paciente}</span>
                                        <span class='phone-number'>${agendamento.contato}</span>
                                        <div class='icone-agendamento'>
                                            <i class='bi bi-eye' onclick='viewAgenda(${agendamento.id})'></i>
                                            <i class='bi bi-trash3' onclick='deleteAgenda(${agendamento.id})'></i>
                                        </div>
                                    </div>`;
                            }
                        }
                    });
                })
                .catch(error => console.error('Erro ao carregar os agendamentos:', error));
        }

        function viewAgenda(agendaId) {
            var modal = document.getElementById("myModal");
            var agendas = document.getElementsByClassName("agenda");
            for (var i = 0; i < agendas.length; i++) {
                if (agendas[i].dataset.agendaId == agendaId) {
                    document.getElementById("modal-paciente").textContent = "Paciente: " + agendas[i].dataset.paciente;
                    document.getElementById("modal-contato").textContent = "Contato: " + agendas[i].dataset.contato;
                    document.getElementById("modal-data").textContent = "Data: " + agendas[i].dataset.dia;
                    document.getElementById("modal-hora").textContent = "Hora: " + agendas[i].dataset.hora;
                    document.getElementById("modal-status").textContent = "Status: " + agendas[i].dataset.estado;
                    break;
                }
            }
            modal.style.display = "block";
        }

        function deleteAgenda(agendaId) {
            if (confirm('Deseja cancelar essa agenda?')) {
                fetch('delete_agenda.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `agenda_id=${agendaId}`
                })
                .then(response => {
                    if (response.ok) {
                        alert('Solicitação de cancelado enviada.');
                        updateCalendar();
                    } else {
                        alert('Erro ao excluir a agenda. Por favor, tente novamente.');
                    }
                });
            }
        }

        function updateCalendar() {
            generateCalendar();
            carregarAgendamentos();
        }

        window.onload = function () {
            generateCalendar();
            carregarAgendamentos();
        };


        var modal = document.getElementById("myModal");


        var span = document.getElementsByClassName("close")[0];

        span.onclick = function() {
            modal.style.display = "none";
        }


        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>
