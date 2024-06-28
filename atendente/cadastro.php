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
    <link href='../css/cadastro.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">
    <title>Inicio Médico
    </title>

</head>

<body>

    <?php
    include ("../navibar.php");

    session_start();
    $menu = generateMenu($_SESSION['user_type'], $permissions);

    // Imprimir o menu
    echo $menu;

    if (isset($_GET['id'])) {
        $atendimento = $_GET['id'];
    }

    ?>

    <main class="cabeca-cadastro">
        <div class="cadastro-paciente">
            <div class="titulo-cadastro">
                <h1>
                    Cadastro de paciente
                </h1>
            </div>



            <form class="formulario-cadastro" method="PUT" action="cadastro_paciente.php">
                <legend class="titulo-formulario">Dados Pessoais</legend>

                <input type="hidden" name="id_atendimento" value="<?php echo htmlspecialchars($atendimento); ?>">
                <div class="input-group">
                    <input required="" autocomplete="off" class="input" required="" type="text" name="nome" id="nome">
                    <label class="user-label">Nome</label>
                </div>





                <div class="input-group">
                    <label class="user-label-sem-efeito">Gênero</label>
                    <select class="seletor" id="genero" name="genero" required>
                        <option value="masculino">Masculino</option>
                        <option value="feminino">Feminino</option>
                        <option value="outro">Outro</option>
                    </select>

                </div>

                <div class="input-group">
                    <label class="user-label-sem-efeito">Estado Civil</label>
                    <select class="seletor" id="estado_civil" name="estado_civil" required>
                        <option value="Solteiro(a)">Solteiro(a)</option>
                        <option value="Casado(a)">Casado(a)</option>
                        <option value="Divorciado(a)">Divorciado(a)</option>
                        <option value="Viúvo(a)">Viúvo</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>




                <div class="input-group">
                    <input class="input" type="text" placeholder="000.000.000-00" id="cpf" name="cpf" maxlength="14"
                        oninput="formatCPF(this)">
                    <label class="user-label">CPF</label>
                </div>


                <div class="input-group">
                    <input required="" autocomplete="off" class="input-data" required="" type="date"
                        id="data_nascimento" name="data_nascimento" onchange="validateDate()">
                    <label class="user-label-sem-efeito">Data de Nascimento</label>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="cns" name="cns" required>
                    <label class="user-label">CNS</label>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="nome_mae" name="nome_mae" required>
                    <label class="user-label">Nome da Mãe</label>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="nome_pai" name="nome_pai" required>
                    <label class="user-label">Nome do Pai</label>
                </div>


                <legend class="titulo-formulario">Endereço</legend>

                <div class="input-group">
                    <input required="" autocomplete="off" class="input" required="" type="text" id="rua" name="rua">
                    <label class="user-label">Rua</label>
                </div>

                <div class="input-group">
                    <input required="" autocomplete="off" class="input" required="" type="text" id="cidade"
                        name="cidade">
                    <label class="user-label">Cidade</label>
                </div>

                <div class="input-group">
                    <input required="" autocomplete="off" class="input" required="" type="text" id="bairro"
                        name="bairro">
                    <label class="user-label">Bairro</label>
                </div>


                <div class="input-group">
                    <input required="" autocomplete="off" class="input" required="" type="text" id="estado"
                        name="estado">
                    <label class="user-label">Estado</label>
                </div>

                <div class="input-group">
                    <input required="" autocomplete="off" class="input" required="" type="text" id="cep" name="cep">
                    <label class="user-label">CEP</label>
                </div>

                <legend class="titulo-formulario">Contato</legend>

                <div class="input-group">
                    <input autocomplete="off" class="input" required="" type="text" id="email" name="email">
                    <label class="user-label">Email</label>
                </div>

                <div class="input-group">
                    <input autocomplete="off" class="input" required="" type="text" id="telefone" name="telefone"
                        required>
                    <label class="user-label">Telefone</label>
                </div>

                <legend class="titulo-formulario">Plano</legend>



                <div class="input-group">
                    <input autocomplete="off" class="input" required="" type="text" id="convenio" name="convenio">
                    <label class="user-label">Convênio</label>
                </div>

                <div class="input-group">
                    <input autocomplete="off" class="input" required="" type="text" id="plano" name="plano">
                    <label class="user-label">Plano</label>
                </div>


                <div class="input-group">
                    <input autocomplete="off" class="input" required="" type="text" id="numero_carteira"
                        name="numero_carteira">
                    <label class="user-label">Número da Carteira</label>
                </div>

                <button class="buttonPadrao" id="submitButton" type="submit" value="Agendar"> <span>Salvar</span></button>
            </form>
        </div>
    </main>
    <script>


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
        function validateDate() {
            const dateInput = document.getElementById('data_nascimento');
            const submitButton = document.getElementById('submitButton');
            const selectedDate = new Date(dateInput.value);
            const currentDate = new Date();

            currentDate.setHours(0, 0, 0, 0);

            if (selectedDate > currentDate) {
                alert("A data de nascimento deve ser maior do que a data atual");
                console.log(currentDate);
                submitButton.disabled = true;
            } else {
                submitButton.disabled = false;
            }
        }


        document.getElementById('open_btn').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('open-sidebar');
            document.body.classList.toggle('menu-open')
        });
    </script>
</body>

</html>