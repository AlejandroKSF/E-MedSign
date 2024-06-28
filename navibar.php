<?php
$permissions = array(
    'admin' => array(
        'Médicos' => array('url' => 'Medicos.php', 'icon' => 'bi bi-file-earmark-medical'),
        'Cadastrar Médico' => array('url' => 'cadastroMedico.php', 'icon' => ' bi bi-person-add'),
        'Atendentes' => array('url' => 'Atendentes.php', 'icon' => 'bi bi-person-vcard'),
        'Cadastrar Atendente' => array('url' => 'cadastroAtendente.php', 'icon' => 'bi bi-person-plus'),
        'Clínicas' => array('url' => 'Clinicas.php', 'icon' => 'bi bi-building'),
        'Cadastrar Clínica' => array('url' => 'cadastroClinica.php', 'icon' => 'bi bi-building-add'),
        
        

    ),
    'medico' => array(
        'Inicio' => array('url' => 'dashboardMedico.php', 'icon' => 'bi bi-speedometer'),
        'Calendario' => array('url' => 'calendario.php', 'icon' => 'bi bi-calendar2-week'),
        'Perfil' => array('url' => 'perfil_config.php', 'icon' => 'bi bi-person'),
        'Personalizar' => array('url' => 'configMedico.php', 'icon' => 'bi bi-clipboard-pulse')
    ),
    'atendente' => array(
        'Inicio' => array('url' => './dashboardAtendente.php', 'icon' => 'bi bi-house'),
        'Lista de pacientes' => array('url' => './pacientes.php', 'icon' => 'bi bi-file-earmark-medical'),
        'Cadastrar Paciente' => array('url' => './cadastro.php', 'icon' => 'bi bi-person-add'),
    )
);

// Função para gerar o menu dinamicamente com base no tipo de usuário
function generateMenu($userType, $permissions)
{
    $menu = '<nav id="sidebar" class="menu-lateral">
    <div id="sidebar_content" class="navbar">
        <div id="perfil-empresa"> 
            <img src="../imagens/Logomarca-E-MEDSIGN.png" id="user_avatar" class="imagem-empresa" alt="Avatar">
        </div><ul class="lista-do-menu">';

    foreach ($permissions[$userType] as $key => $value) {
        if (is_array($value) && isset($value['url'])) {
            $menu .= '       
            <a class="link-menu" href="' . $value['url'] . '">
            <li class="menu-links">
                <i class="bx ' . $value['icon'] . '"></i>
                <span class="link-item">
                ' . $key . '
                </span>
      
        </li>
        </a>
            
            ';
        }
    }

    $menu .= '</ul><button id="open_btn">
    <i id="open_btn_icon" class="fa-solid fa-arrow-right" onclick="toggleMenu()"></i>
</button>
</div>
<div id="logout">
<form id="logout_form" action="../logout.php" method="post">
    <button id="logout_btn" class="logout-ativo" type="submit">
    <i class="bi bi-door-closed"></i>
        <span class="link-item">
            Sair
        </span>

    </button>
</form>
</div>
</nav>';

    return $menu;
}
?>