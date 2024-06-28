<!DOCTYPE html>
<html lang="en" dir="ltr">

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
    <link href='../css/perfil.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <script src="../script/onclick.js" defer></script>
    <script src="../script/navbar.js" defer></script>


</head>
<style>
    /*# sourceMappingURL=style2.css.map */
</style>

<body>

    <?php
    include ("../connect.php");

    // Verifica se o usuário está logado
    session_start();
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'medico') {
        // Redireciona para a página de erro de acesso negado
        header('Location: ../acesso_negado.php');
        exit(); 
    }
    if (!isset($_SESSION['user_id'])) {
        // Redireciona para a página de login se o usuário não estiver logado
        header("Location: login.php");
        exit();
    }

    $userId = $_SESSION['user_id'];

    // Consulta SQL para selecionar os dados do funcionário com base no user_id
    $query = "SELECT func.nm_funcionario as nome, cli.nm_clinica as clinica, med.cd_Crm as crm, med.ds_especializacao as especialidade, med.ds_path_img_perfil as img FROM tb_medico as med join tb_funcionario as func on func.cd_funcionario = med.cd_FuncionarioID join tb_clinica as cli on cli.cd_clinica = func.cd_clinica WHERE med.cd_FuncionarioID = $userId";
    $result = mysqli_query($conexao, $query);

    if (!$result) {
        // Se houver erro na consulta, exibe uma mensagem de erro
        echo "Erro ao executar a consulta: " . mysqli_error($conexao);
    } else {
        // Se a consulta for bem-sucedida, exibe os dados na página HTML
        $row = mysqli_fetch_assoc($result);
        $nome = $row['nome'];
        $crm = $row['crm'];
        $especialidade = $row['especialidade'];
        $clinica = $row['clinica'];
        $img = $row['img'];

        ?>
        <?php
        include ("../navibar.php");

        $menu = generateMenu($_SESSION['user_type'], $permissions);

        // Imprimir o menu
        echo $menu;


        ?>

        <main class="home-section">
            <!-- conteudo -->
            <aside class="conteudo">
                <div class="titulos">
                    <h1>Perfil médico</h1>
                </div>

                <section class="centro">
                    <div class="imagem-perfil">



                        <img src="<?php echo $img; ?>" class="imagem" id="profileImage">
                        <button class="Btn" type="button" onclick="document.getElementById('profileImageInput').click()">
                            <svg class="svg" viewBox="0 0 512 512">
                                <path
                                    d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z">
                                </path>
                            </svg>
                        </button>
                        <form id="uploadForm" action="upload_image.php" method="post" enctype="multipart/form-data">
                            <input type="file" id="profileImageInput" name="profileImage" style="display: none;"
                                onchange="document.getElementById('uploadForm').submit()">
                        </form>
                    </div>

                    <p>Nome: <?php echo $nome; ?></p>
                    <p>CRM: <?php echo $crm; ?></p>
                    <p>Especialidade: <?php echo $especialidade; ?></p>
                    <p>Clínica: <?php echo $clinica; ?></p>
                </section>
                </section>

                <section class="conteudoPrincipal">
                    <aside class="conteudo-certificado">
                        <p>Certificado digital: ATIVO</p>


                        <p>validade: 10/02/2001 às 14:00:00</p>
                    </aside>
                    <form class="botao-form" id="uploadForm" action="uploadCert.php" method="post"
                        enctype="multipart/form-data">

                        <label for="fileToUpload" class="custom-file-upload">
                            Importar Certificado
                        </label>
                        <input id="fileToUpload" type="file" onchange="showSubmitButton()" />


                        <button class="botao-enviar" id="submitButton" style="display:none;"
                            value="Enviar Arquivo"></button>
                    </form>



            </aside>

        </main>
        <script>
            function showSubmitButton() {
                var fileInput = document.getElementById('fileToUpload');
                var submitButton = document.getElementById('submitButton');

                if (fileInput.files.length > 0) {
                    submitButton.style.display = 'block';
                } else {
                    submitButton.style.display = 'none';
                }
            }
        </script>
    </body>



    </html>
    <?php
    }
    ?>