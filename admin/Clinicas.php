<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="../css/Paciente.scss" rel="stylesheet">
    <link href="../css/navibar.scss" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="../script/onclick.js" defer></script>
    <title>Inicio Médico</title>
</head>

<body>
    <?php
    include("../navibar.php");

    session_start();
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type']!== 'admin') {
        // Redireciona para a página de erro de acesso negado
        header('Location: ../acesso_negado.php');
        exit(); 
    }
    $menu = generateMenu($_SESSION['user_type'], $permissions);

    // Imprimir o menu
    echo $menu;
    ?>

    <main class="cabeça">
        <div class="tablesInd">
            <div class="titulo-paciente">
                <h3 class="index">Clinicas cadastradas</h3>
            </div>

            <div class="campo-buscar-input">
                <input required placeholder="Type something..." class="ui-input" type="text">
                <div class="ui-input-underline"></div>
                <div class="ui-input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="currentColor"
                            d="M21 21L16.65 16.65M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z">
                        </path>
                    </svg>
                </div>
            </div>

            <table class="tabela-paciente">
                <thead class="tabela-titulo">
                    <tr class="cabeça-tabela-paciente">
                        <th class="titulo-cabecalho">Nome</th>
                        <th class="titulo-cabecalho">Endereco</th>
                        <th class="titulo-cabecalho">Cep</th>
                        <th class="titulo-cabecalho">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include('../main.php');
                    $result = Clinicas();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='campo-tabela' id='row_" . htmlspecialchars($row['cd_clinica']) . "'>";
                            echo "<td class='informacaion-tabela'>" . htmlspecialchars($row['nm_clinica']) . "</td>";
                            echo "<td class='informacaion-tabela'>" . htmlspecialchars($row['ds_endereco']) . "</td>";
                            echo "<td class='informacaion-tabela'>" . htmlspecialchars($row['cd_cep']) . "</td>";

                            echo "<td class='informacaion-tabela'>
                                <a class='link-icon' href='view_clinica.php?id=" . htmlspecialchars($row['cd_clinica']) . "' aria-label='View'><i class='bi bi-book'></i></a>  
                                <a class='link-icon' href='editar_Clinica.php?id=" . htmlspecialchars($row['cd_clinica']) . "' aria-label='Edit'><i class='bi bi-pencil'></i></a>  
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Nenhuma clinica cadastrada.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        document.getElementById('open_btn').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('open-sidebar');
            document.body.classList.toggle('menu-open');
        });

        $(document).ready(function() {
            $('.delete-paciente').on('click', function(e) {
                e.preventDefault();
                var pacienteId = $(this).data('id');
                if (confirm('Tem certeza de que deseja excluir este paciente?')) {
                    $.ajax({
                        url: 'remover_paciente.php',
                        type: 'POST',
                        data: { id: pacienteId },
                        success: function(response) {
                            if (response === 'success') {
                                $('#row_' + pacienteId).remove();
                            } else {
                                alert('Falha ao excluir paciente.');
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
