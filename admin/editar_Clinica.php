<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='../css/cadastroClinica.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">
    <title>Inicio Médico
    </title>

</head>

<body>

    <?php
    include ("../navibar.php");
    include('../connect.php');

    session_start();
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type']!== 'admin') {
        // Redireciona para a página de erro de acesso negado
        header('Location: ../acesso_negado.php');
        exit(); 
    }
    $menu = generateMenu($_SESSION['user_type'], $permissions);

    // Imprimir o menu
    echo $menu;
    $clinica = $_GET['id'];
    $conn = $conexao;
    $sqlClinica = "SELECT * FROM tb_clinica WHERE cd_clinica = ?";
    $stmt = $conn->prepare($sqlClinica);
    $stmt->bind_param('i', $clinica);
    $stmt->execute();
    $result1 = $stmt->get_result()->fetch_assoc();

    ?>

    <main class="cabeca-cadastro">
        <div class="cadastro-paciente">
            <div class="titulo-cadastro">
                <h1>
                    Cadastrar Clinica
                </h1>
            </div>
            


            <form class="formulario-cadastro" method="POST" action="update_Clinica.php" enctype="multipart/form-data">

            <aside class="informcao-geral">
            <input type="hidden" name="cd_clinica" value="<?php echo htmlspecialchars($result1['cd_clinica']); ?>">
                <div class="input-group">
                    <input required="" autocomplete="off" class="input" required="" type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($result1['nm_clinica']); ?>">
                    <label class="user-label">Nome</label>
                </div>

                <div class="input-group">
                    <input required="" autocomplete="off" class="input" required="" type="text" name="endereco" id="endereco" value="<?php echo htmlspecialchars($result1['ds_endereco']); ?>">
                    <label class="user-label">Endereco</label>
                </div>
                <div class="input-group">
                    <input required="" autocomplete="off" class="input" required="" type="text" name="cep" id="cep" value="<?php echo htmlspecialchars($result1['cd_cep']); ?>">
                    <label class="user-label">CEP</label>
                </div>

                
                <div class="input-group">

                <label for="logo" class="custom-file-upload">
                     Importar foto
                 </label>
                 <input autocomplete="off" class="input" required="" type="file" name="logo" id="logo" />
                </div>
                </aside>

                <!-- Outros campos do formulário, botão de envio, etc. -->
                <div class="botao-final">
                <button class="buttonPadrao" value="Agendar"> <span>Salvar</span></button>
                </div>
            </form>
        </div>
    </main>
    <script>
        document.getElementById('open_btn').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('open-sidebar');
            document.body.classList.toggle('menu-open')
        });
    </script>
</body>

</html>