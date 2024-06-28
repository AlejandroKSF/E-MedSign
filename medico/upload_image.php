<?php
session_start();
include("../connect.php");

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'medico') {
    header('Location: ../acesso_negado.php');
    exit();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $targetDir = "../imagens/";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["profileImage"]["name"], PATHINFO_EXTENSION));


    $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES["profileImage"]["size"] > 500000) { // 500KB
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }


    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG, JPEG & PNG files are allowed.";
        $uploadOk = 0;
    }


    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
 
        $newFileName = uniqid('img_', true) . '.' . $imageFileType;
        $targetFile = $targetDir . $newFileName;

        if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFile)) {
    
            $filePath = $targetFile;
            $query = "UPDATE tb_medico SET ds_path_img_perfil='$filePath' WHERE cd_FuncionarioID=$userId";
            if (mysqli_query($conexao, $query)) {
                header("Location: perfil_config.php");
            } else {
                echo "Error updating record: " . mysqli_error($conexao);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
