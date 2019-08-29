<?php

require("BD.php");

$conexion = mysqli_connect($host,$username,$password,$db_name);
mysqli_set_charset($conexion,"utf8");

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->codigo) && !empty($data->contrasena)){
    if(strlen(trim($data->codigo)) <= 50){
        $codigo = mysqli_real_escape_string($conexion, trim($data->codigo));;
    }
    else{
        $projects[0]['success'] = 0;
        $projects[0]['mensaje'] = 'El correo no debe tener mas de 50 caracteres, no se puede insertar.';
        echo json_encode($projects);
        return false;
    }

    if(strlen(trim($data->contrasena)) <= 50){
        $contrasena = mysqli_real_escape_string($conexion, trim($data->contrasena));;
    }
    else{
        $projects[0]['success'] = 0;
        $projects[0]['mensaje'] = 'La contrasena no debe tener mas de 250 caracteres, no se puede insertar.';
        echo json_encode($projects);
        return false;
    }
}
else if(empty($data->codigo)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El usuario esta vacio';
    echo json_encode($projects);
    return false;
}
else if(empty($data->contrasena)){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'La contrasena esta vacia';
    echo json_encode($projects);
    return false;
}

$consulta = "SELECT COUNT(usuid) conteo, usuid, contrasena, nombre, rol
                FROM usuario 
                WHERE codigo = '$codigo' 
                GROUP BY usuid, contrasena, nombre, rol";
$resultado = mysqli_query($conexion,$consulta);

$row=mysqli_fetch_row($resultado);

if($row[0] == 0){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'El usuario no existe';
    echo json_encode($projects);
    return false;
}

if($row[2] != $contrasena){
    $projects[0]['success'] = 0;
    $projects[0]['mensaje'] = 'La contrasena no coincide';
    echo json_encode($projects);
    return false;
}

$projects[0]['success'] = 1;
$projects[0]['mensaje'] = 'Se encontro la informacion';
echo json_encode($projects);

mysqli_close($conexion);

?>